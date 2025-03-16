<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SportsCategory extends Model
{
    protected $table = 'sports';
    protected $fillable = ['name', 'icon', 'api'];
}
