<!DOCTYPE html>
<html dir="rtl">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .container { max-width: 600px; margin: 20px auto; background: white; padding: 30px; border-radius: 8px; }
        h1 { color: #333; text-align: right; }
        .otp-box { background: #f9f9f9; border: 2px solid #4CAF50; padding: 20px; text-align: center; border-radius: 6px; margin: 20px 0; }
        .otp-code { font-size: 32px; font-weight: bold; color: #4CAF50; letter-spacing: 5px; }
        .expires { color: #666; text-align: right; margin-top: 15px; }
        .footer { text-align: center; color: #999; font-size: 12px; margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="container">
        <h1>مرحباً {{ $name }}</h1>

        <p>شكراً لتسجيلك معنا! يرجى استخدام الرمز التالي للتحقق من بريدك الإلكتروني:</p>

        <div class="otp-box">
            <p style="margin: 0; color: #999; font-size: 12px;">رمز التحقق:</p>
            <div class="otp-code">{{ $otpCode }}</div>
        </div>

        <div class="expires">
            ⏰ هذا الرمز صالح لمدة {{ $expiresInMinutes }} دقائق فقط
        </div>

        <p style="text-align: right; color: #555;">إذا لم تقم بطلب هذا الرمز، يرجى تجاهل هذا البريد.</p>

        <div class="footer">
            <p>© 2025 Camp's Project. جميع الحقوق محفوظة.</p>
        </div>
    </div>
</body>
</html>
