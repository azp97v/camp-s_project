{{--
    OTP Email Template
    --------------------------------------------------------
    رسالة البريد الإلكتروني لإرسال رمز التحقق (OTP) للمستخدم.
    English: Email template for sending OTP verification code to user.
--}}
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>رمز التحقق</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            padding: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #b57b4a 0%, #8b5a2b 100%);
            color: #ffffff;
            padding: 40px 20px;
            text-align: center;
        }
        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
            font-weight: 700;
        }
        .header p {
            font-size: 14px;
            opacity: 0.9;
        }
        .content {
            padding: 40px 30px;
            text-align: right;
        }
        .content h2 {
            color: #333333;
            font-size: 20px;
            margin-bottom: 20px;
            font-weight: 600;
        }
        .content p {
            color: #555555;
            font-size: 14px;
            margin-bottom: 20px;
            line-height: 1.8;
        }
        .otp-section {
            background: #f8f9fa;
            border: 2px solid #b57b4a;
            border-radius: 10px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-label {
            color: #666666;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 15px;
            display: block;
            font-weight: 600;
        }
        .otp-code {
            background: #ffffff;
            color: #16a34a;
            font-size: 36px;
            font-weight: 700;
            font-family: 'Courier New', monospace;
            letter-spacing: 8px;
            padding: 20px;
            border-radius: 8px;
            display: inline-block;
            border: 2px dashed #16a34a;
            user-select: all;
        }
        .expires {
            color: #d97706;
            font-size: 13px;
            margin-top: 15px;
            font-weight: 500;
        }
        .warning {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 15px 20px;
            margin: 25px 0;
            border-radius: 4px;
            text-align: right;
            font-size: 13px;
            color: #78350f;
        }
        .footer {
            background: #f8f9fa;
            padding: 30px;
            text-align: center;
            border-top: 1px solid #e5e7eb;
        }
        .footer p {
            color: #999999;
            font-size: 12px;
            margin: 5px 0;
        }
        .footer-logo {
            font-size: 16px;
            font-weight: 700;
            color: #b57b4a;
            margin: 15px 0 10px;
        }
        .social-links {
            margin-top: 15px;
        }
        .social-links a {
            color: #b57b4a;
            text-decoration: none;
            margin: 0 10px;
            font-size: 12px;
        }
        .divider {
            border: 0;
            border-top: 1px solid #e5e7eb;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>✉️ رمز التحقق</h1>
            <p>تم طلب رمز تحقق من حسابك</p>
        </div>

        <!-- Main Content -->
        <div class="content">
            <h2>مرحباً {{ $name }}،</h2>

            <p>
                شكراً لتسجيلك في <strong>Step by Step</strong>! لقد أرسلنا رمز تحقق إلى عنوان بريدك الإلكتروني.
                استخدم الرمز أدناه للتحقق من حسابك:
            </p>

            <!-- OTP Code Section -->
            <div class="otp-section">
                <span class="otp-label">رمز التحقق الخاص بك</span>
                <div class="otp-code">{{ $code }}</div>
                <div class="expires">
                    ⏱️ ينتهي هذا الرمز بعد <strong>{{ $expiresInMinutes }}</strong> دقيقة
                </div>
            </div>

            <!-- Warning -->
            <div class="warning">
                <strong>🔒 لا تشارك هذا الرمز مع أحد!</strong><br>
                فريق Step by Step لن يطلب منك هذا الرمز عبر البريد أو الهاتف أبداً.
            </div>

            <!-- Instructions -->
            <p style="color: #666666; font-size: 14px; line-height: 1.8;">
                <strong>الخطوات التالية:</strong><br>
                1. انسخ أو اكتب الرمز أعلاه<br>
                2. عد إلى صفحة التحقق في التطبيق<br>
                3. اللصق الرمز والنقر على "تحقق من الرمز"<br>
                4. سيتم تفعيل حسابك فوراً
            </p>

            <!-- Help Text -->
            <p style="color: #999999; font-size: 13px; margin-top: 25px;">
                لم تطلب هذا الرمز؟ تجاهل هذه الرسالة أو اتصل بنا للإبلاغ عن نشاط غريب.
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div class="footer-logo">Step by Step</div>
            <p>منصة تطويرية متقدمة</p>
            <hr class="divider">
            <p>
                هذه رسالة آلية، يرجى عدم الرد عليها.<br>
                جميع الحقوق محفوظة © 2025 Step by Step
            </p>
            <div class="social-links">
                <a href="#">الدعم</a> •
                <a href="#">الشروط</a> •
                <a href="#">الخصوصية</a>
            </div>
        </div>
    </div>
</body>
</html>
