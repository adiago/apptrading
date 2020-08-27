<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Side extends Model
{
    const BUY = 1;
    const SELL = 2;

    protected $table = 'sides';
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
