<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    protected $table = 'trades';
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
