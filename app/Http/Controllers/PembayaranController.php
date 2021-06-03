<?php

namespace App\Http\Controllers;

use App\pelanggan;
use App\pemasangan;
use Illuminate\Http\Request;
use App\pembayaran;
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
        ->join('pelanggan', 'pemasangan.id_pelanggan', '=', 'pemasangan.id_pelanggan')
        ->get();
        return DataTables::of($pelanggan)
            ->make(true);
    }

    public function get_pembayaran(Request $request) {
        return DB::table('pembayaran')
        ->join('pemasangan', 'pembayaran.id_pemasangan', '=', 'pemasangan.id')
        ->join('pelanggan', 'pemasangan.id_pelanggan', '=', 'pemasangan.id_pelanggan')
        ->join('tagihan', 'pembayaran.id_tagihan', '=', 'tagihan.id')
        ->where('tagihan.status_bayar', 0)
        ->where('pemasangan.id_pelanggan', $request->id_pelanggan)
        ->select(['pemasangan.id_pelanggan', 'pemasangan.tarif', 'tagihan.tanggal_tagihan', 'pemasangan.alamat_pemasangan', 'pembayaran.bayar', 'tagihan.id'])
        ->groupBy(['pemasangan.id_pelanggan', 'pemasangan.tarif', 'tagihan.tanggal_tagihan', 'pemasangan.alamat_pemasangan', 'pembayaran.bayar', 'tagihan.id'])
        ->get();
    }

    public function create($id_pemasangan = null) {
        $pelanggan = pelanggan::pluck('nama_pelanggan', 'id');
        $pemasangan = pemasangan::with('tagihan')->get();
        foreach($pemasangan as $row) {
            foreach($row->tagihan as $p) {
                $pemasangan->p = $p->id;
            }
        }
        $pemasangan->pemasangan = $pemasangan->pluck('id', 'id');
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
            'id_tagihan' => $request->id_tagihan,
            'id_pelanggan' => $request->nama_pelanggan,
            'no_pemasangan' => $request->no_pemasangan,
            'alamat_pemasangan' => $request->alamat_pemasangan,
            'bayar' => $request->bayar,
            'deleted' => 0,
            'created_at' => date('Y-m-d'),
            'created_by' => Auth::user()->username,
        ];
        dd($data);
        if(pelanggan::create($data)) {
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
            'pelanggan' => 'required',
            'no_pemasangan' => 'required|numeric|max:12',
            'alamat_pemasangan' => 'required',
            'total_bayar' => 'required',
            'tunai' => 'required',
        ];
    }

    public function validation_message() {
        $messages = [];
        $messages['pelanggan.required'] = 'Nama Pelanggan Harus Di Isi';
        $messages['no_pemasangan.required'] = 'No Pemasangan Harus Di Isi';
        $messages['alamat_pemasangan.required'] = 'Alamat Pemasangan Harus Di Isi';
        $messages['total_bayar.required'] = 'Total Bayar Harus Di Isi';
        $messages['tunai.required'] = 'Tunai Harus Di Isi';
        return $messages;
    }
}
