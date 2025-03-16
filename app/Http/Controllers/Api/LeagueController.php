<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\League;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;

class LeagueController extends Controller
{
    public function index()
    {
        
    }

    public function updateLeagues($sports, $apiUrl){
        if(!League::all()->count()){
            foreach($sports as $sport){
                $response = Http::withHeaders(['x-apisports-key' => env('API_KEY')])
                                ->get("{$sport["api"]}leagues")
                                ->json()['response'] ?? [];
                foreach ($response as $league) {
                    try {
                        $item = [
                            "league_id" => $league["league"]["id"] ?? null,
                            "sport_id" => $sport["id"] ?? null,  // Ensure sport_id is set or default to null
                            "name" => $league["league"]["name"] ?? null,
                            "logo" => $league["league"]["logo"] ?? null,
                            "country" => $league["country"]["name"] ?? null,
                            "flag" => $league["country"]["flag"] ?? null, // Handle missing flag
                            "created_at" => Carbon::now()
                        ];
                        League::create($item);
                    } catch (Exception $e) {
                        // Log the error for debugging
                        \Log::error("Error inserting league data: " . $e->getMessage());
                    }
                }
            }
        }
        return $response;
    }
    public function fetchTopLeagues($apiUrl)
    {
        $response = Http::withHeaders(['x-apisports-key' => env('API_KEY')])
                        ->get("{$apiUrl}leagues")
                        ->json()['response'] ?? [];

        $topLeagues = League::orderBy('league_id')
        ->where('sport_id', 0)
        ->limit(10)
        ->get();
        return $topLeagues;
    }
}
