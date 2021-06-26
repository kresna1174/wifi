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
    
    public static function getTagihanTahun($year = null, $operator = '=') {    
        $query = DB::table('tagihan')
            ->select('id_pemasangan', DB::raw('SUM(sisa_tagihan) as sisa_tagihan'))            
            ->groupBy('id_pemasangan');
        if ($year) {
            $query->whereRaw('LEFT(tanggal_tagihan,4) '.$operator.' \''.$year.'\'');
        }            
        return $query->get();
    }
    
}
