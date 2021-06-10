<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
}
