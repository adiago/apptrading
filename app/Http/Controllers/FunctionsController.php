<?php

namespace App\Http\Controllers;

use App\Asset;
use App\Side;
use Illuminate\Http\Request;

class FunctionsController extends Controller
{
    public function calculateTradePoints($trade) {
        $points = 0;
        $asset = Asset::find($trade['asset_id']);

        if(!is_object($asset) || is_null($asset)) return 0;
        if(is_null($asset->tick)) return 0;

        if($trade['side_id'] == Side::BUY) {
            $diff = $trade['exit_price'] - $trade['entry_price'];
        } else if($trade['side_id'] == Side::SELL) {
            $diff = $trade['entry_price'] - $trade['exit_price'];
        }

        $points = round($diff / $asset->tick, 4);

        return $points;
    }
}
