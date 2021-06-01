<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pelanggan extends Model
{
    protected $table = 'pelanggan';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = true;
}
