<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bets extends Model
{
    protected $table = 'tbl_bets';
    protected $fillable = ['user_id', 'event_id', 'bet_type', 'odds', 'stake', 'potential_payout', 'status', 'placed_at', 'status'];
}
