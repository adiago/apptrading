<?php

namespace App\Http\Controllers;

use App\Market;
use Illuminate\Http\Request;

class MarketsController extends Controller
{
    public function index() {
        $data = Market::orderBy('id', 'asc')->get();
        return response()->json($data);
    }
}
