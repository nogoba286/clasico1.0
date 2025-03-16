<?php
// app/Models/Odd.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Odd extends Model
{
    protected $table = 'odds';

    protected $fillable = ['event_id', 'league_id', 'odd_option_id', 'value', 'odd'];

    public $timestamps = true; // Ensures timestamps are managed
}
