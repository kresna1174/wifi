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
            ->get();
        }])
            ->get();
        $tagihan = [];
        foreach($pemasangan as $row) {
            $tanggal_pemasangan = explode('-', $row->tanggal_pemasangan);
            foreach($row->tagihan as $rs_tagihan) {
                $rs_tagihan->tanggal_tagihan = BulanIndo::tanggal_indo($tanggal_pemasangan[0].'-'.$tanggal_pemasangan[1].'-'.$rs_tagihan->tanggal_tagihan); 
                $rs_tagihan->tarif = number_format($rs_tagihan->tarif, 2, ',', '.'); 
                $rs_tagihan->tagihan = number_format($rs_tagihan->tagihan, 2, ',', '.'); 
                $tagihan[] = $rs_tagihan;
            }
        }
        return $tagihan;
    }

    public function generate() {
        $pemasangan = pemasangan::get();
        foreach($pemasangan as $row) {
            $data[] = $row;
        }
        foreach($data as $row) {
            $rs_tagihan = tagihan::whereIn('tagihan.id_pemasangan', [$row->id])->get();
            $tanggal_pemasangan = explode('-', $row->tanggal_pemasangan);
            foreach($rs_tagihan as $p) {
                $tanggal = $row->tanggal_generate;
                if($p->tanggal_tagihan == 32) {
                    $tanggal = date('t', strtotime($row->tanggal_generate));
                }
                if($row->tanggal_generate <= date('Y-m-d', strtotime(date('Y-m-d'). '- 1 month'))) {
                    $pemasangan = pemasangan::findOrFail($row->id)->update(['tanggal_generate' => date('Y-m-d')]);
                    $tagihan = [
                        'id_pemasangan' => $row->id,
                        'tanggal_tagihan' => $p->tanggal_tagihan,
                        'status_bayar' => 0,
                        'deleted' => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => Auth::user()->name,
                        'tagihan' => $p->tagihan,
                        'sisa_tagihan' => 0,
                        'tanggal_tagihan_terakhir' => date('Y-m-d', strtotime(date('Y-m-d', strtotime($tanggal_pemasangan[0].'-'.$tanggal_pemasangan[1].'-'.$tanggal)). '+1month'))
                    ];
                    tagihan::create($tagihan);
                    return [
                        'success' => true,
                        'message' => 'Data Berhasil di Generate',
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Data Gagal di Generate',
                    ];
                }
            }
        }
    }
}
