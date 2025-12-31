<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class EmailOtp extends Mailable
{
    use Queueable, SerializesModels;

    public $otp;
    public $expiresAt;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($otp, $expiresAt = null)
    {
        $this->otp = $otp;
        $this->expiresAt = $expiresAt;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $expiresInMinutes = null;
        if ($this->expiresAt) {
            try {
                $expiresInMinutes = \Carbon\Carbon::now()->diffInMinutes($this->expiresAt);
            } catch (\Exception $e) {
                $expiresInMinutes = null;
            }
        }

        return $this->subject('رمز التحقق')
                    ->view('emails.otp')
                    ->with([
                        'name' => null,
                        'otpCode' => $this->otp,
                        'expiresInMinutes' => $expiresInMinutes,
                    ]);
    }
}
