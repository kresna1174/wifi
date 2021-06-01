<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pembayaran extends Model
{
    protected $table = 'pembayaran';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = true;
}
