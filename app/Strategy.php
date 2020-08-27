<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Strategy extends Model
{
    protected $table = 'strategies';
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
