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
    'ward_id',
    'council_id',
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
            'password'          => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    // 🏫 Shule aliyopo
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    // 🗺️ Kata aliyopo (ward_officer)
    public function ward()
    {
        return $this->belongsTo(Ward::class);
    }

    // 🏛️ Halmashauri (district_officer)
    public function council()
    {
        return $this->belongsTo(Council::class);
    }

    // 📅 Mahudhurio yake yote
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    // 🔄 Maombi ya uhamisho (kama mwalimu anayehamishwa)
    public function transfers()
    {
        return $this->hasMany(Transfer::class, 'user_id');
    }

    // 📤 Maombi ya uhamisho aliyoomba (kama afisa)
    public function requestedTransfers()
    {
        return $this->hasMany(Transfer::class, 'requested_by');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    // 👤 Jina kamili
    public function getFullNameAttribute(): string
    {
        return trim(
            $this->first_name . ' ' .
            ($this->middle_name ? $this->middle_name . ' ' : '') .
            $this->last_name
        );
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS
    |--------------------------------------------------------------------------
    */

    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    public function isHeadTeacher(): bool
    {
        return $this->role === 'head_teacher';
    }

    public function isWardOfficer(): bool
    {
        return $this->role === 'ward_officer';
    }

    public function isDistrictOfficer(): bool
    {
        return $this->role === 'district_officer';
    }

    public function isOfficer(): bool
    {
        return in_array($this->role, ['ward_officer', 'district_officer']);
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function getAuthIdentifierName()
    {
        return 'check_number';
    }
}