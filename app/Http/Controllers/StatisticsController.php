<?php

namespace App\Http\Controllers;

use App\Side;
use App\Trade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatisticsController extends Controller
{
    public function widgets()
    {
        $userId = Auth::id();
        $trades = Trade::where('user_id', $userId)->get();
        $countTrades = count($trades);
        $winTrades = $lossTrades = $breakEven = 0;
        foreach ($trades as $trade) {
            if($trade->side_id == Side::SELL) {
                if($trade->entry_price > $trade->exit_price) $winTrades++;
                else if($trade->entry_price < $trade->exit_price) $lossTrades++;
                else $breakEven++;
            } elseif($trade->side_id == Side::BUY) {
                if($trade->entry_price < $trade->exit_price) $winTrades++;
                else if($trade->entry_price > $trade->exit_price) $lossTrades++;
                else $breakEven++;
            } else {
                continue;
            }
        }

        $data = [
            'countTrades' => $countTrades,
            'winTrades' => $winTrades,
            'lossTrades' => $lossTrades,
            'breakEven' => $breakEven
        ];


        return response()->json($data);
    }

    public function datatable(Request $request) {
        $userId = Auth::id();
        $data =$request->all();

        $trades = Trade::select('trades.trade_date as date', 'trades.side_id', 'trades.entry_price',
        'trades.exit_price', 'trades.points as points', 'markets.name as market_name', 'assets.name as asset_name',
            'sides.name as side_name', 'strategies.name as strategy_name')
            ->leftJoin('assets', 'assets.id', '=', 'trades.asset_id')
            ->leftJoin('markets', 'markets.id', '=', 'assets.market_id')
            ->leftJoin('strategies', 'strategies.id', '=', 'trades.strategy_id')
            ->leftJoin('sides', 'sides.id', '=', 'trades.side_id')
            ->where('trades.user_id', $userId)
            ->orderBy('trades.id', 'DESC')
            ->take($data['qty'])
            ->get();
        return response()->json($trades);
    }
}
