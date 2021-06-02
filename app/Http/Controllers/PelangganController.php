<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\pelanggan;
use Illuminate\Support\Facades\Validator;

class PelangganController extends Controller
{
    public function index() {
        return view('pelanggan.index');
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
        $model = pelanggan::findOrFail($id);
        return view('pelanggan.view', ['model' => $model]);
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
            'no_ktp' => $request->no_ktp,
            'alamat' => $request->alamat,
            'deleted' => 0,
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
                'errors' => $validator->messages
            ], 400);
        }
        $data = [
            'nama_pelanggan' => $request->nama_pelanggan,
            'no_telepon' => $request->no_telepon,
            'no_ktp' => $request->no_ktp,
            'alamat' => $request->alamat,
            'deleted' => 0,
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
            'no_telepon' => 'required|numeric|max:12',
            'no_ktp' => 'required',
            'alamat' => 'required',
        ];
    }

    public function validation_message() {
        $messages = [];
        $messages['nama_pelanggan.required'] = 'Nama Pelanggan Harus Di Isi';
        $messages['no_telepon.required'] = 'No Telepon Harus Di Isi';
        $messages['no_telepon.numeric|max:12'] = 'No Telepon Harus Angka';
        $messages['no_ktp.required'] = 'No Identitas Harus Di Isi';
        $messages['alamat.required'] = 'Alamat Harus Di Isi';
        return $messages;
    }
}

