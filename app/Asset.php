<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $table = 'assets';
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
