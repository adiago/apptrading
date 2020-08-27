<?php

namespace App\Http\Controllers;

use App\Strategy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StrategiesController extends Controller
{
    public function index() {
        $data = Strategy::where('user_id', Auth::id())->orderBy('id', 'asc')->get();
        return response()->json($data);
    }
}
