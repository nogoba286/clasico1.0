<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'username', 'email', 'password', 'first_name', 'last_name', 'birthday',
        'country', 'city', 'zip_code', 'address', 'phone_number', 'currency',
        'is_active', 'is_admin', 'balance'
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'is_active' => 'boolean',
        'is_admin' => 'boolean',
        'email_verified_at' => 'datetime',
    ];
}