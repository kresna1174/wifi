<?php

namespace App\Http\Controllers;

use App\deposit;
use App\Libraries\BulanIndo;
use App\pelanggan;
use App\pemasangan;
use Illuminate\Http\Request;
use App\pembayaran;
use App\pembayaran_detail;
use App\tagihan;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Pembayaran as GlobalPembayaran;
use Yajra\DataTables\Facades\DataTables;

class PembayaranController extends Controller
{
    public function index() {
        return view('pembayaran.index', ['title' => 'Pembayaran']);
    }

    public function get() {
        $pelanggan = pembayaran::join('pemasangan', 'pembayaran.id_pemasangan', '=', 'pemasangan.id')
        ->join('pembayaran_detail', 'pembayaran.id', '=', 'pembayaran_detail.id_pembayaran')
        ->join('tagihan', 'pembayaran_detail.id_tagihan', '=', 'tagihan.id')
        ->join('pelanggan', 'pemasangan.id_pelanggan', '=', 'pelanggan.id')
        ->select('pelanggan.nama_pelanggan', 'pembayaran.bayar', 'pembayaran.tanggal_bayar', 'pemasangan.tarif', 'pemasangan.alamat_pemasangan', 'tagihan.tagihan')
        ->groupBy('pelanggan.nama_pelanggan', 'pembayaran.bayar', 'pembayaran.tanggal_bayar', 'pemasangan.tarif', 'pemasangan.alamat_pemasangan', 'tagihan.tagihan')
        ->get();
        foreach($pelanggan as $row) {
            $row->tanggal_bayar = BulanIndo::tanggal_indo($row->tanggal_bayar);
        }
        return DataTables::of($pelanggan)
            ->make(true);
    }

    public function get_pembayaran(Request $request) {
        $find_pemasangan = pemasangan::where('no_pemasangan', $request->no_pemasangan)->first();
        $pemasangan = pemasangan::with(['tagihan' => function($p) use($find_pemasangan) {
            $p->join('pemasangan', 'tagihan.id_pemasangan', 'pemasangan.id')
            ->where('tagihan.id_pemasangan', $find_pemasangan->id)
            ->where('status_bayar', 0)
            ->select('tagihan.id as tagihan_id', 'tagihan.tanggal_tagihan', 'tagihan.sisa_tagihan', 'tagihan.id_pemasangan', 'tagihan.tagihan', 'pemasangan.alamat_pemasangan')
            ->get();
        }])
            ->where('no_pemasangan', $request->no_pemasangan)
            ->where('id_pelanggan', $request->id_pelanggan)
            ->get();
        $data = [];
        foreach($pemasangan as $row) {
            $tanggal = $row->tanggal_tagihan;
            $t = date('t', strtotime($row->tanggal_pemasangan));
            if($tanggal == 32 || $tanggal > $t) {
                $tanggal = $t;
            }
            foreach($row->tagihan as $p) {
                $p->tanggal_tagihan = BulanIndo::tanggal_indo($p->tanggal_tagihan);
                $data[] = $p;
            }
        }
        return $data;
    }

    public function create() {
        $pelanggan = pelanggan::pluck('nama_pelanggan', 'id');
        $pemasangan =  pemasangan::leftjoin('pelanggan', 'pemasangan.id_pelanggan', '=', 'pemasangan.id_pelanggan')
        ->leftjoin('tagihan', 'pemasangan.id', '=', 'tagihan.id_pemasangan')
        ->where('tagihan.status_bayar', 0)
        ->select(['pemasangan.id_pelanggan', 'pemasangan.tarif', 'tagihan.tanggal_tagihan', 'pemasangan.alamat_pemasangan', 'tagihan.id', 'pemasangan.id as pemasangan_id'])
        ->groupBy(['pemasangan.id_pelanggan', 'pemasangan.tarif', 'tagihan.tanggal_tagihan', 'pemasangan.alamat_pemasangan', 'tagihan.id', 'pemasangan.id'])
        ->get();
        foreach($pemasangan as $row) {
            $pemasangan->id_pelanggan = $row->id_pelanggan;
        }
        return view('pembayaran.create', ['pelanggan' => $pelanggan, 'pemasangan' => $pemasangan, 'title' => 'Pembayaran']);
    }

