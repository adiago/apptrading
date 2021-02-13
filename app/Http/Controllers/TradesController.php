<?php

namespace App\Http\Controllers;

use App\Trade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TradesController extends Controller
{
    public function __construct()
    {
    }
    public function index() {
        $userId = Auth::id();
        $data = Trade::where('user_id', $userId)->get();
        return response()->json($data);
    }

    public function show() {

    }

    public function store(Request $request) {
        $inputTrade = $request->all();
        $functions = new FunctionsController();
        $inputTrade['points'] = $functions->calculateTradePoints($inputTrade);
        Trade::create($inputTrade);
    }

    public function update(Request $request, $tradeId) {
        $inputTrade = $request->all();
        $functions = new FunctionsController();
        $inputTrade['points'] = $functions->calculateTradePoints($inputTrade);
        $trade = Trade::find($tradeId)->update(
            ['side_id' => $inputTrade['side_id'] ?? null,
            'strategy_id' => $inputTrade['strategy_id'] ?? null,
            'mood_id' => $inputTrade['mood_id'] ?? null,
            'asset_id' => $inputTrade['asset_id'] ?? null,
            'entry_price' => $inputTrade['entry_price'] ?? null,
            'exit_price' => $inputTrade['exit_price'] ?? null,
            'points' => $inputTrade['points'] ?? null,
            'trade_date' => $inputTrade['trade_date'] ?? null,
            ]
        );
    }

    public function getList(Request $request) {
        $userId = Auth::id();
        $data =$request->all();
        $trades = Trade::where('user_id', $userId)->take($data['qty'])->get();
        return response()->json($trades);

    }
}
