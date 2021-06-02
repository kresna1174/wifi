<?php

namespace App\Http\Controllers;

use App\pelanggan;
use App\pemasangan;
use Illuminate\Http\Request;
use App\pembayaran;
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

    public function create() {
        return view('pembayaran.create');
    }
}