    public function store(Request $request) {
        $rules = self::validation($request->all());
        $messages = self::validation_message($request->all());
        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->fails()) {
            return response()->json([
                'message' => 'Terjadi Kesalahan Input',
                'errors' => $validator->messages()
            ], 400);
        }

        $deposit = deposit::where('id_pelanggan', $request->nama_pelanggan)->where('no_pemasangan', $request->no_pemasangan)->first();
        if($deposit != null) {
            if($deposit->jumlah_deposit == 0) {
                if($request->deposit != null) {
                    $deposit->update(['jumlah_deposit' => $request->deposit]);
                }
            } else if($request->bayar > $deposit->jumlah_deposit) {
                $total_deposit = $deposit->jumlah_deposit + $request->deposit;
            } else {
                $total_deposit = $deposit->jumlah_deposit - $request->bayar;
            }
            $deposit->update(['jumlah_deposit' => $total_deposit]);
        } else {
            if($request->deposit != null) {
                $ins_deposit = [
                    'id_pelanggan' => $request->nama_pelanggan,
                    'jumlah_deposit' => $request->deposit,
                    'no_pemasangan' => $request->no_pemasangan,
                    'tanggal' => date('Y-m-d'),
                    'deleted' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::user()->name,
                ];
                $deposit = deposit::create($ins_deposit);
            }
        }
        $pemasangan = pemasangan::where('no_pemasangan', $request->no_pemasangan)->get();
        foreach($pemasangan as $row) {
            $id[] = $row->id;
        }
        $model = tagihan::whereIn('id_pemasangan', $id)
            ->where('status_bayar', 0)
            ->orderBy('tanggal_tagihan', 'ASC')
            ->get();
        $total_bayar = $request->bayar;
        foreach($model as $row) {
            if($total_bayar >= $row->sisa_tagihan) {
                $total_bayar -= $row->sisa_tagihan;
                $bayar = $row->sisa_tagihan;
            } else {
                $bayar = $total_bayar;
                $total_bayar = 0;
            }
            $row->sisa_tagihan -= $bayar;
            $row->updated_at = date('Y-m-d H:i:s');
            $row->updated_by = Auth::user()->name;
            if($row->sisa_tagihan <= 0) {
                $row->status_bayar = 1;
            }
            $arrayPembayaran = [
                'id_pemasangan' => $row->id_pemasangan,
                'bayar' => $request->bayar,
                'tanggal_bayar' => date('Y-m-d'),
                'deleted' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::user()->name
            ];
            $pembayaran = pembayaran::create($arrayPembayaran);
            pembayaran_detail::create(['id_pembayaran' => $pembayaran->id, 'id_tagihan' => $row->id, 'jumlah_bayar' => $request->bayar]);
            $save = $row->save();
        }
        if($save) {
            return [
                'success' => true,
                'message' => 'Pembayaran Berhasil'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Pembayaran Gagal'
            ];
        }
    }

    public function validation() {
        return [
            'nama_pelanggan' => 'required',
            'no_pemasangan' => 'required',
            'alamat_pemasangan' => 'required',
            'total_bayar' => 'required',
            'bayar' => 'required',
        ];
    }

    public function validation_message() {
        $messages = [];
        $messages['nama_pelanggan.required'] = 'Nama Pelanggan Harus Di Isi';
        $messages['no_pemasangan.required'] = 'No Pemasangan Harus Di Isi';
        $messages['alamat_pemasangan.required'] = 'Alamat Pemasangan Harus Di Isi';
        $messages['total_bayar.required'] = 'Total Bayar Harus Di Isi';
        $messages['bayar.required'] = 'Tunai Harus Di Isi';
        return $messages;
    }
}
