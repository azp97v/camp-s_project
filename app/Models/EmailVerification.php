<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailVerification extends Model
{
    protected $fillable = [
        'name',
        'email',
        'password',
        'otp_code',
        'otp_expires_at',
        'attempts',
    ];

    protected $casts = [
        'otp_expires_at' => 'datetime',
    ];

    /**
     * Check if OTP has expired
     */
    public function isOtpExpired(): bool
    {
        return now()->isAfter($this->otp_expires_at);
    }

    /**
     * Check if OTP is valid
     */
    public function isOtpValid(string $otp): bool
    {
        return $this->otp_code === $otp && !$this->isOtpExpired();
    }

    /**
     * Increment attempts counter
     */
    public function incrementAttempts(): void
    {
        $this->increment('attempts');
    }

    /**
     * Check if exceeded maximum attempts (5)
     */
    public function hasExceededAttempts(): bool
    {
        return $this->attempts >= 5;
    }
}
?>
