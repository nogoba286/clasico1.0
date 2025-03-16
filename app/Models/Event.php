<?php
// app/Models/Event.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'timezone',
        'date',
        'venue_name',
        'venue_city',
        'status_long',
        'status_elapsed',
        'status_extra',
        'league_id',
        'home_goals',
        'home_id',
        'home_name',
        'home_logo',
        'away_goals',
        'away_id',
        'away_name',
        'away_logo'
    ];

    public function odds()
    {
        return $this->hasMany(Odd::class);
    }

    public function bets()
    {
        return $this->hasMany(Bet::class);
    }

    public $timestamps = true;
}
