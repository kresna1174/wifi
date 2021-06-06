<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = true;

    public function pemasangan() {
        return $this->hasMany(pemasangan::class, 'id', 'id_pemasangan');
    }
}
