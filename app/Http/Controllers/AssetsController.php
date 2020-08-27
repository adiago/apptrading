<?php

namespace App\Http\Controllers;

use App\Asset;
use Illuminate\Http\Request;

class AssetsController extends Controller
{
    public function index() {
        $data = Asset::orderBy('id', 'asc')->get();
        return response()->json($data);
    }
}
