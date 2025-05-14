<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/translation.js'])

<head>
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/plans.css') }}">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: #f4f4f4;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            color: rgb(0, 0, 0);
            margin-bottom: 5px;
        }

        input {
            width: 95% !important;
        }

        input,
        select {
            background: #f4f4f4;
            text-align: center;
            font-family: 'cairo';
            width: 100%;
            color: rgb(24, 24, 24);
            padding: 10px;
            border: 1px solid rgb(150 150 150);
            border-radius: 15px;
        }

        button {
            background: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            font-family: 'cairo';
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }
    </style>
</head>


<body>
    <div>
        <img style="position: fixed; width: 100%; height: 100%;"
            src="https://tod-preprod.enhance.diagnal.com/resources/images/eam/production/ed90d982b63f4238a93d4dd6f4c5988d/800x450/packageselection_bg_5447.jpg"
            alt="">
        <div class="overlay">
        </div>
        {{-- Start Header --}}
        <header
            style="border-bottom: 5px solid var(--main-color); z-index: 9; position: relative; padding: 20px 35px; display: grid; align-items: center; grid-template-columns: repeat(3, minmax(0, 1fr));">
            <a href="/"
                style="text-align: left; font-size: 20px; color: var(--main-color); text-decoration: none; font-weight: bold; font-family: 'cairo';">
                {{ $lang == 'ar' ? 'تسجيل دخول' : 'Login' }}
            </a>
            <div class="logo-container" style="text-align: center;">
                <a href="/" class="logo-link">
                    <img src="{{ asset('assets/img/joystik-logo.svg') }}" style="width: 120px !important;"
                        class="logo" alt="Joystick Logo">
                </a>
            </div>
            <a href="/" style="text-align: right;">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                    fill="none">
                    <path d="M14.4297 5.92969L20.4997 11.9997L14.4297 18.0697" stroke="white" stroke-width="1.5"
                        stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M3.5 12H20.33" stroke="white" stroke-width="1.5" stroke-miterlimit="10"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>
        </header>
        {{-- End Header --}}

        <body>
            <div class="container" style="text-align: center; position: relative; z-index: 999999999999; background: #ffffff !important;">
                <h1 style="text-align: center;">{{ $lang == 'ar' ? 'تحويل فوري' : 'Bank Transfer' }}</h1>
                <p>{{ $lang == 'ar'
                    ? '  +20 10 05677471'
                    : 'Please transfer the amount to the following 
                        number: +20 10 05677471' }}
                </p>
                <form
                    action="{{ route('payment.transfer.store', ['lang' => $lang, 'subscriber_id' => $subscriber->id]) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label
                            for="transfer_image">{{ $lang == 'ar' ? 'صورة إيصال التحويل' : 'Transfer Receipt Image' }}</label>
                        <input type="file" style="text-align: center;" name="transfer_image" id="transfer_image" required>
                    </div>
                    <button type="submit">{{ $lang == 'ar' ? 'إرسال' : 'Submit' }}</button>
                </form>
            </div>
        </body>

</html>
