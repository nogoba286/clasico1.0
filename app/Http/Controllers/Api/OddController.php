<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Odd;
use App\Models\OddOption;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;

class OddController extends Controller
{
    public function index()
    {
        
    }

    public function updateOddOptions($apiUrl){
        if(!OddOption::all()->count()){
            $oddOptions = Http::withHeaders(['x-apisports-key' => env('API_KEY')])
                                    ->get("{$apiUrl}odds/bets")
                                    ->json()['response'] ?? [];
    
            foreach($oddOptions as $oddOption){
                $item["id"] = $oddOption["id"];
                $item["name"] = $oddOption["name"];
                OddOption::insert($item);
            }
        }
        return "success";
    }

    public function updateOdds($apiUrl){
        if(!Odd::count()){
            for($i = 0; $i < 7; $i++){
                $odds = Http::withHeaders(['x-apisports-key' => env('API_KEY')])
                                ->get("{$apiUrl}odds", ["date"=>now()->addDays($i)->format('Y-m-d')])
                                ->json()['response'] ?? [];
                foreach($odds as $odd){
                    foreach($odd["bookmakers"][0]["bets"] as $bet){
                        foreach($bet["values"] as $value){
                            $item["league_id"] = $odd["league"]["id"];
                            $item["event_id"] = $odd["fixture"]["id"];
                            $item["odd_option_id"] = $bet["id"];
                            $item["value"] = $value["value"];
                            $item["odd"] = $value["odd"] ?? null;
                            $item["created_at"] = now()->format("Y-m-d H:i:s");
                            Odd::create($item);
                        }
                    }
                }
            }
        }else{
            $updateDate = Carbon::parse($updateDate ?? now()->format('Y-m-d'));
            $latestDate = Odd::latest('created_at')->first()->created_at ?? now();
            if ($updateDate->greaterThan($latestDate)){
                $odds = Http::withHeaders(['x-apisports-key' => env('API_KEY')])
                                ->get("{$apiUrl}odds", ["date"=>now()->addDays(7)->format('Y-m-d')])
                                ->json()['response'] ?? [];
                foreach($odds as $odd){
                    foreach($odd["bookmakers"][0]["bets"] as $bet){
                        foreach($bet["values"] as $value){
                            $item["league_id"] = $odd["league"]["id"];
                            $item["event_id"] = $odd["fixture"]["id"];
                            $item["odd_option_id"] = $bet["id"];
                            $item["value"] = $value["value"];
                            $item["odd"] = $value["odd"] ?? null;
                            $item["created_at"] = now()->format("Y-m-d H:i:s");
                            Odd::create($item);
                        }
                    }
                }
            }
        }
    }

    public function fetchLiveOdds(){
        $response = Http::withHeaders(['x-apisports-key' => $this->apiKey])
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

    public function fetchOddById($event_id){
        $odds = Odd::where('event_id', $event_id)
                ->get()
                ->map(function($odd) {
                    return [
                        'odd_option_id' => $odd->odd_option_id,
                        'value' => $odd->value,
                        'odd' => $odd->odd
                    ];
                })
                ->toArray();
                
        return $odds;
    }
}
