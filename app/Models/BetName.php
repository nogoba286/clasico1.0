<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BetName extends Model
{
    protected $table = 'bet_names';
    protected $fillable = ['name'];
}
