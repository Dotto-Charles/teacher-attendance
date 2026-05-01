<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'first_name',
    'middle_name',
    'last_name',
    'check_number',
    'email',
    'phone',
    'sex',
    'password',
    'role',
    'status',
    'school_id',
])]

#[Hidden([
    'password',
    'remember_token',
])]

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Cast attributes
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // 🏫 User belongs to school
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    
        public function council()
{
    return $this->belongsTo(\App\Models\Council::class);
}
    /*
    |--------------------------------------------------------------------------
    | ACCESSORS (PROFESSIONAL)
    |--------------------------------------------------------------------------
    */

    // 👤 Full Name
    public function getFullNameAttribute(): string
    {
        return trim(
            $this->first_name . ' ' .
            $this->middle_name . ' ' .
            $this->last_name
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS (OPTIONAL BUT POWERFUL)
    |--------------------------------------------------------------------------
    */

    // Check roles
    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    public function isHeadTeacher(): bool
    {
        return $this->role === 'head_teacher';
    }

    public function isOfficer(): bool
    {
        return in_array($this->role, ['ward_officer', 'district_officer']);
    }

    // Approval status
    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }
    public function getAuthIdentifierName()
{
    return 'check_number';
}

public function ward()
{
    return $this->belongsTo(Ward::class);
}

public function attendances()
{
    return $this->hasMany(\App\Models\Attendance::class);
}
}