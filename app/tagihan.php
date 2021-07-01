<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class tagihan extends Model
{
    protected $table = 'tagihan';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = true;

    public function pemasangan() {
        return $this->hasMany(pemasangan::class, 'id', 'id_pemasangan');
    }
    
    public static function getTotalTagihan() {
        return DB::table('tagihan')
            ->select('id_pemasangan', DB::raw('SUM(tagihan) as tagihan, SUM(sisa_tagihan) as sisa_tagihan'))   
            ->where('deleted', 0)
            ->where('status_bayar', 0)
            ->get();
    }
    
    public static function getTagihanTahun($year = null, $operator = '=') {    
        $query = DB::table('tagihan')
            ->select('id_pemasangan', DB::raw('SUM(sisa_tagihan) as sisa_tagihan'))   
            ->where('deleted', 0)         
            ->groupBy('id_pemasangan');
        if ($year) {
            $query->whereRaw('LEFT(tanggal_tagihan,4) '.$operator.' \''.$year.'\'');
        }            
        return $query->get();
    }    
    
    public static function getCollectiveRate() {
        return DB::select(DB::raw('SELECT AVG(rate) AS rate FROM (
            SELECT 
                LEFT(tanggal_tagihan, 7) AS periode, 
                SUM(tagihan) AS tagihan, 
                SUM(sisa_tagihan) AS sisa_tagihan,
                (SUM(tagihan) - SUM(sisa_tagihan))/SUM(tagihan)*100 AS rate
            FROM tagihan
            GROUP BY LEFT(tanggal_tagihan, 7)
        ) rate
        '));       
    }
}
