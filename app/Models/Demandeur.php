<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Demandeur extends Authenticatable
{
     use HasFactory, Notifiable;
     protected $fillable = [
        'name',
        'email',
        'password',
        'prenom',
        'contact',
        'adresse',
        'Fonction',
        'archived_at',
        'profile_picture',
    ];
}
