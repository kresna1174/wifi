<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\pemasangan;
use App\pelanggan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PemasanganController extends Controller
{
    public function index() {
        $title = "Pemasangan";
        return view('pemasangan.index', ['title' => $title]);
    }
    
    public function get() {
        $pelanggan = DB::table('pemasangan')
           ->join('pelanggan', 'pemasangan.id_pelanggan', '=', 'pelanggan.id')
           ->get();
        return DataTables::of($pelanggan)
           ->make(true); 
    } 

    public function get_pemasangan(Request $request) {
        return pelanggan::with('pemasangan')->where('pelanggan.id', $request->id_pelanggan)->first();
    } 

    public function create() {
        $pelanggan = pelanggan::with('pemasangan')->get();
        return view('pemasangan.create', ['pelanggan' => $pelanggan]); 
    } 

    public function edit($id) {
        $pelanggan = pelanggan::with('pemasangan')->where('pelanggan.id', $id)->first();
        foreach($pelanggan->pemasangan as $key => $row) {
            $pelanggan->tarif = $row->tarif;
            $pelanggan->alamat_pemasangan = $row->alamat_pemasangan;
            $pelanggan->tanggal_pemasangan = $row->tanggal_pemasangan;
        }
        return view('pemasangan.edit', ['pelanggan' => $pelanggan]);
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
        if($request->pilih_pelanggan == '1') {
            $data = [
                'nama_pelanggan' => $request->nama_pelanggan,
                'no_telepon' => $request->no_telepon,
                'no_ktp' => $request->no_ktp,
                'alamat' => $request->alamat,
                'created_at'  => date('Y-m-d'),
                'created_by' => Auth::user()->username,
            ];
        }
        if($pelanggan = pelanggan::create($data)) {
            if(pemasangan::create([
                'id_pelanggan' => $pelanggan->id, 
                'alamat_pemasangan' => $request->alamat_pemasangan,
                'tarif' => $request->tarif,
                'tanggal_pemasangan' => $request->tanggal_pemasangan,
                'deleted' => 0
            ])) {
                return [
                    'success' => true,
                    'message' => 'Data Berhasil di Tambahkan'
                ];
            }
        } else {
            return [
                'success' => false,
                'message' => 'Data Gagal di Tambahkan'
            ];
        }
    } 

     public function update(Request $request, $id) {
         $rules = self::validation($request->all());
         $messages = self::validation_messages($request->all());
         $validator = Validator::make($request->all(), $rules, $messages);
         if($validator->fails()) {
             return response()->json([
                 'message' => 'Terjadi Kesalahan Input',
                 'errors' => $validator->messages()
             ], 400);
         }
         $data = [
            'id_pelanggan' => $request->nama_pelanggan,
            'no_telepon' => $request->no_telepon,
            'no_ktp' => $request->no_ktp,
            'alamat' => $request->alamat,
            'alamat_pemasangan' => $request->alamat_pemasangan,
            'alamat_pemasangan'=> $request->alamat_pemasangan,
            'tarif' => $request->tarif,
            'tanggal_pemasangan' => $request->tanggal_pemasangan,
            'deleted' => 0, 
            'updated_at'  => date('Y-m-d'),
            'updated_by' => Auth::user()->username, 
         ];
         $pelanggan = pemasangan::whereid_pelanggan($id)->first();
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
         $model = pemasangan::findOrFail($id);
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
                'message' => 'Data Tidak di Temukan'
            ];
        }
     } 

    public function validation()  {
        return [ 
            'nama_pelanggan' => 'required',
            'no_telepon' => 'required|numeric',
            'no_ktp' => 'required|numeric',
            'alamat' => 'required',
            'alamat_pemasangan' => 'required', 
            'tarif' => 'required|numeric',
            'tanggal_pemasangan' => 'required',
        ];
    }
    
    public function validation_message() {
        $messages = [];
        $messages['nama_pelanggan.required'] = 'Nama Pelanggan Harus Di Isi';
        $messages['no_telepon.required'] = 'No Telepon Harus Di Isi';
        $messages['no_telepon.numeric'] = 'No Telepon Harus Angka';
        $messages['no_ktp.required'] = 'No Identitas Harus Di Isi';
        $messages['no_ktp.numeric'] = 'No Identitas Harus Angka';
        $messages['alamat.required'] =  'Alamat Pelanggan Harus Di Isi';
        $messages['alamat_pemasangan.required'] = 'Alamat Pemasangan Harus Di Isi';
        $messages['tarif.required'] = 'Tarif Harus Di Isi';
        $messages['tarif.numeric'] = 'Tarif Harus Angka';
        $messages['tanggal_pemasangan.required'] =  'Tanggal Pemasangan Harus Di Isi';
        return $messages;
    } 
}

