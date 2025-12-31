<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailOtp as EmailOtpMailable;

class SendTestMail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mail:test {email? : Destination email address (or set MAIL_TEST_ADDRESS in .env)}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send a test OTP email to verify mail configuration';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $email = $this->argument('email') ?: env('MAIL_TEST_ADDRESS');

        if (! $email) {
            $this->error('No target email provided. Pass an email or set MAIL_TEST_ADDRESS in .env');
            return 1;
        }

        $otp = 'TEST-' . strtoupper(substr(sha1(now()), 0, 6));
        $expires = now()->addMinutes(3);

        try {
            Mail::to($email)->send(new EmailOtpMailable($otp, $expires));
            $this->info("Test OTP sent to {$email}");
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to send test email: ' . $e->getMessage());
            return 1;
        }
    }
}
