<?php
// app/Models/Bet.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bet extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'event_id', 'odds_id', 'bet_amount', 'potential_payout', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function odds()
    {
        return $this->belongsTo(Odd::class);
    }
}
