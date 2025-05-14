<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $lang == 'ar' ? 'تم الدفع بنجاح' : 'Payment Successful' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/translation.js'])
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background-color: #f4f6f8;
            direction: {{ $lang == 'ar' ? 'rtl' : 'ltr' }};
            margin: 0;
            padding: 0;
        }

        .bg-image {
            position: fixed;
            width: 100%;
            height: 100%;
            object-fit: cover;
            z-index: -2;
        }

        .overlay {
            position: fixed;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.7);
            z-index: -1;
        }

        header {
            border-bottom: 5px solid var(--main-color);
            z-index: 9;
            position: relative;
            padding: 20px 35px;
            display: grid;
            align-items: center;
            grid-template-columns: repeat(3, minmax(0, 1fr));
        }

        .success-container {
            max-width: 800px;
            margin: 50px auto;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            padding: 40px;
            text-align: center;
            position: relative;
            z-index: 10;
        }

        .success-icon {
            width: 80px;
            height: 80px;
            background-color: #4CAF50;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
        }

        .success-icon svg {
            width: 40px;
            height: 40px;
            color: white;
        }

        h1 {
            color: #333;
            margin-bottom: 20px;
            font-weight: 700;
        }

        .subscription-details {
            margin: 30px 0;
            padding: 20px;
            background-color: #f9f9f9;
            border-radius: 8px;
            text-align: {{ $lang == 'ar' ? 'right' : 'left' }};
        }

        .subscription-details p {
            margin: 10px 0;
            font-size: 16px;
        }

        .subscription-details strong {
            font-weight: 600;
            color: #333;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: var(--main-color, #1a73e8);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            font-weight: 600;
            margin-top: 20px;
            transition: all 0.3s ease;
        }

        .btn:hover {
            background-color: #0d47a1;
            transform: translateY(-2px);
        }

        .payment-id {
            font-size: 14px;
            color: #666;
            margin-top: 30px;
        }
    </style>
</head>

<body>
    <!-- Background Image and Overlay -->
    <img class="bg-image" src="https://tod-preprod.enhance.diagnal.com/resources/images/eam/production/ed90d982b63f4238a93d4dd6f4c5988d/800x450/packageselection_bg_5447.jpg" alt="">
    <div class="overlay"></div>

    <!-- Header -->
    <header>
        <a href="/" style="text-align: {{ $lang == 'ar' ? 'right' : 'left' }}; font-size: 20px; color: var(--main-color); text-decoration: none; font-weight: bold; font-family: 'cairo';">
            {{ $lang == 'ar' ? 'تسجيل دخول' : 'Login' }}
        </a>
        <div class="logo-container" style="text-align: center;">
            <a href="/" class="logo-link">
                <img src="{{ asset('assets/img/joystik-logo.svg') }}" style="width: 120px !important;" class="logo" alt="Joystick Logo">
            </a>
        </div>
        <a href="/" style="text-align: {{ $lang == 'ar' ? 'left' : 'right' }};">
            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none">
                <path d="{{ $lang == 'ar' ? 'M9.5703 5.92969L3.5003 11.9997L9.5703 18.0697' : 'M14.4297 5.92969L20.4997 11.9997L14.4297 18.0697' }}" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                <path d="{{ $lang == 'ar' ? 'M20.5 12H3.67' : 'M3.5 12H20.33' }}" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </a>
    </header>

    <!-- Success Container -->
    <div class="success-container">
        <div class="success-icon">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="white">
                <path d="M0 0h24v24H0z" fill="none"/>
                <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>
            </svg>
        </div>
        
        <h1>{{ $lang == 'ar' ? 'تم الدفع بنجاح!' : 'Payment Successful!' }}</h1>
        <p>{{ $lang == 'ar' ? 'تم تأكيد اشتراكك بنجاح. شكراً لاختيارك خدماتنا!' : 'Your subscription has been confirmed successfully. Thank you for choosing our services!' }}</p>
        
        <!-- Subscription Details -->
        <div class="subscription-details">
            <p><strong>{{ $lang == 'ar' ? 'كود التفعيل:' : 'Activation Code:' }}</strong> {{ $subscriber->activation_code ?? $subscriber->id .'-'. substr(md5($subscriber->email), 0, 6) }}</p>
            <p><strong>{{ $lang == 'ar' ? 'اسم الباقة:' : 'Package Name:' }}</strong> {{ $subscriber->game->name }}</p>
            <p><strong>{{ $lang == 'ar' ? 'المدة:' : 'Duration:' }}</strong> {{ floor($subscriber->duration / 30) }} {{ $lang == 'ar' ? 'شهر' : 'months' }}</p>
            <p><strong>{{ $lang == 'ar' ? 'تاريخ الانتهاء:' : 'Expiry Date:' }}</strong> {{ date('Y-m-d', strtotime('+'.$subscriber->duration.' days')) }}</p>
        </div>
        
        <a href="{{ route('home', ['lang' => $lang]) }}" class="btn">{{ $lang == 'ar' ? 'العودة إلى الصفحة الرئيسية' : 'Return to Homepage' }}</a>
        
        @if(isset($payment_id))
        <p class="payment-id">{{ $lang == 'ar' ? 'رقم العملية:' : 'Transaction ID:' }} {{ $payment_id }}</p>
        @endif
    </div>
</body>
</html>