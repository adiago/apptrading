<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Broker extends Model
{
    protected $table = 'brokers';
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
