<?php

namespace App\Http\Controllers;

use App\Events\OddsUpdated;
use Carbon\Carbon;
use App\Models\SportsCategory;
use App\Models\League;
use App\Models\Team;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{

    public function __construct()
    {
        
    }

    public function register(Request $request){
        if ($request->isMethod('get')) {
            return view('scenes.register');
        }
        if ($request->isMethod('post')){
            $validator = Validator::make($request->all(), [
                'username' => 'required|string|unique:users',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|min:6|confirmed',
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'birthday' => 'nullable|date',
                'country' => 'nullable|string',
                'city' => 'nullable|string',
                'zip_code' => 'nullable|string',
                'address' => 'nullable|string',
                'phone_number' => 'nullable|string',
                'currency' => 'nullable|string',
            ]);
    
            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
    
            $user = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'birthday' => $request->birthday,
                'country' => $request->country,
                'city' => $request->city,
                'zip_code' => $request->zip_code,
                'address' => $request->address,
                'phone_number' => $request->phone_number,
                'currency' => $request->currency ?? 'USD',
                'balance' => 10,
                'is_active' => true,
                'is_admin' => false,
            ]);

            Auth::login($user);
    
            return redirect()->route('home');
        }
    }


    public function login(Request $request)
    {
        // Validate the form data
        $credentials = $request->validate([
            'username' => 'required|string',
            'password' => 'required|min:6',
        ]);

        // Authenticate with username instead of email
        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            
            return redirect()->route('home');
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }

}
