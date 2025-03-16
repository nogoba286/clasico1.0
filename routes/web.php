<?php

use Illuminate\Support\Facades\Route;
use App\Events\OddsUpdated;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\FavouriteController;
use App\Http\Middleware\CheckUserRole;


Route::get('/updateData', [HomeController::class, 'updateData']);
Route::get('/go-to-home', [HomeController::class, 'goToHome']);
Route::match(['get', 'post'], '/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'login'])->name('login');

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/get-all-data-by-date', [HomeController::class, 'fetchAllDataByDate']);

Route::get('/update-live', [HomeController::class, 'getLiveData']);
Route::get('/get-odd-details', [HomeController::class, 'fetchOddById']);
Route::get('/fetch-only-live-bets', [HomeController::class, 'fetchOnlyLiveBets']);
Route::middleware([CheckUserRole::class])->group(function () {
    Route::post('/fetch-my-bets', [HomeController::class, 'fetchMyBets']);
    Route::post('/place-bets', [HomeController::class, 'placeBets']);
    Route::post('/push-bet-item', [HomeController::class, 'pushBetItem']);
    Route::post('/delete-bet-item', [HomeController::class, 'deleteBetItem']);
    Route::post('/add-favorite', [FavouriteController::class, 'addFavorite']);
    Route::post('/logout', function () {
        Auth::logout();  // Log the user out
        return redirect('/');  // Redirect to the home page or any other page
    })->name('logout'); 
});
