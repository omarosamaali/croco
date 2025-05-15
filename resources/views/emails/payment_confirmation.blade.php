<!DOCTYPE html>
<html lang="{{ $lang }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $lang == 'ar' ? 'تأكيد الدفع' : 'Payment Confirmation' }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 0;
            direction: {{ $lang == 'ar' ? 'rtl' : 'ltr' }};
        }

        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            padding-bottom: 20px;
        }

        .header img {
            max-width: 150px;
        }

        .content {
            font-size: 16px;
            line-height: 1.6;
            text-align: center;
        }

        .content p {
            margin: 10px 0;
        }

        .content strong {
            color: #000;
        }

        .footer {
            text-align: center;
            padding-top: 20px;
            font-size: 14px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('assets/img/joystik-logo.svg') }}" style="width: 120px !important;" class="logo" alt="Joystick Logo">
        </div>
        <div class="content">
            <h2>{{ $lang == 'ar' ? 'مرحبًا' : 'Hello' }} {{ $subscriber->name }},</h2>
            <p>{{ $lang == 'ar' ? 'نشكرك على اشتراكك! تم تأكيد دفعك بنجاح.' : 'Thank you for your subscription! Your payment has been successfully confirmed.' }}</p>
            <p>
                <strong>{{ $lang == 'ar' ? 'اسم الباقة' : 'Package Name' }}:</strong>
                {{ $lang == 'ar' ? $subscriber->mainCategory->name_ar : $subscriber->mainCategory->name_en }}
            </p>
            <p>
                <strong>{{ $lang == 'ar' ? 'المدة' : 'Duration' }}:</strong>
                {{ floor($subscriber->duration / 30) }} {{ $lang == 'ar' ? ($subscriber->duration / 30 == 1 ? 'شهر' : 'أشهر') : ($subscriber->duration / 30 == 1 ? 'month' : 'months') }}
            </p>
            <p>
                <strong>{{ $lang == 'ar' ? 'السعر' : 'Price' }}:</strong>
                {{ $subscriber->price }} USD
            </p>
            <p>
                <strong>{{ $lang == 'ar' ? 'كود التفعيل' : 'Activation Code' }}:</strong>
                {{ $subscriber->activation_code }}
            </p>
            <p>{{ $lang == 'ar' ? 'إذا كانت لديك أي استفسارات، يرجى التواصل مع فريق الدعم.' : 'If you have any questions, please contact our support team.' }}</p>
        </div>
        <div class="footer">
            <p>{{ $lang == 'ar' ? '© 2025 Joystick. جميع الحقوق محفوظة.' : '© 2025 Joystick. All rights reserved.' }}</p>
        </div>
    </div>
</body>
</html>
```