<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Mood extends Model
{
    protected $table = 'moods';
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
