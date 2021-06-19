<?php

namespace App\Http\Controllers;

use App\Libraries\BulanIndo;
use App\pelanggan;
use App\pemasangan;
use App\tagihan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TagihanController extends Controller
{
    public function index(Request $request) {
        pemasangan::generate();
        if($request->periode != null) {
            $periode = $request->periode;
        } else {
            $periode = date('Y-m');
        }
        $pemasangan = pemasangan::with(['tagihan' => function($p) use($periode) {
            $p->join('pemasangan', 'tagihan.id_pemasangan', 'pemasangan.id')
            ->join('pelanggan', 'pemasangan.id_pelanggan', 'pelanggan.id')
            ->where(DB::raw('LEFT(tagihan.tanggal_tagihan ,7)'), $periode)
            ->where('status_bayar', 0)
            ->select('tagihan.id as tagihan_id', 'tagihan.id_pemasangan', 'pemasangan.alamat_pemasangan', 'pemasangan.tarif', 'tagihan.tanggal_tagihan', 'tagihan.tagihan', 'pelanggan.nama_pelanggan', 'pelanggan.no_pelanggan', 'tagihan.sisa_tagihan', 'pemasangan.no_pemasangan', 'pelanggan.id as pelanggan_id', 'pemasangan.tanggal_pemasangan')
            ->groupBy('tagihan.id_pemasangan')
            ->get();
        }])
            ->get();
            $tagihan = [];
            $total_bayar = [];
            foreach($pemasangan as $row) {
                $abc = pemasangan::with(['tagihan' => function($p) use($periode) {
                    $p->join('pemasangan', 'tagihan.id_pemasangan', 'pemasangan.id')
                    ->join('pelanggan', 'pemasangan.id_pelanggan', 'pelanggan.id')
                    ->where(DB::raw('LEFT(tagihan.tanggal_tagihan ,7)'), $periode)
                    ->where('status_bayar', 0)
                    ->select('tagihan.id as tagihan_id', 'tagihan.id_pemasangan', 'pemasangan.alamat_pemasangan', 'pemasangan.tarif', 'tagihan.tanggal_tagihan', 'tagihan.tagihan', 'pelanggan.nama_pelanggan', 'pelanggan.no_pelanggan', 'tagihan.sisa_tagihan', 'pemasangan.no_pemasangan', 'pelanggan.id as pelanggan_id', 'pemasangan.tanggal_pemasangan')
                    ->get();
                }])->where('id', $row->id)
                    ->get();
            $tanggal = $row->tanggal_tagihan;
            $t = date('t', strtotime($row->tanggal_pemasangan));
            if($tanggal == 32 || $tanggal > $t) {
                $tanggal = $t;
            }
            foreach($row->tagihan as $rs_tagihan) {
                $rs_tagihan->tanggal_tagihan = BulanIndo::tanggal_indo($rs_tagihan->tanggal_tagihan); 
                $rs_tagihan->tarif = number_format($rs_tagihan->tarif, 2, ',', '.'); 
                foreach($abc as $bca) {
                    foreach($bca->tagihan as $p) {
                        if(isset($total_bayar[$row->no_pemasangan]['total'])) {
                            $total_bayar[$row->no_pemasangan]['total'] += $p->sisa_tagihan;
                        } else {
                            $total_bayar[$row->no_pemasangan]['total'] = $p->sisa_tagihan;
                        }
                    }
                }
                $rs_tagihan->tagihan = number_format($rs_tagihan->tagihan, 2, ',', '.'); 
                $tagihan[] = $rs_tagihan;
            }
        }
        $periode = explode('-', $periode);
        return view('tagihan.index', ['title' => 'Generate Tagihan', 'tagihan' => $tagihan, 'total_bayar' => $total_bayar, 'periode' => $periode[1]]);
    }
}
