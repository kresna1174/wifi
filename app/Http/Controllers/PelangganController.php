<?php

namespace App\Http\Controllers;

use App\Libraries\BulanIndo;
use Illuminate\Http\Request;
use DataTables;
use App\pelanggan;
use App\pemasangan;
use App\pembayaran;
use App\tagihan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PelangganController extends Controller
{
    public function index() {
        return view('pelanggan.index', ['title' => 'Pelanggan']);
    }

    public function get() {
        return Datatables::of(pelanggan::orderBy('id', 'ASC')->get())
        ->make(true);
        return view('pelanggan.index');
    }

    public function create() {
        return view('pelanggan.create');
    }

    public function edit($id) {
        $model = pelanggan::findOrFail($id);
        return view('pelanggan.edit', ['model' => $model]);
    }

    public function view($id) {
        $model = pelanggan::with(['pemasangan' => function($data) use($id) {
            $data->join('tagihan', 'pemasangan.id', '=', 'tagihan.id_pemasangan')
            ->join('pelanggan', 'pemasangan.id_pelanggan', 'pelanggan.id')
            ->where('id_pelanggan', $id)
            ->select('pemasangan.id', 'pemasangan.tarif', 'pelanggan.id as id_pelanggan', 'pemasangan.tanggal_akhir_bayar', 'pemasangan.alamat_pemasangan')
            ->groupBy('pemasangan.id', 'pemasangan.tarif', 'pelanggan.id', 'pemasangan.tanggal_akhir_bayar', 'pemasangan.alamat_pemasangan')
            ->get();
        }])->where('pelanggan.id', $id)->first();
        return view('pelanggan.view', ['model' => $model]);
    }

    public function detail(Request $request) {
        $model = pemasangan::with(['tagihan' => function($data) use($request) {
            $data->join('pemasangan', 'tagihan.id_pemasangan', '=', 'pemasangan.id')
                ->where('id_pemasangan', $request->id_pemasangan)->get();
        }])
            ->where('id_pelanggan', $request->id_pelanggan)
            ->where('id', $request->id_pemasangan)
            ->first();
        foreach($model->tagihan as $row) {
            $row->tanggal_tagihan = BulanIndo::tanggal_indo($row->tanggal_tagihan);
        }
        return view('pelanggan.detail', ['model' => $model]);
    }

    public function riwayat(Request $request) {
        $data = pemasangan::join('pembayaran', 'pemasangan.id', '=', 'pembayaran.id_pemasangan')
            ->leftjoin('deposit', 'pemasangan.id_pelanggan', '=', 'deposit.id_pelanggan')
            ->where('pemasangan.id', $request->id_pemasangan)
            ->where('pemasangan.id_pelanggan', $request->id_pelanggan)
            ->select('pembayaran.tanggal_bayar', 'pemasangan.tarif', 'pembayaran.bayar', 'deposit.jumlah_deposit')
            ->groupBy('pembayaran.tanggal_bayar', 'pemasangan.tarif', 'pembayaran.bayar', 'deposit.jumlah_deposit')
            ->get();
        foreach($data as $row) {
            $row->tanggal_bayar = BulanIndo::tanggal_indo($row->tanggal_bayar);   
        }
        return view('pelanggan.riwayat', ['data' => $data]);
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
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_telepon' => $request->no_telepon,
            'no_identitas' => $request->no_identitas,
            'alamat' => $request->alamat,
            'deleted' => 0,
            'created_at' => date('Y-m-d H:i:s'),
            'created_by' => Auth::user()->name
        ];
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

    public function update(Request $request, $id) {
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
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_telepon' => $request->no_telepon,
            'no_identitas' => $request->no_identitas,
            'alamat' => $request->alamat,
            'deleted' => 0,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => Auth::user()->name
        ];
        $pelanggan = pelanggan::findOrFail($id);
        if($pelanggan->update($data)) {
            return [
                'success' => true,
                'message' => 'Data Berhasil di Update'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Data Gagal di Update'
            ];
        }
    }

    public function delete($id) {
        $model = pelanggan::findOrFail($id);
        if($model) {
            if($model->delete()) {
                return [
                    'success' => true,
                    'message' => 'Data Berhasil Di Hapus'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Data Gagal Di Hapus'
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'Data Tidak Di Temukan'
            ];
        }
    }

    public function validation() {
        return [
            'nama_pelanggan' => 'required',
            'no_telepon' => 'required|numeric',
            'no_identitas' => 'required',
            'alamat' => 'required',
        ];
    }

    public function validation_message() {
        $messages = [];
        $messages['nama_pelanggan.required'] = 'Nama Pelanggan Harus Di Isi';
        $messages['no_telepon.required'] = 'No Telepon Harus Di Isi';
        $messages['no_telepon.numeric'] = 'No Telepon Harus Angka';
        $messages['no_identitas.required'] = 'No Identitas Harus Di Isi';
        $messages['alamat.required'] = 'Alamat Harus Di Isi';
        return $messages;
    }
}

