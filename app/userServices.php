<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class userServices extends Model
{
    protected $table = 'users';
    protected $primary_key = 'id';
    protected $guarded = [];
    public $timestamps = true;
}
