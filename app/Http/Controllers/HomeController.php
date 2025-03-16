<?php

namespace App\Http\Controllers;

use App\Events\OddsUpdated;
use App\Http\Controllers\Api\LeagueController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\OddController;
use App\Http\Controllers\Api\FavouriteController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use App\Models\SportsCategory;
use App\Models\Team;
use App\Models\BetName;
use App\Models\Bets;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    protected $apiUrl;
    protected $sports;
    protected $targetSport;
    protected $supportCompany = "Bet365";
    
    public function __construct()
    {
        $this->sports = SportsCategory::all()->toArray();
        $this->apiUrl = $this->sports[0]["api"]; 
        $this->targetSport = $this->sports[0];
    }
    public function goToHome(){
        return redirect()->route('home');
    }

    public function updateData(
        EventController $eventController,
        LeagueController $leagueController,
        OddController $oddController

    ){
        $eventController->updateEvents($this->apiUrl);
        $eventController->updateLiveEvents($this->apiUrl);
        $eventController->fetchLiveEvents($this->apiUrl);
    }

    public function index(LeagueController $leagueController, FavouriteController $favouriteController)
    {
        $this->fetchBets();
        $data = [
            'fetchTopLeagues'   => $leagueController->fetchTopLeagues($this->apiUrl),
            'liveAllEvents'     => $this->fetchLiveFixtures(),
            'upcomingAllEvents' => $this->fetchUpcomingFixtures(),
            'sportType'         => $this->fetchSportType(),
            'sports'            => $this->sports,
            'weekdays'          => $this->fetchWeekdays(),
            'favouriteBets'     => $favouriteController->getFavouriteBets()['favouriteBets']
        ];
        return view("layouts.sportbetting", compact('data'));
    }

    public function fetchOnlyLiveBets(){
        $bets = $this->fetchLiveFixtures();
        return response()->json(['bets' => $bets]);
    }
    public function fetchWeekdays()
    {
        return collect(range(0, 6))->map(function ($i) {
            $date = Carbon::today()->addDays($i);
            return [
                'day'  => $i === 0 ? 'Today' : $date->format('D'),
                'date' => $date->format('d'),
                'full-date' => $date->format('y-m-d')
            ];
        })->all();
    }

    public function combineByFixtureId($events, $odds)
    {
        $odds_indexed = collect($odds)->mapWithKeys(function ($odd) {
            return [$odd['fixture']['id'] => $odd];
        });

        return collect($events)->filter(function ($event) use ($odds_indexed) {
            return isset($event['fixture']['id']) && isset($odds_indexed[$event['fixture']['id']]);
        })->mapWithKeys(function ($event) use ($odds_indexed) {
            return [$event['fixture']['id'] => [
                'event' => $event,
                'odds'  => $odds_indexed[$event['fixture']['id']]
            ]];
        })->all();
    }
    
    public function fetchLiveFixtures()
    {
        $response = Http::withHeaders(['x-apisports-key' => env('API_KEY')])
                    ->get("{$this->apiUrl}fixtures", ['live' => 'all'])
                    ->json()['response'] ?? [];
        return array_slice($response, 0, 10);
    }

    public function fetchUpcomingFixtures()
    {
        $data = Http::withHeaders(['x-apisports-key' => env('API_KEY')])
                   ->get("{$this->apiUrl}fixtures", ['date' => now()->addDays(1)->format('Y-m-d')])
                   ->json()['response'] ?? [];
        return array_slice($data, 0, 10);
    }

    public function fetchUpcomingOdds()
    {
        return Http::withHeaders(['x-apisports-key' => env('API_KEY')])
                   ->get("{$this->apiUrl}odds", ['date' => now()->addDays(1)->format('Y-m-d')])
                   ->json()['response'] ?? [];
    }

    public function fetchAllDataByDate(Request $request){
        $date = (int) $request->date;
        $response = Http::withHeaders(['x-apisports-key' => env('API_KEY')])
                        ->get("{$this->apiUrl}fixtures", ['date' => now()->addDays($date)->format('Y-m-d')])
                        ->json()['response'] ?? [];
        return response()->json(['data'=>array_slice($response, 0, 15)]);
    }

    public function fetchSportType()
    {
        return SportsCategory::where('api', $this->apiUrl)->first();
    }

    public function fetchOdds()
    {
        $response = Http::withHeaders(['x-apisports-key' => env('API_KEY')])
                        ->get("{$this->apiUrl}odds/live")
                        ->json()['response'] ?? [];

        return collect($response)->map(function ($item) {
            $item['odds'] = collect($item['odds'])
                ->map(function ($odd) {
                    $odd['values'] = collect($odd['values'])
                        ->where('suspended', 1)
                        ->values()
                        ->all();
                    return !empty($odd['values']) ? $odd : null;
                })
                ->filter()
                ->values()
                ->all();

            return !empty($item['odds']) ? $item : null;
        })->filter()->values()->all();
    }

    public function fetchBets(){
        if (BetName::count() === 0) {
            $response = Http::withHeaders(['x-apisports-key' => env('API_KEY')])
                    ->get("{$this->apiUrl}odds/bets")
                    ->json()['response'] ?? [];
            BetName::insert($response);
        }
    }

    public function fetchOddById(Request $request){
        $fixtureId = $request->fixtureId;
        $odds = Http::withHeaders(['x-apisports-key' => env('API_KEY')])
                        ->get("{$this->apiUrl}odds", [ "fixture" => $fixtureId ])
                        ->json()['response'][0] ?? [];

        $fixture = Http::withHeaders(['x-apisports-key' => env('API_KEY')])
            ->get("{$this->apiUrl}fixtures", [ "id" => $fixtureId ])
            ->json()['response'][0] ?? [];
            
                        
        $data["fixture"] = $odds["fixture"];
        $data["league"] = $odds["league"];
        $data["teams"] = $fixture["teams"];
        $data["score"] = $fixture["score"];
        $data["goals"] = $fixture["goals"];
        $data["status"] = $fixture["fixture"]["status"];

        foreach($odds["bookmakers"] as $bookmaker){
            if($bookmaker["name"] == $this->supportCompany)
                $data["bookmakers"] = $bookmaker;
        }

        return response()->json(['data'=>$data]);
    }
    


    public function pushBetItem(Request $request){
        $userId = Auth::user()->id;
        $fixtureId = $request->fixtuerId;
        $id = $request->id;
        $name = $request->name;
        $odds = $request->odds;
        $homeTeam = $request->homeTeam;
        $awayTeam = $request->awayTeam;

        $results = Bets::where([
                ['user_id', '=', $userId],
                ['fixture_id', '=', $fixtureId],
                ['bet_value', '=', $id],
                ['bet_type', '=', $name]
            ])->get();

        if(!count($results)){
            Bets::insert([
                'user_id'=>$userId,
                'fixture_id'=>$fixtureId,
                'home_team'=>$homeTeam,
                'away_team'=>$awayTeam,
                'bet_value'=>$id,
                'bet_type'=>$name,
                'odds'=>$odds,
                'stake'=>10,
                'status'=>0
            ]);
        }else{
            Bets::where([
                ['user_id', '=', $userId],
                ['fixture_id', '=', $fixtureId],
                ['bet_value', '=', $id],
                ['bet_type', '=', $name]
            ])->delete();
        }
        $userBets = Bets::where([
            ['user_id', '=', $userId],
            ['status', '=', 0]
        ])->get();
        foreach($userBets as $bet){
            $bet->events = Http::withHeaders(['x-apisports-key' => env('API_KEY')])
                        ->get("{$this->apiUrl}fixtures", [ "id" => $bet->event_id ])
                        ->json()['response'][0] ?? [];
        }

        return response()->json([
            'message' => 'Bet placed successfully!',
            'bets' => $userBets
        ]);
    }
    public function placeBets(Request $request){
        $userId = Auth::user()->id;
        $bets = $request->bets;
        $balance = Auth::user()->balance;
        $totalAmount = 0;

        foreach($bets as $bet) {
            $totalAmount += floatval($bet['amount']);
        }

        if($totalAmount > $balance){
            return response()->json(['message' => 'Insufficient balance!']);
        }else{
            User::where('id', $userId)->update(['balance' => $balance - $totalAmount]);
            
                foreach($bets as $bet){
                    Bets::where([
                        ['user_id', '=', $userId],
                    ])->update([
                        'status' => 1,
                        'stake' => $bet['amount']
                    ]);
                }
        }
        return response()->json(['message' => 'Bets placed successfully!']);
    }

    public function deleteBetItem(Request $request){
        $betId = $request->betId;
        $userId = Auth::user()->id;
        $res = Bets::where([
                ['user_id', '=', $userId],
                ['id', '=', $betId]
            ])->get();

        if(count($res)){
            Bets::where([
                ['id', '=', $betId],
                ['user_id', '=', $userId]
            ])->delete();
        }

        $userBets = Bets::where([
            ['user_id', '=', $userId],
            ['status', '=', 0]
        ])->get();

        foreach($userBets as $bet){
            $bet->events = Http::withHeaders(['x-apisports-key' => env('API_KEY')])
                        ->get("{$this->apiUrl}fixtures", [ "id" => $bet->event_id ])
                        ->json()['response'][0] ?? [];
        }

        return response()->json([
            'message' => 'successfully',
            'bets' => $userBets
        ]);
    
    }
    public function fetchMyBets(){
        $userId = Auth::user()->id;
        $bets = Bets::where([
            ['user_id', '=', $userId],
            ['status', '=', 1]
        ])->get();
        return response()->json(['bets' => $bets]);
    }

}
