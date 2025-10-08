<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Societe extends Authenticatable
{
    use HasFactory, Notifiable;
    protected $fillable = [
        'name',
        'contact',
        'email',
        'password',
        'profile_picture',
        'adresse',
        'archived_at',
    ];
}
