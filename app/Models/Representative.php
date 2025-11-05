<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Representative extends Model
{
    protected $fillable = [
        'name', 'dni', 'phone', 'email', 'role'
    ];

}
