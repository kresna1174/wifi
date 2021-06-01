<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class tagihan extends Model
{
    protected $table = 'tagihan';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = true;
}
