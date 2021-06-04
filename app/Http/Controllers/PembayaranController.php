<?php

namespace App\Http\Controllers;

use App\deposit;
use App\pelanggan;
use App\pemasangan;
use Illuminate\Http\Request;
use App\pembayaran;
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
        $pelanggan = DB::table('pembayaran')
        ->join('pemasangan', 'pembayaran.id_pemasangan', '=', 'pemasangan.id')
        ->join('tagihan', 'pembayaran.id_tagihan', '=', 'tagihan.id')
        ->join('pelanggan', 'pemasangan.id_pelanggan', '=', 'pelanggan.id')
        ->select('pelanggan.nama_pelanggan', 'pembayaran.bayar', 'pembayaran.tanggal_bayar', 'pemasangan.tarif', 'pemasangan.alamat_pemasangan')
        ->groupBy('pelanggan.nama_pelanggan', 'pembayaran.bayar', 'pembayaran.tanggal_bayar', 'pemasangan.tarif', 'pemasangan.alamat_pemasangan')
        ->get();
        return DataTables::of($pelanggan)
            ->make(true);
    }

    public function get_pembayaran(Request $request) {
            $pemasangan = DB::table('pemasangan')
            ->leftjoin('pelanggan', 'pemasangan.id_pelanggan', '=', 'pemasangan.id_pelanggan')
            ->leftjoin('tagihan', 'pemasangan.id', '=', 'tagihan.id_pemasangan')
            ->where('tagihan.status_bayar', 0)
            ->where('pemasangan.id_pelanggan', $request->id_pelanggan)
            ->where('pemasangan.id', $request->id_pemasangan)
            ->select(['pemasangan.id_pelanggan', 'pemasangan.tarif', 'tagihan.tanggal_tagihan', 'pemasangan.alamat_pemasangan', 'tagihan.id as tagihan_id', 'pemasangan.id as pemasangan_id', 'pelanggan.id as pelanggan_id'])
            ->groupBy(['pemasangan.id_pelanggan', 'pemasangan.tarif', 'tagihan.tanggal_tagihan', 'pemasangan.alamat_pemasangan', 'tagihan.id', 'pemasangan.id', 'pelanggan.id'])
            ->get();
        return $pemasangan;
    }

    public function create() {
        $pelanggan = pelanggan::pluck('nama_pelanggan', 'id');
        $pemasangan =  DB::table('pemasangan')
        ->leftjoin('pelanggan', 'pemasangan.id_pelanggan', '=', 'pemasangan.id_pelanggan')
        ->leftjoin('tagihan', 'pemasangan.id', '=', 'tagihan.id_pemasangan')
        ->where('tagihan.status_bayar', 0)
        ->select(['pemasangan.id_pelanggan', 'pemasangan.tarif', 'tagihan.tanggal_tagihan', 'pemasangan.alamat_pemasangan', 'tagihan.id', 'pemasangan.id as pemasangan_id'])
        ->groupBy(['pemasangan.id_pelanggan', 'pemasangan.tarif', 'tagihan.tanggal_tagihan', 'pemasangan.alamat_pemasangan', 'tagihan.id', 'pemasangan.id'])
        ->get();
        foreach($pemasangan as $row) {
            $pemasangan->id_pelanggan = $row->id_pelanggan;
        }
        return view('pembayaran.create', ['pelanggan' => $pelanggan, 'pemasangan' => $pemasangan]);
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
        $data = [
            'id_pemasangan' => $request->no_pemasangan,
            'id_tagihan' => $request->id_tagihan,
            'bayar' => $request->bayar,
            'tanggal_bayar' => date('Y-m-d'),
            'deleted' => 0,
            'created_at' => date('Y-m-d'),
            'created_by' => Auth::user()->name,
        ];
        if(pembayaran::create($data)) {
            if($request->deposit != null) {
                $deposit = [
                    'id_pelanggan' => $request->nama_pelanggan,
                    'jumlah_deposit' => $request->deposit,
                    'tanggal' => date('Y-m-d'),
                    'deleted' => 0,
                    'created_at' => date('Y-m-d'),
                    'created_by' => Auth::user()->name,
                ];
                deposit::create($deposit);
            }
            $tagihan = tagihan::where('id', $request->id_tagihan)->first();
            $tagihan->update(['status_bayar' => 1, 'updated_at' => date('Y-m-d'), 'updated_by' => Auth::user()->name]);
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
            'no_pemasangan' => 'required|numeric|max:12',
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
