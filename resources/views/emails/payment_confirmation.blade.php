<!DOCTYPE html>
<html lang="{{ $lang }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- يمكنك تغيير الموضوع الافتراضي هنا أو تركه ليتم تحديده في Mailable --}}
    <title>{{ $lang == 'ar' ? 'تأكيد الإشتراك وبيانات التفعيل' : 'Subscription Confirmation & Activation Details' }}
    </title>
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
            /* قد تحتاج لتعديل محاذاة النص حسب الـ direction */
            text-align: {{ $lang == 'ar' ? 'right' : 'left' }};
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

        .details-box {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 5px;
            margin-top: 20px;
            background-color: #f9f9f9;
        }

        .details-box p {
            margin: 5px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            {{-- أضف شعارك هنا إذا أردت --}}
            {{-- <img src="{{ asset('assets/img/your-logo.png') }}" alt="Your Logo" style="max-width: 150px;"> --}}
        </div>

        <div class="content">
            <h2>{{ $lang == 'ar' ? 'مرحباً' : 'Hello' }} {{ $subscriber->name }},</h2>

            {{-- **هذا هو الجزء الذي يتغير حسب البيانات المتاحة** --}}

            {{-- الشرط: إذا كان كود التفعيل غير فارغ، اعرض تفاصيل التفعيل الكاملة --}}
            @if (!empty($subscriber->activation_code))
                {{-- **محتوى الرسالة النهائية (بتفاصيل التفعيل فقط)** --}}
                <p>{{ $lang == 'ar' ? 'تم تفعيل اشتراكك بنجاح! إليك بيانات التفعيل الخاصة بك:' : 'Your subscription has been successfully activated! Here are your activation details:' }}
                </p>

                <div class="details-box">
                    {{-- 1. رمز التفعيل --}}
                    <p>
                        <strong>{{ $lang == 'ar' ? 'رمز التفعيل' : 'Activation Code' }}:</strong>
                        {{ $subscriber->activation_code }}
                    </p>

                    {{-- 2. نوع الباقة (Main Category) --}}
                    {{-- تأكد أن علاقة mainCategory محملة --}}
                    @if ($subscriber->mainCategory)
                        <p>
                            <strong>{{ $lang == 'ar' ? 'نوع الباقة' : 'Package Type' }}:</strong>
                            {{ $lang == 'ar' ? $subscriber->mainCategory->name_ar : $subscriber->mainCategory->name_en }}
                        </p>
                    @endif

                    {{-- 3. نوع الخطة (Sub Category) --}}
                    {{-- تأكد أن علاقة subCategory محملة --}}
                    @if ($subscriber->subCategory)
                        <p>
                            <strong>{{ $lang == 'ar' ? 'نوع الخطة' : 'Plan Type' }}:</strong>
                            {{ $lang == 'ar' ? $subscriber->subCategory->name_ar : $subscriber->subCategory->name_en }}
                        </p>
                    @endif

                    {{-- 4. تاريخ التسجيل (Created At) --}}
                    @if ($subscriber->created_at)
                        <p>
                            <strong>{{ $lang == 'ar' ? 'تاريخ التسجيل' : 'Registration Date' }}:</strong>
                            {{ \Carbon\Carbon::parse($subscriber->created_at)->format('Y-m-d H:i') }}
                            {{-- يمكنك تعديل التنسيق --}}
                        </p>
                    @endif

                    {{-- 5. تاريخ الانتهاء (Calculated from Created At + Duration) --}}
                    {{-- نفترض أن مدة الإشتراك موجودة إما مباشرة على المشترك أو على subCategory المرتبط --}}
                    @if ($subscriber->created_at && $subscriber->duration)
                        <p>
                            <strong>{{ $lang == 'ar' ? 'تاريخ الانتهاء' : 'Expiry Date' }}:</strong>
                            {{ \Carbon\Carbon::parse($subscriber->created_at)->addDays($subscriber->duration)->format('Y-m-d') }}
                            {{-- يمكنك تعديل التنسيق --}}
                        </p>
                        {{-- شرط احتياطي إذا كانت المدة فقط على subCategory --}}
                    @elseif ($subscriber->created_at && $subscriber->subCategory && $subscriber->subCategory->duration)
                        <p>
                            <strong>{{ $lang == 'ar' ? 'تاريخ الانتهاء' : 'Expiry Date' }}:</strong>
                            {{ \Carbon\Carbon::parse($subscriber->created_at)->addDays($subscriber->subCategory->duration)->format('Y-m-d') }}
                            {{-- يمكنك تعديل التنسيق --}}
                        </p>
                    @endif

                    {{-- تم حذف باقي التفاصيل (السعر، DNS username, password, link, expiry) من هذا البريد --}}

                </div>
            @else
                {{-- **هذا هو الجزء الذي يظهر إذا كان كود التفعيل فارغاً (محتوى قيد المراجعة)** --}}
                <p>
                    {{ $lang == 'ar'
                        ? 'شكراً لاشتراكك! لقد تم استلام طلبك بنجاح وتم تأكيد دفعك.'
                        : 'Thank you for your subscription! Your request has been successfully received and your payment has been confirmed.' }}
                </p>
                <p>
                    {{ $lang == 'ar'
                        ? 'طلبك قيد المراجعة حالياً وسيتم تجهيز بيانات التفعيل والـ DNS الخاصة بك قريباً.'
                        : 'Your request is currently under review and your activation and DNS details will be prepared shortly.' }}
                </p>
                <p>
                    {{ $lang == 'ar'
                        ? 'سيتم إرسال رسالة إلكترونية أخرى تحتوي على رمز التفعيل النهائي وجميع التفاصيل المطلوبة بمجرد الانتهاء من المراجعة.'
                        : 'Another email containing the final activation code and all required details will be sent to you once the review is complete.' }}
                </p>

                {{-- يمكنك ترك تفاصيل الباقة والسعر هنا في رسالة قيد المراجعة إذا أردت --}}
                <div class="details-box">
                    <p>
                        <strong>{{ $lang == 'ar' ? 'اسم الباقة' : 'Package Name' }}:</strong>
                        {{ $lang == 'ar' && $subscriber->mainCategory ? $subscriber->mainCategory->name_ar : ($subscriber->mainCategory ? $subscriber->mainCategory->name_en : 'N/A') }}
                    </p>
                    <p>
                        <strong>{{ $lang == 'ar' ? 'نوع الخطة' : 'Plan Type' }}:</strong>
                        {{ $lang == 'ar' && $subscriber->subCategory ? $subscriber->subCategory->name_ar : ($subscriber->subCategory ? $subscriber->subCategory->name_en : 'N/A') }}
                    </p>
                    <p>
                        <strong>{{ $lang == 'ar' ? 'السعر المدفوع' : 'Amount Paid' }}:</strong>
                        {{ $subscriber->price ?? 'N/A' }} USD
                    </p>
                             @if ($subscriber->dns_expiry_date)
                         <p>
                            <strong>{{ $lang == 'ar' ? 'تاريخ الانتهاء ' : ' Expiry Date' }}:</strong>
                            {{ \Carbon\Carbon::parse($subscriber->dns_expiry_date)->format('Y-m-d') }} {{-- تنسيق التاريخ --}}
                        </p>
                    @endif
                    @if ($subscriber->activation_code)
                        <p>
                            <strong>{{ $lang == 'ar' ? 'رمز التفعيل' : 'Activation Code' }}:</strong>
                            {{ $subscriber->activation_code }}
                        </p>
                    @endif
                </div>


            @endif


            <p style="margin-top: 20px;">
                {{ $lang == 'ar' ? 'إذا كانت لديك أي استفسارات، يرجى التواصل مع فريق الدعم.' : 'If you have any questions, please contact our support team.' }}
            </p>
            {{-- يمكنك إضافة رابط الدعم هنا إذا أردت --}}
            {{-- <p><a href="{{ route('contact-us', ['lang' => $lang]) }}">{{ $lang == 'ar' ? 'اتصل بنا' : 'Contact Us' }}</a></p> --}}

        </div> {{-- إغلاق div class="content" --}}

        <div class="footer">
            <p>{{ $lang == 'ar' ? '© 2025 Croco. جميع الحقوق محفوظة.' : '© 2025 Croco. All rights reserved.' }}</p>
        </div>
    </div> {{-- إغلاق div class="container" --}}
</body>

</html>
