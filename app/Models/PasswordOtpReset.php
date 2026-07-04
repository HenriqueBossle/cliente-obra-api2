<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PasswordOtpReset extends Model
{
    /** @var string */
    protected $table = 'password_otp_resets';

    /** @var array */
    protected $fillable = [
        'email',
        'otp_hash',
        'expires_at',
    ];

    /** @var array */
    protected $dates = [
        'expires_at',
        'created_at',
        'updated_at',
    ];

    /**
     * Determine if the OTP is expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Scope a query to only include non‑expired OTPs.
     *
     * @param  Builder  $query
     * @return Builder
     */
    public function scopeValid(Builder $query): Builder
    {
        return $query->where('expires_at', '>', now());
    }
}