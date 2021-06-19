<?php

namespace App\Http\Controllers;

use App\Libraries\BulanIndo;
use Illuminate\Http\Request;
use App\pemasangan;
use App\pelanggan;
use App\tagihan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class PemasanganController extends Controller
{
    public function index() {
        return view('pemasangan.index', ['title' => 'pemasangan']);
    }
    
    public function get() {
        $pelanggan = pemasangan::where('pemasangan.deleted', 0)->join('pelanggan', 'pemasangan.id_pelanggan', '=', 'pelanggan.id')
           ->select('pelanggan.nama_pelanggan', 'pemasangan.alamat_pemasangan', 'pemasangan.tarif', 'pemasangan.tanggal_pemasangan', 'pemasangan.id')
           ->get();
        foreach($pelanggan as $row) {
            $row->tanggal_pemasangan = BulanIndo::tanggal_indo($row->tanggal_pemasangan);
        }
        return DataTables::of($pelanggan)
           ->make(true); 
    } 

    public function get_pemasangan(Request $request) {
        if(isset($request->id_pelanggan)) {
            return pelanggan::where('deleted', 0)->findOrFail($request->id_pelanggan);
        } else {
            return pelanggan::where('deleted', 0)->get();
        }
    }

    public function create() {
        $pelanggan = pelanggan::where('deleted', 0)->with('pemasangan')->get();
        $no_pemasangan = 'NPM-'.str_pad(pemasangan::max('id') + 1, 7, "0", STR_PAD_LEFT);
        $pelanggan->no_pelanggan = 'NP-'.str_pad(pelanggan::max('id') + 1, 7, "0", STR_PAD_LEFT);
        $pelanggan->data = pelanggan::where('deleted', 0)->get();
        for($i = 1; $i <= 32; $i++) {
            $tanggal[$i] = $i;
        }
        $tanggal[32] = "tanggal akhir";

        return view('pemasangan.create', ['pelanggan' => $pelanggan, 'title' => 'Pemasangan', 'no_pemasangan' => $no_pemasangan, 'tanggal' => $tanggal]); 
    } 

    public function edit($id) {
        $pemasangan = pemasangan::with('pelanggan')->findOrFail($id);
        foreach($pemasangan->pelanggan as $pelanggan) {
            $rs_pemasangan = $pelanggan;
        }
        $pemasangan->nama_pelanggan = pelanggan::where('deleted', 0)->pluck('nama_pelanggan', 'id');
        for($i = 1; $i <= 32; $i++) {
            $tanggal[$i] = $i;
        }
        $tanggal[32] = "tanggal akhir";
        return view('pemasangan.edit', ['pemasangan' => $pemasangan, 'title' => 'Pemasangan', 'tanggal' => $tanggal, 'rs_pemasangan' => $rs_pemasangan]);
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
        if($request->pilih_pelanggan == 1) {
            $data = [
                'id_pelanggan' => $request->nama_pelanggan,
                'no_pemasangan' => $request->no_pemasangan,
                'alamat_pemasangan' => $request->alamat_pemasangan,
                'tarif' => $request->tarif,
                'tanggal_tagihan' => $request->tanggal_tagihan,
                'tanggal_pemasangan' => $request->tanggal_pemasangan,
                'tanggal_generate' => $request->tanggal_pemasangan,
                'deleted' => 0,
                'created_at'  => date('Y-m-d H:i:s'),
                'created_by' => Auth::user()->name,
            ];
            if($pemasangan = pemasangan::create($data)) {
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
        } else {
            $data = [
                'nama_pelanggan' => $request->nama_pelanggan,
                'no_telepon' => $request->no_telepon,
                'no_identitas' => $request->no_identitas,
                'identitas' => $request->identitas,
                'no_pelanggan' => $request->no_pelanggan,
                'alamat' => $request->alamat,
                'deleted' => 0,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => Auth::user()->name
            ];
            if($pelanggan = pelanggan::create($data)) {
                if($pemasangan = pemasangan::create([
                    'id_pelanggan' => $pelanggan->id, 
                    'no_pemasangan' => $request->no_pemasangan, 
                    'alamat_pemasangan' => $request->alamat_pemasangan,
                    'tarif' => $request->tarif,
                    'tanggal_tagihan' => $request->tanggal_tagihan,
                    'tanggal_generate' => $request->tanggal_pemasangan,
                    'tanggal_pemasangan' => $request->tanggal_pemasangan,
                    'deleted' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'created_by' => Auth::user()->name
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
                'id_pelanggan' => $request->nama_pelanggan,
                'alamat_pemasangan' => $request->alamat_pemasangan,
                'no_pemasangan' => $request->no_pemasangan,
                'tarif' => $request->tarif,
                'tanggal_tagihan' => $request->tanggal_tagihan,
                'tanggal_pemasangan' => $request->tanggal_pemasangan,
                'tanggal_generate' => $request->tanggal_pemasangan,
                'deleted' => 0,
                'updated_at'  => date('Y-m-d H:i:s'),
                'updated_by' => Auth::user()->name,
            ];
            $pemasangan = pemasangan::find($id);
            if($pemasangan->update($data)) {
                return [
                    'success' => true,
                    'message' => 'Data Berhasil di Perbarui'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Data Gagal di Perbarui'
                ];
            }
    } 

     public function delete($id) {
         $model = pemasangan::findOrFail($id);
         if($model) {
             if($model->update(['deleted' => 1])) {
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
            'no_identitas' => 'required|numeric',
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
        $messages['no_identitas.required'] = 'No Identitas Harus Di Isi';
        $messages['no_identitas.numeric'] = 'No Identitas Harus Angka';
        $messages['alamat.required'] =  'Alamat Pelanggan Harus Di Isi';
        $messages['alamat_pemasangan.required'] = 'Alamat Pemasangan Harus Di Isi';
        $messages['tarif.required'] = 'Tarif Harus Di Isi';
        $messages['tarif.numeric'] = 'Tarif Harus Angka';
        $messages['tanggal_pemasangan.required'] =  'Tanggal Pemasangan Harus Di Isi';
        return $messages;
    } 
}

