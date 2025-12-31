Mail setup (Gmail / SMTP)

Required `.env` keys for Gmail (example):

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@gmail.com
MAIL_PASSWORD=app-password-or-smtp-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your@gmail.com
MAIL_FROM_NAME="Your App Name"

Notes:
- For Gmail you must enable an App Password (preferred) or allow less secure apps (not recommended).
- For local development use Mailtrap or a local SMTP server to avoid Gmail restrictions.
- After updating `.env`, run `php artisan config:clear`.

Test sending quickly via tinker:

php artisan tinker
\>>> Mail::raw('test', function($m){$m->to('you@example.com')->subject('test');});
