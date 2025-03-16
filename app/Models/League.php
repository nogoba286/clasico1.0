<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class League extends Model
{
    use HasFactory; // Optional, but recommended

    protected $table = 'leagues';

    protected $fillable = [
        'league_id', 'sport_id', 'name', 'logo', 'country', 'flag'
    ];

    public $timestamps = true; // Ensures timestamps are managed
}
