<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class deposit extends Model
{
    protected $table = 'deposit';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = true;
}
