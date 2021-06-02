<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\pemasangan;
use App\pelanggan;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PemasanganController extends Controller
{
    public function index() {
        $title = "Pemasangan";
        return view('pemasangan.index', ['title' => $title]);
    }
    
    public function get() {
        $pemasangan = DB::table('pemasangan')
           ->('pelanggan', 'pemasangan.id_pelanggan', '=', 'pelanggan.id')
           ->get();
        return DataTables::of($pemasangan)
           ->make(true); 
    } 

    public function create() {
        $pelanggan = pelanggan::pluck('nama_pelanggan', 'id');
        return view('pemasangan.create', ['pelanggan' => $pelanggan]); 
    } 

    public function edit($id) {
        $model = pemasangan::where('id_pelanggan', $id)->first();
        $pelanggan = pelanggan::pluck('nama_pelanggan', 'id');
        return view('pemasangan.edit', ['model' => $model, 'pelanggan' => $pelanggan]);
    } 

    public function store(Request $request) {
        $rules = self::validation($request->all());
        $messages = self::validation_message($request->all());
        $validator = Validator::make($request->all(), $rules, $messages);
        if($validator->fails()) {
            return return response()->json([
                'message' => 'Terjadi Kesalahan Input',
                'errors' => $validator->messages()
            ], 400);
        }
        $data = [
            'nama_pelanggan' => $request->nama_pelanggan,
            'alamat_pemasangan' => $request->alamat_pemasangan,
            'tarif' => $request->tarif,
            'tanggal_pemasangan' => $request->tanggal_pemasangan,
            'deleted' => 0,
        ];
        if(pemasangan::create($data)) {
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

    public function update(Request) 
}

