<?php

namespace App\Http\Controllers;

use App\Side;
use Illuminate\Http\Request;

class SidesController extends Controller
{
    public function index() {
        $data = Side::orderBy('id', 'asc')->get();
        return response()->json($data);
    }
}
