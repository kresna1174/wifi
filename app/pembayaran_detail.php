<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pembayaran_detail extends Model
{
    protected $table = 'pembayaran_detail';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = false;
}
