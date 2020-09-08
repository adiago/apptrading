<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    protected $table = 'markets';
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
