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
        ->join('tagihan', 'pembayaran.id_tagihan', '=', 'tagihan.id')
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
        $pemasangan = pemasangan::leftjoin('pelanggan', 'pemasangan.id_pelanggan', '=', 'pemasangan.id_pelanggan')
            ->leftjoin('tagihan', 'pemasangan.id', '=', 'tagihan.id_pemasangan')
            ->where('tagihan.status_bayar', 0)
            ->where('pemasangan.id_pelanggan', $request->id_pelanggan)
            ->where('pemasangan.no_pemasangan', $request->no_pemasangan)
            ->select(['pemasangan.id_pelanggan', 'pemasangan.tarif', 'tagihan.tanggal_tagihan', 'pemasangan.alamat_pemasangan', 'tagihan.id as tagihan_id', 'pemasangan.id as pemasangan_id', 'pelanggan.id as pelanggan_id', 'tagihan.tagihan'])
            ->groupBy(['pemasangan.id_pelanggan', 'pemasangan.tarif', 'tagihan.tanggal_tagihan', 'pemasangan.alamat_pemasangan', 'tagihan.id', 'pemasangan.id', 'pelanggan.id', 'tagihan.tagihan'])
            ->get();
        foreach($pemasangan as $row) {
            $row->tanggal_tagihan = BulanIndo::tanggal_indo($row->tanggal_tagihan);
        }
        return $pemasangan;
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
        $model = pemasangan::where('no_pemasangan', $request->no_pemasangan)->first();
        $data = [
            'id_pemasangan' => $model->id,
            'id_tagihan' => $request->id_tagihan,
            'bayar' => $request->bayar,
            'tanggal_bayar' => date('Y-m-d'),
            'deleted' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => Auth::user()->name,
        ];
        $deposit = deposit::where('id_pelanggan', $request->nama_pelanggan)->first();
        if($deposit != null) {
            $total_deposit = $deposit->jumlah_deposit - $request->bayar;
            $deposit->update(['jumlah_deposit' => $total_deposit]);
        }
        if($pembayaran = pembayaran::create($data)) {
            if($request->deposit != null) {
                $deposit = [
                    'id_pelanggan' => $request->nama_pelanggan,
                    'jumlah_deposit' => $request->deposit,
                    'tanggal' => date('Y-m-d'),
                    'deleted' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::user()->name,
                ];
                deposit::create($deposit);
            }
            $tagihan = tagihan::where('id', $request->id_tagihan)->first();
            $tagihan->update([
                'status_bayar' => 1,
                'sisa_tagihan' => $request->sisa,
                'updated_at' => date('Y-m-d H:i:s'), 
                'updated_by' => Auth::user()->name
            ]);
            tagihan::create([
                'id_pemasangan' => $tagihan->id_pemasangan,
                'tanggal_tagihan' => $tagihan->tanggal_tagihan,
                'status_bayar' => 0,
                'deleted' => 0,
                'tagihan' => $request->sisa,
                'sisa_tagihan' => 0,
                'tanggal_tagihan_terakhir' => $tagihan->tanggal_tagihan_terakhir,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::user()->name
            ]);
            pembayaran_detail::create(['id_pembayaran' => $pembayaran->id, 'id_tagihan' => $tagihan->id]);
            return [
                'success' => true,
                'message' => 'Data Berhasil di Tambahkan'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Data Gagal di Tambahkan'
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
