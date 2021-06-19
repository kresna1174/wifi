<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Libraries\Arr;
use DateTime;
use Illuminate\Support\Facades\Date;

class pemasangan extends Model
{
    protected $table = 'pemasangan';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = true;

    public function pembayaran() {
        return $this->hasMany(pembayaran::class, 'id_pemasangan', 'id');
    }

    public function tagihan() {
        return $this->hasMany(tagihan::class, 'id_pemasangan', 'id');
    }

    public function pelanggan() {
        return $this->hasMany(pelanggan::class, 'id', 'id_pelanggan');
    }

    public function ManyPembayaran() {
        return $this->belongsToMany(pembayaran::class, 'pembayaran_detail', 'id_tagihan', 'id');
    }

    public static function generate() {
        $now = date('Y-m-d');
        $pemasangan = pemasangan::with(['tagihan' => function($data) {
            $data->groupBy('tagihan.id_pemasangan')
            ->orderBy('tagihan.tanggal_tagihan', 'ASC')
            ->get();
        }])->where('tanggal_generate', '<', date('Y-m-d'))
        ->get();
        $rs_tagihan = [];
        $id = [];
        $now = date('Y-m-d');
        foreach($pemasangan as $row) {
        $lastGenerate = $row->tanggal_generate;
        $exp_lastGenerate = explode('-', $lastGenerate);
        $start = new DateTime($row->tanggal_generate);
        $end = new DateTime($now);
        $interval =  $start->diff($end);
        $tanggal_pemasangan = $row->tanggal_pemasangan;
        $exp_tanggal_pemasangan = explode('-', $lastGenerate);
        $t = date('t', strtotime($row->tanggal_pemasangan));
        if($row->tanggal_tagihan  == 32 || $row->tanggal_tagihan > $t) {
            $row->tanggal_tagihan = $t;
        }
        $tanggal_tagihan = $exp_tanggal_pemasangan[0].'-'.$exp_tanggal_pemasangan[1].'-'.$row->tanggal_tagihan;
        $tanggal_tagihan = date('Y-m-d', strtotime('+ 1 month', strtotime($tanggal_tagihan)));
        $jumlah_hari = date('t', strtotime($tanggal_pemasangan))/2;
        $tanggal_pemasangan = new DateTime($tanggal_pemasangan);
        $tanggal_tagihan = new DateTime($tanggal_tagihan);
        $tagihan_next = $tanggal_tagihan->diff($tanggal_pemasangan);
        $lastTagihan = $exp_tanggal_pemasangan[0].'-'.$exp_tanggal_pemasangan[1].'-'.$row->tanggal_tagihan;
        $id[] = $row->id;
        $tagihan = Arr::map($row->tagihan, 'tanggal_tagihan', 'tanggal_tagihan');
        for ($i = 1; $i<=$interval->m; $i++) {
                $tanggal = $row->tanggal_tagihan;
                $nextTagihan = date('Y-m-d', strtotime('+1 month', strtotime($lastGenerate)));                
                $t = date('t', strtotime($row->tanggal_generate));
                if($tanggal == 32 || $tanggal > $t) {
                    $tanggal = $t;
                }
                if($jumlah_hari <= $tagihan_next->days) {
                    $newTagihan = date('Y-m-d', strtotime('+ 15 days', strtotime($lastTagihan)));
                } else {
                    $newTagihan = date('Y-m-d', strtotime('+ 1 month', strtotime($lastTagihan)));
                }
                $tanggal = $row->tanggal_tagihan;
                $t = date('t', strtotime($newTagihan));
                if($tanggal == 32 || $tanggal > $t) {
                    $tanggal = $t;
                }
                $lastGenerate = date('Y-m-'.$tanggal, strtotime($nextTagihan));
                $lastTagihan = date('Y-m-'.$tanggal, strtotime($newTagihan));
                if (!isset($tagihan[$lastGenerate])) {
                    if (strtotime($lastTagihan) <= strtotime($now))
                    $rs_tagihan[] = [
                        'id_pemasangan' => $row->id,
                        'tanggal_tagihan' => $lastTagihan,
                        'status_bayar' => 0,
                        'deleted' => 0,
                        'created_at' => date('Y-m-d H:i:s'),
                        'created_by' => \Auth::user()->name,
                        'tagihan' => $row->tarif,
                        'sisa_tagihan' => $row->tarif
                    ];
                }
            }
        }
        if (count($rs_tagihan)) {
            tagihan::insert($rs_tagihan);
            pemasangan::whereIn('id', $id)->update(['tanggal_generate' => $now]);
        }
    }
}
