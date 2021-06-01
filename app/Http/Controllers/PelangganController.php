<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DataTables;
use App\pelanggan;

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
}
