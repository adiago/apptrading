<?php

namespace App\Http\Controllers;

use App\Side;
use App\Trade;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StatisticsController extends Controller
{
    public function widgets()
    {
        $userId = Auth::id();
        $trades = Trade::where('user_id', $userId)->get();
        $countTrades = count($trades);
        $pointsTrades = 0;
        $winTrades = $lossTrades = $breakEven = 0;
        foreach ($trades as $trade) {
            $pointsTrades += $trade->points;
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
            'pointsTrades' => round($pointsTrades,2),
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
            'trades.exit_price', 'trades.points as points', 'trades.id as trade_id',
            'assets.name as asset_name', 'assets.id as asset_id',
            'markets.name as market_name', 'markets.id as market_id',
            'sides.name as side_name', 'sides.id as side_id',
             'strategies.name as strategy_name', 'strategies.id as strategy_id')
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

    public function chartPoints(Request $request) {
        $userId = Auth::id();
        $data =$request->all();

        $trades = Trade::selectRaw('id, SUM(points) as points, DATE_FORMAT(DATE(trade_date), "%d/%m/%y")  as date_trade')
            ->where('user_id', $userId)
            ->groupBy('date_trade')
            ->orderBy('points', 'DESC')
            ->pluck('points', 'date_trade');
            
        return response()->json($trades);
    }
    
    public function chartCumulativePoints(Request $request) {
        $userId = Auth::id();
        $data =$request->all();

        $trades = Trade::selectRaw('SUM(points) as points, DATE_FORMAT(DATE(trade_date), "%d/%m/%y")  as date_trade')
            ->where('user_id', $userId)
            ->groupBy('date_trade')
            ->orderBy('date_trade', 'ASC')
            ->pluck('points', 'date_trade');
                
            return response()->json($trades);
    }
    public function chartWinLoss(Request $request) {
        $userId = Auth::id();
        $data =$request->all();

        $win = Trade::where('points', '>', 0)
            ->where('user_id', $userId)
            ->get()->count();
        $loss = Trade::where('points', '<', 0)
            ->where('user_id', $userId)
            ->get()->count();        
        $breakEven = Trade::where('points', '=', 0)
        ->where('user_id', $userId)
        ->get()->count();

        return response()->json(['win' => $win, 'loss' => $loss, 'break' => $breakEven]);
    }

    public function chartLongShort() {
        $userId = Auth::id();

        $long = Trade::where('side_id', Side::BUY)
        ->where('user_id', $userId)->count();
        $short = Trade::where('side_id', Side::SELL)
        ->where('user_id', $userId)->count();

        return response()->json(['long' => $long, 'short' => $short]);
    
    }
    
}
