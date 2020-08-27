<?php

namespace App\Http\Controllers;

use App\Trade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TradesController extends Controller
{
    public function index() {
        $userId = Auth::id();
        $data = Trade::where('user_id', $userId)->get();
        return response()->json($data);
    }

    public function show() {

    }

    public function store(Request $request) {
        Trade::create($request->all());
    }

    public function getList(Request $request) {
        $userId = Auth::id();
        $data =$request->all();
        $trades = Trade::where('user_id', $userId)->take($data['qty'])->get();
        return response()->json($trades);

    }
}
