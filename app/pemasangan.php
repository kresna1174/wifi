<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class pemasangan extends Model
{
    protected $table = 'pemasangan';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = true;
}
