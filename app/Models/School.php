<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
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

public function ward(): BelongsTo
{
    return $this->belongsTo(Ward::class);
}
}
