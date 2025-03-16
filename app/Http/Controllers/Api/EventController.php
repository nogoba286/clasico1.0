<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Http\Controllers\Api\OddController;
use Illuminate\Support\Facades\Http;

class EventController extends Controller
{
    public $oddController;

    public function __construct(OddController $oddController){
        $this->oddController = $oddController;
    }
    public function index()
    {
        
    }
    public function updateEvents($apiUrl)
    {
        if(Event::count()){
            $updateDate = Carbon::parse($updateDate ?? now()->format('Y-m-d'));
            $latestDate = Event::latest('created_at')->first()->created_at ?? now();
            // Now compare Carbon instances
            if ($updateDate->greaterThan($latestDate)) 
            {
                $data = Http::withHeaders(['x-apisports-key' => env('API_KEY')])
                            ->get("{$apiUrl}fixtures", ['date' => $searchDate->addDays(7)->format('Y-m-d')])
                            ->json()['response'] ?? [];
                foreach($data as $element){
                    $item["event_id"] = $element["fixture"]["id"]; 
                    $item["timezone"] = $element["fixture"]["timezone"]; 
                    $item["date"] = Carbon::parse($element['fixture']['date'])->format('Y-m-d H:i:s'); 
                    $item["venue_name"] = $element["fixture"]["venue"]["name"]; 
                    $item["venue_city"] = $element["fixture"]["venue"]["city"]; 
                    $item["status_long"] = $element["fixture"]["status"]["long"];
                    $item["status_short"] = $element["fixture"]["status"]["short"];  
                    $item["status_elapsed"] = $element["fixture"]["status"]["elapsed"]; 
                    $item["status_extra"] = $element["fixture"]["status"]["extra"]; 
                    $item["league_id"] = $element["league"]["id"]; 
                    $item["home_goals"] = $element["goals"]["home"]; 
                    $item["home_id"] = $element["teams"]["home"]["id"]; 
                    $item["home_name"] = $element["teams"]["home"]["name"]; 
                    $item["home_logo"] = $element["teams"]["home"]["logo"]; 
                    $item["away_goals"] = $element["goals"]["away"]; 
                    $item["away_id"] = $element["teams"]["away"]["id"]; 
                    $item["away_name"] = $element["teams"]["away"]["name"]; 
                    $item["away_logo"] = $element["teams"]["away"]["logo"]; 
                    $item["created_at"] = Carbon::now();
                    Event::create($item);
                }
                return $data[0];
            }
        }else{
            $searchDate = $searchDate ?? now()->format('Y-m-d'); // Set default if null
            $data = Http::withHeaders(['x-apisports-key' => env('API_KEY')])
                        ->get("{$apiUrl}fixtures", ['date' => $searchDate])
                        ->json()['response'] ?? [];
            foreach($data as $element){
                $item["event_id"] = $element["fixture"]["id"]; 
                $item["timezone"] = $element["fixture"]["timezone"]; 
                $item["date"] = Carbon::parse($element['fixture']['date'])->format('Y-m-d H:i:s'); 
                $item["venue_name"] = $element["fixture"]["venue"]["name"]; 
                $item["venue_city"] = $element["fixture"]["venue"]["city"]; 
                $item["status_long"] = $element["fixture"]["status"]["long"];
                $item["status_short"] = $element["fixture"]["status"]["short"];  
                $item["status_elapsed"] = $element["fixture"]["status"]["elapsed"]; 
                $item["status_extra"] = $element["fixture"]["status"]["extra"]; 
                $item["league_id"] = $element["league"]["id"]; 
                $item["home_goals"] = $element["goals"]["home"]; 
                $item["home_id"] = $element["teams"]["home"]["id"]; 
                $item["home_name"] = $element["teams"]["home"]["name"]; 
                $item["home_logo"] = $element["teams"]["home"]["logo"]; 
                $item["away_goals"] = $element["goals"]["away"]; 
                $item["away_id"] = $element["teams"]["away"]["id"]; 
                $item["away_name"] = $element["teams"]["away"]["name"]; 
                $item["away_logo"] = $element["teams"]["away"]["logo"]; 
                $item["created_at"] = Carbon::now();
                Event::create($item);
            }
        }
    }

    public function updateLiveEvents($apiUrl){
        $allEvents = Event::all();
        $data = Http::withHeaders(['x-apisports-key' => env('API_KEY')])
                        ->get("{$apiUrl}fixtures", ['live' => "all"])
                        ->json()['response'] ?? [];
        foreach($data as $element){
            Event::where('id', $element["fixture"]["id"])->update([
                'status_long' => $element["fixture"]["status"]["long"],
                'status_short' => $element["fixture"]["status"]["short"],
                'status_elapsed' => $element["fixture"]["status"]["elapsed"],
                'status_extra' => $element["fixture"]["status"]["extra"]
            ]);
        }
        return "success";
    }

    public function fetchLiveEvents($apiUrl){
        $data = Http::withHeaders(['x-apisports-key' => env('API_KEY')])
                        ->get("{$apiUrl}fixtures", ['live' => "all"])
                        ->json()['response'] ?? [];
                        dd($data);
        foreach($data as $key => $element){
            $liveOdd = $this->oddController->fetchOddById($element["fixture"]["id"]);
            $data[$key]['odds'] = $liveOdd ?? [];
        }

        return response()->json($data);
    }

}
