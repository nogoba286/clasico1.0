<?php
// app/Models/Event.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FavouriteBet extends Model
{
    protected $table = 'favourite_bets';

    protected $fillable = ['fixture_id', 'user_id'];

}
