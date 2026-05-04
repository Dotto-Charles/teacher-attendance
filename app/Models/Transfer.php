<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'from_school_id',
        'to_school_id',
        'requested_by',
        'status',
        'reason',
    ];

    // ── Mwalimu anayehamishwa ─────────────────────────────────────────
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ── Shule anakotoka ───────────────────────────────────────────────
    public function fromSchool()
    {
        return $this->belongsTo(School::class, 'from_school_id');
    }

    // ── Shule anakwenda ───────────────────────────────────────────────
    public function toSchool()
    {
        return $this->belongsTo(School::class, 'to_school_id');
    }

    // ── Aliyeomba uhamisho ────────────────────────────────────────────
    public function requester()
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    // ── Scopes ───────────────────────────────────────────────────────
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }
}