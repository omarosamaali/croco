<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $lang == 'ar' ? 'تأكيد الاشتراك' : 'Subscription Confirmation' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Cairo', sans-serif; background: #f4f4f4; color: #333; }
        .container { max-width: 600px; margin: 50px auto; padding: 20px; background: white; border-radius: 8px; }
        h1 { text-align: center; }
        p { margin: 10px 0; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; }
        .btn:hover { background: #0056b3; }
    </style>
</head>
<body>
    <div class="container">
        <h1>{{ $lang == 'ar' ? 'تأكيد الاشتراك' : 'Subscription Confirmation' }}</h1>
        <p>{{ $lang == 'ar' ? 'شكرًا على اشتراكك!' : 'Thank you for your subscription!' }}</p>
        <p>
            {{ $lang == 'ar' ? 'الباقة: ' : 'Package: ' }}
            {{ $lang == 'ar' ? $subscriber->mainCategory->name_ar : $subscriber->mainCategory->name_en }}
        </p>
        <p>
            {{ $lang == 'ar' ? 'الخطة: ' : 'Plan: ' }}
            {{ $lang == 'ar' ? $subscriber->subCategory->name_ar : $subscriber->subCategory->name_en }}
            ({{ $lang == 'ar' ? 'المدة: ' : 'Duration: ' }}{{ floor($subscriber->subCategory->duration / 30) }}
            {{ $lang == 'ar' ? ($subscriber->subCategory->duration / 30 == 1 ? 'شهر' : 'أشهر') : ($subscriber->subCategory->duration / 30 == 1 ? 'month' : 'months') }})
        </p>
        <p>{{ $lang == 'ar' ? 'السعر: ' : 'Price: ' }}{{ $price }}</p>
        <p>{{ $lang == 'ar' ? 'الاسم: ' : 'Name: ' }}{{ $subscriber->name }}</p>
        <p>{{ $lang == 'ar' ? 'البريد الإلكتروني: ' : 'Email: ' }}{{ $subscriber->email }}</p>
        <p>{{ $lang == 'ar' ? 'رقم الهاتف: ' : 'Phone: ' }}{{ $subscriber->phone }}</p>
        <p>{{ $lang == 'ar' ? 'الدولة: ' : 'Country: ' }}{{ $subscriber->country }}</p>
        <a href="{{ route('payment.paypal', ['lang' => $lang, 'subscriber_id' => $subscriber->id]) }}"
           class="btn">{{ $lang == 'ar' ? 'الدفع باستخدام PayPal' : 'Pay with PayPal' }}</a>
        <a href="{{ route('payment.transfer', ['lang' => $lang, 'subscriber_id' => $subscriber->id]) }}"
           class="btn">{{ $lang == 'ar' ? 'الدفع عن طريق التحويل البنكي' : 'Pay via Bank Transfer' }}</a>
    </div>
</body>
</html>