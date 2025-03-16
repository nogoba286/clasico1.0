<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SportsCategory;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Http;

class SportController extends Controller
{
    public function index()
    {
        $sports = SportsCategory::all();
        return $sports;
    }
}
