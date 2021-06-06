<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\deposit;
use App\Libraries\BulanIndo;
use App\pelanggan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class DepositController extends Controller
{
    public function index() {
        $title = 'Master Deposit';
        return view('deposit.index', ['title' => $title, 'title' => 'Deposit']);
    }

    public function get() {
        $pelanggan = deposit::join('pelanggan', 'deposit.id_pelanggan', '=', 'pelanggan.id')
            ->get();

        foreach($pelanggan as $row) {
            $row->tanggal = BulanIndo::tanggal_indo($row->tanggal);
        }
        return DataTables::of($pelanggan)
            ->make(true);
    }

    public function create() {
        $pelanggan = pelanggan::pluck('nama_pelanggan', 'id');
        return view('deposit.create', ['pelanggan' => $pelanggan]);
    }

    public function edit($id) {
        $model = deposit::where('id_pelanggan', $id)->first();
        $pelanggan = pelanggan::pluck('nama_pelanggan', 'id');
        return view('deposit.edit', ['model' => $model, 'pelanggan' => $pelanggan]);
    }

    public function view($id) {
        $model = pelanggan::with('deposit')->where('pelanggan.id', $id)->first();
        $data = [];
        foreach($model->deposit as $key => $row) {
            $data = [$row];
            $row->tanggal = BulanIndo::tanggal_indo($row->tanggal);
        }
        return view('deposit.view', ['model' => $model, 'data' => $data]);
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
            'id_pelanggan' => $request->nama_pelanggan,
            'jumlah_deposit' => $request->jumlah_deposit,
            'deleted' => 0,
            'tanggal' => date('Y-m-d'),
        ];
        if(deposit::create($data)) {
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
            'id_pelanggan' => $request->nama_pelanggan,
            'jumlah_deposit' => $request->jumlah_deposit,
            'deleted' => 0,
            'tanggal' => date('Y-m-d'),
        ];
        $pelanggan = deposit::whereid_pelanggan($id)->first();
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
        $model = deposit::findOrFail($id);
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
            'jumlah_deposit' => 'required|numeric',
        ];
    }

    public function validation_message() {
        $messages = [];
        $messages['nama_pelanggan.required'] = 'Nama Pelanggan Harus Di Isi';
        $messages['jumlah_deposit.required'] = 'Deposit Harus Di Isi';
        $messages['jumlah_deposit.numeric'] = 'Deposit Harus Angka';
        return $messages;
    }
}
