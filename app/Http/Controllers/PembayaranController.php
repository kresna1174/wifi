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
            ->select('tagihan.id as tagihan_id', 'tagihan.id_pemasangan', 'pemasangan.alamat_pemasangan', 'pemasangan.tarif', 'tagihan.tanggal_tagihan', 'tagihan.tagihan')
            ->get();
        }])
            ->where('no_pemasangan', $request->no_pemasangan)
            ->where('id_pelanggan', $request->id_pelanggan)
            ->get();
        $data = [];
        foreach($pemasangan as $row) {
            $tanggal_pemasangan = explode('-', $row->tanggal_pemasangan);
            foreach($row->tagihan as $p) {
                if($p->tanggal_tagihan == 32) {
                    $p->tanggal_tagihan = date('t', strtotime($row->tanggal_pemasangan));
                }
                $p->tanggal_tagihan = BulanIndo::tanggal_indo($tanggal_pemasangan[0].'-'.$tanggal_pemasangan[1].'-'.$p->tanggal_tagihan);
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

        $deposit = deposit::where('id_pelanggan', $request->nama_pelanggan)->first();
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
                    'tanggal' => date('Y-m-d'),
                    'deleted' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::user()->name,
                ];
                $deposit = deposit::create($ins_deposit);
            }
        }

        $model = tagihan::where('id', $request->id_tagihan)
            ->where('status_bayar', 0)
            ->get();
        foreach($model as $row) {
            $pembayaran = [
                'id_pemasangan' => $row->id_pemasangan,
                'bayar' => $request->bayar,
                'tanggal_bayar' => date('Y-m-d'),
                'deleted' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::user()->name,
            ];

            if($insert_pembayaran = pembayaran::create($pembayaran)) {
                pembayaran_detail::create(['id_pembayaran' => $insert_pembayaran->id, 'id_tagihan' => $row->id]);
                $tagihan = [
                    'status_bayar' => 1,
                    'sisa_tagihan' => $request->sisa ?? 0,
                    'updated_at' => date('Y-m-d H:i:s'), 
                    'updated_by' => Auth::user()->name
                ];
                $update_tagihan = tagihan::findOrFail($row->id)->update($tagihan);
                if($row->tagihan - $request->bayar !== 0 && $row->tagihan - $request->bayar > 0) {
                    $in_tagihan = [
                        'id_pemasangan' => $row->id_pemasangan,
                        'tanggal_tagihan' => $row->tanggal_tagihan,
                        'status_bayar' => 0,
                        'deleted' => 0,
                        'tagihan' => $request->sisa ?? 0,
                        'sisa_tagihan' => 0,
                        'tanggal_tagihan_terakhir' => $row->tanggal_tagihan_terakhir,
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => Auth::user()->name
                    ];
                    $ins_tagihan = tagihan::create($in_tagihan);
                }
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
