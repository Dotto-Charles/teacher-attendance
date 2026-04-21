<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = [
    'ward_id',
    'name',
    'code',
    'latitude',
    'longitude',
    'radius',
    
];
}
