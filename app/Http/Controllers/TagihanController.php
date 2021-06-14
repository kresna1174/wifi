<?php

namespace App\Http\Controllers;

use App\Libraries\BulanIndo;
use App\pelanggan;
use App\pemasangan;
use App\tagihan;
use Dotenv\Regex\Success;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Calculation\Database\DMax;

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
            ->select('tagihan.id as tagihan_id', 'tagihan.id_pemasangan', 'pemasangan.alamat_pemasangan', 'pemasangan.tarif', 'tagihan.tanggal_tagihan', 'tagihan.tagihan', 'pelanggan.nama_pelanggan', 'pelanggan.no_pelanggan', 'tagihan.sisa_tagihan', 'pemasangan.no_pemasangan', 'pelanggan.id as pelanggan_id', 'pemasangan.tanggal_pemasangan')
            ->groupBy('tagihan.id_pemasangan')
            ->get();
        }])
            ->get();
        $tagihan = [];
        $total_bayar = [];
        foreach($pemasangan as $row) {
            $tanggal_pemasangan = explode('-', $row->tanggal_pemasangan);
            $t = date('t', strtotime($row->tanggal_pemasangan));
            foreach($row->tagihan as $rs_tagihan) {
                if($rs_tagihan->tanggal_tagihan == 32) {
                    $rs_tagihan->tanggal_tagihan = $t;
                }
                $rs_tagihan->tanggal_tagihan = BulanIndo::tanggal_indo($tanggal_pemasangan[0].'-'.$tanggal_pemasangan[1].'-'.$rs_tagihan->tanggal_tagihan); 
                $rs_tagihan->tarif = number_format($rs_tagihan->tarif, 2, ',', '.'); 
                if(isset($total_bayar[$row->no_pemasangan]['total'])) {
                    $total_bayar[$row->no_pemasangan]['total'] += $rs_tagihan->tagihan;
                } else {
                    $total_bayar[$row->no_pemasangan]['total'] = $rs_tagihan->tagihan;
                }
                $rs_tagihan->tagihan = number_format($rs_tagihan->tagihan, 2, ',', '.'); 
                $tagihan[] = $rs_tagihan;
            }
        }
        return $pemasangan;
    }

    public function get_total(Request $request) {
        $pemasangan = pemasangan::with(['tagihan' => function($p) use($request) {
            $p->join('pemasangan', 'tagihan.id_pemasangan', 'pemasangan.id')
            ->join('pelanggan', 'pemasangan.id_pelanggan', 'pelanggan.id')
            ->where(DB::raw('LEFT(tagihan.tanggal_tagihan_terakhir ,7)'), $request->periode)
            ->where('status_bayar', 0)
            ->select('tagihan.id as tagihan_id', 'tagihan.id_pemasangan', 'pemasangan.alamat_pemasangan', 'pemasangan.tarif', 'tagihan.tanggal_tagihan', 'tagihan.tagihan', 'pelanggan.nama_pelanggan', 'pelanggan.no_pelanggan', 'tagihan.sisa_tagihan', 'pemasangan.no_pemasangan', 'pelanggan.id as pelanggan_id', 'pemasangan.tanggal_pemasangan')
            ->get();
        }])
            ->get();
        $tagihan = [];
        $total_bayar = [];
        foreach($pemasangan as $row) {
            $tanggal_pemasangan = explode('-', $row->tanggal_pemasangan);
            $t = date('t', strtotime($row->tanggal_pemasangan));
            foreach($row->tagihan as $rs_tagihan) {
                if($rs_tagihan->tanggal_tagihan == 32) {
                    $rs_tagihan->tanggal_tagihan = $t;
                }
                $rs_tagihan->tanggal_tagihan = BulanIndo::tanggal_indo($tanggal_pemasangan[0].'-'.$tanggal_pemasangan[1].'-'.$rs_tagihan->tanggal_tagihan); 
                $rs_tagihan->tarif = number_format($rs_tagihan->tarif, 2, ',', '.'); 
                if(isset($total_bayar[$row->no_pemasangan]['total'])) {
                    $total_bayar[$row->no_pemasangan]['total'] += $rs_tagihan->tagihan;
                } else {
                    $total_bayar[$row->no_pemasangan]['total'] = $rs_tagihan->tagihan;
                }
                $rs_tagihan->tagihan = number_format($rs_tagihan->tagihan, 2, ',', '.'); 
                $tagihan[] = $rs_tagihan;
            }
        }
        return $total_bayar;
    }

    public function generate() {
        $pemasangan = pemasangan::with(['tagihan' => function($data) {
            $data->groupBy('tagihan.id_pemasangan')
            ->orderBy('tagihan.id', 'DESC')
            ->get();
        }])->get();
        $rs_tagihan = [];
        foreach($pemasangan as $row) {
            $id[] = $row->id;
            $tanggal_pemasangan = explode('-', $row->tanggal_pemasangan);
            foreach($row->tagihan as $p) {
                $tanggal = $p->tanggal_tagihan;
                $t = date('t', strtotime($row->tanggal_generate));
                if($tanggal == 32) {
                    $tanggal = $t;
                }
                if($row->tanggal_generate <= date('Y-m-d', strtotime(date('Y-m-d'). '- 1 month'))) {
                    $rs_tagihan[] = [
                        'id_pemasangan' => $row->id,
                        'tanggal_tagihan' => $tanggal,
                        'status_bayar' => 0,
                        'deleted' => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => Auth::user()->name,
                        'tagihan' => $p->tagihan,
                        'sisa_tagihan' => 0,
                        'tanggal_tagihan_terakhir' => date('Y-m-d', strtotime(date('Y-m-d', strtotime($tanggal_pemasangan[0].'-'.$tanggal_pemasangan[1].'-'.$tanggal)). '+1month'))
                    ];
                }
            }
        }
        if($tagihan = tagihan::insert($rs_tagihan)) {
            pemasangan::whereIn('id', $id)->update(['tanggal_generate' => date('Y-m-d')]);
            return [
                'success' => true,
                'message' => 'Tagihan Berhasil di Generate'
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Tagihan Gagal di Generate'
            ];
        }
    }
}
