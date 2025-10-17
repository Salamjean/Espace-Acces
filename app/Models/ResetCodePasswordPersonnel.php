<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResetCodePasswordPersonnel extends Model
{
    protected $fillable = [
        'email',
        'code',
    ];
}
