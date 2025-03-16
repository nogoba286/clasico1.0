<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FavouriteBet;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class FavouriteController extends Controller
{

    public function __construct(){

    }
    public function index()
    {
        
    }
    public function addFavorite(Request $request){
        $res = FavouriteBet::where('event_id', $request->fixtureId)->where('user_id', Auth::user()->id)->first();
        if($res){
            FavouriteBet::where('event_id', $request->fixtureId)->where('user_id', Auth::user()->id)->delete();
        }else{

            $favouriteBet = new FavouriteBet();
            $favouriteBet->event_id = $request->fixtureId;
            $favouriteBet->user_id = Auth::user()->id;
            $favouriteBet->home_team = $request->homeTeam;
            $favouriteBet->away_team = $request->awayTeam;
            $favouriteBet->date = Carbon::parse($request->date)->format('Y-m-d');
            $favouriteBet->save();
        }
        $favouriteBets = FavouriteBet::where('user_id', Auth::user()->id)->get();
        return response()->json(['message' => 'Favourite bet added successfully', 'favouriteBets' => $favouriteBets]);
    }
    public function getFavouriteBets(){
        $favouriteBets = Auth::check() ? FavouriteBet::where('user_id', Auth::user()->id)->get() : collect([]);
        return ['favouriteBets' => $favouriteBets];
    }

}
