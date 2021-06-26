<?php

namespace App\Http\Controllers;

use App\Libraries\BulanIndo;
use Illuminate\Http\Request;
use DataTables;
use App\pelanggan;
use App\pemasangan;
use App\pembayaran;
use App\pembayaran_detail;
use App\tagihan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PelangganController extends Controller
{
    public function index() {
        return view('pelanggan.index', ['title' => 'Pelanggan']);
    }

    public function get() {
        return Datatables::of(pelanggan::orderBy('id', 'ASC')->where('deleted', 0)->get())
        ->make(true);
        return view('pelanggan.index');
    }

    public function create() {
        $pelanggan = 'NP-'.str_pad(pelanggan::max('id') + 1, 7, "0", STR_PAD_LEFT);
        return view('pelanggan.create', ['pelanggan' => $pelanggan]);
    }

    public function edit($id) {
        $model = pelanggan::findOrFail($id);
        return view('pelanggan.edit', ['model' => $model]);
    }

    public function view($id) {
        $pelanggan = pelanggan::with(['pemasangan' => function($data) use($id) {
            $data->with(['tagihan' => function($p) {
                $p->where('status_bayar', 0)->get();
            }])
                ->where('id_pelanggan', $id)
                ->get();
            }])->where('pelanggan.id', $id)->first();
            $data = [];
            foreach($pelanggan->pemasangan as $row) {
                foreach($row->tagihan as $p) {
                    if(isset($data[$row->no_pemasangan]['total'])) {
                        $data[$row->no_pemasangan]['total'] += $p->sisa_tagihan;
                    } else {
                        $data[$row->no_pemasangan]['total'] = $p->sisa_tagihan;
                    }
                } 
            }
        return view('pelanggan.view', ['pelanggan' => $pelanggan, 'data' => $data]);
    }

    public function detail(Request $request) {
        $pemasangan = pemasangan::with(['tagihan' => function($query) {
            return $query->where('status_bayar', 0);
        }])
            ->where('id_pelanggan', $request->id_pelanggan)
            ->where('id', $request->id_pemasangan)
            ->first();
        return view('pelanggan.detail', ['pemasangan' => $pemasangan]);
    }

    public function riwayat(Request $request) {
        $data = pembayaran_detail::join('pembayaran', 'pembayaran_detail.id_pembayaran', 'pembayaran.id')
            ->join('tagihan', 'pembayaran_detail.id_tagihan', 'tagihan.id')
            ->join('pemasangan', 'tagihan.id_pemasangan', 'pemasangan.id')
            ->where('pembayaran.id_pemasangan', $request->id_pemasangan)
            ->where('tagihan.id_pemasangan', $request->id_pemasangan)
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
            'no_pelanggan' => $request->no_pelanggan,
            'identitas' => $request->identitas,
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
            'no_pelanggan' => $request->no_pelanggan,
            'identitas' => $request->identitas,
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
