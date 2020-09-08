<?php

namespace App\Http\Controllers;

use App\Strategy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use function Psy\sh;

class StrategiesController extends Controller
{
    public function index() {
        $data = Strategy::where('user_id', Auth::id())->orderBy('id', 'asc')->get();
        return response()->json($data);
    }

    public function store(Request $request) {
        Strategy::create($request->all());
    }

    public function update(Request $request, $id) {
        return Strategy::where('id',$id)->update($request->all());
    }
    
    public function destroy($id) {
        return Strategy::find($id)->delete();
    }
}
