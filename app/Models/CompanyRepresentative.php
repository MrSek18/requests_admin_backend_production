<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyRepresentative extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'representative_id',
        'position'
    ];
}
