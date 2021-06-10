<?php

namespace App\Http\Controllers;

use App\Libraries\BulanIndo;
use App\pemasangan;
use App\tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TagihanController extends Controller
{
    public function index() {
        return view('tagihan.index', ['title' => 'Generate Tagihan']);
    }

    public function get_tagihan(Request $request) {
        $pemasangan = pemasangan::with(['tagihan' => function($p) use($request) {
            $p->join('pemasangan', 'tagihan.id_pemasangan', 'pemasangan.id')
            ->join('pelanggan', 'pemasangan.id_pelanggan', 'pelanggan.id')
            ->where(DB::raw('LEFT(tagihan.tanggal_tagihan_terakhir ,7)'), $request->periode)
            ->where('status_bayar', 0)
            ->select('tagihan.id as tagihan_id', 'tagihan.id_pemasangan', 'pemasangan.alamat_pemasangan', 'pemasangan.tarif', 'tagihan.tanggal_tagihan', 'tagihan.tagihan', 'pelanggan.nama_pelanggan', 'pelanggan.no_pelanggan', 'tagihan.sisa_tagihan', 'pemasangan.no_pemasangan', 'pelanggan.id as pelanggan_id')
            ->get();
        }])
            ->get();
        $tagihan = [];
        foreach($pemasangan as $row) {
            foreach($row->tagihan as $rs_tagihan) {
                $rs_tagihan->tanggal_tagihan = BulanIndo::tanggal_indo($rs_tagihan->tanggal_tagihan); 
                $rs_tagihan->tarif = number_format($rs_tagihan->tarif, 2, ',', '.'); 
                $rs_tagihan->sisa_tagihan = number_format($rs_tagihan->sisa_tagihan, 2, ',', '.'); 
                $tagihan[] = $rs_tagihan;
            }
        }
        return $tagihan;
    }
}
