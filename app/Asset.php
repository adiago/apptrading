<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{

    const PETROLEO =12; 
    const E_MINI_PETROLEO =13; 
    const EURO_USD =14; 

    protected $table = 'assets';
    protected $guarded = ['id', 'created_at', 'updated_at'];
}
