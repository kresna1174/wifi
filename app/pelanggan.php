<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pelanggan extends Model
{
    protected $table = 'pelanggan';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = true;

    public function deposit() {
        return $this->hasMany(deposit::class, 'id_pelanggan', 'id');
    }

    public function pembayaran() {
        return $this->hasManyThrough(pembayaran::class, pemasangan::class, 'id_pelanggan', 'id_pemasangan');
    }
}
