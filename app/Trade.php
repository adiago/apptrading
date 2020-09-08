<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Trade extends Model
{
    protected $table = 'trades';
    protected $guarded = ['id', 'createdat', 'updated_at'];
}
