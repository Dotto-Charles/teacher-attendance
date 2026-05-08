<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ward extends Model
{
    public function council()
{
    return $this->belongsTo(Council::class);
}
}
