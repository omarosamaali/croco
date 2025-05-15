<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $lang == 'ar' ? 'إتمام البيانات' : 'Complete Your Details' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: #f4f4f4;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
        }

        h1 {
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        label {
            display: block;
            margin-bottom: 5px;
        }

        select,
        input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        button {
            width: 100%;
            padding: 10px;
            background: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background: #0056b3;
        }

        .error {
            color: red;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/plans.css') }}">
</head>

<body>
    <div>
        <img style="position: fixed; width: 100%; height: 100%; top: 0px;"
            src="https://tod-preprod.enhance.diagnal.com/resources/images/eam/production/ed90d982b63f4238a93d4dd6f4c5988d/800x450/packageselection_bg_5447.jpg"
            alt="">

        <div class="container" style="text-align: center; z-index: 999999999; position: relative;">
            <h1>{{ $lang == 'ar' ? 'إتمام البيانات' : 'Complete Your Details' }}</h1>
            <p style="text-align: center;">
                {{ $lang == 'ar' ? 'باقة: ' : 'Package: ' }}{{ $lang == 'ar' ? $mainCategory->name_ar : $mainCategory->name_en }}
                ({{ $lang == 'ar' ? 'المدة: ' : 'Duration: ' }}{{ floor($duration / 30) }}
                {{ $lang == 'ar' ? ($duration / 30 == 1 ? 'شهر' : 'أشهر') : ($duration / 30 == 1 ? 'month' : 'months') }},
                {{ $lang == 'ar' ? 'السعر: ' : 'Price: ' }}{{ $price }})
            </p>
            @if ($errors->any())
                <div class="error">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form action="{{ route('subscriber.store', ['lang' => $lang]) }}" method="POST">
                @csrf
                <input type="hidden" name="game_id" value="{{ $mainCategory->id }}">
                <input type="hidden" name="duration" value="{{ $duration }}">
                <div class="form-group">
                    <label for="country">{{ $lang == 'ar' ? 'الدولة' : 'Country' }}</label>
                    <select name="country" id="country" required>
                        <option value="EG" {{ old('country') == 'EG' ? 'selected' : '' }}>Egypt</option>
                        <option value="SA" {{ old('country') == 'SA' ? 'selected' : '' }}>Saudi Arabia</option>
                        <option value="AE" {{ old('country') == 'AE' ? 'selected' : '' }}>UAE</option>
                        <option value="KW" {{ old('country') == 'KW' ? 'selected' : '' }}>Kuwait</option>
                        <option value="QA" {{ old('country') == 'QA' ? 'selected' : '' }}>Qatar</option>
                        <option value="BH" {{ old('country') == 'BH' ? 'selected' : '' }}>Bahrain</option>
                        <option value="OM" {{ old('country') == 'OM' ? 'selected' : '' }}>Oman</option>
                        <option value="JO" {{ old('country') == 'JO' ? 'selected' : '' }}>Jordan</option>
                        <option value="LB" {{ old('country') == 'LB' ? 'selected' : '' }}>Lebanon</option>
                        <option value="MA" {{ old('country') == 'MA' ? 'selected' : '' }}>Morocco</option>
                        <option value="TN" {{ old('country') == 'TN' ? 'selected' : '' }}>Tunisia</option>
                        <option value="DZ" {{ old('country') == 'DZ' ? 'selected' : '' }}>Algeria</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="name">{{ $lang == 'ar' ? 'الاسم' : 'Name' }}</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required>
                </div>
                <div class="form-group">
                    <label for="email">{{ $lang == 'ar' ? 'البريد الإلكتروني' : 'Email' }}</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}" required>
                </div>
                <div class="form-group">
                    <label for="phone">{{ $lang == 'ar' ? 'رقم الهاتف' : 'Phone Number' }}</label>
                    <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required>
                </div>
                <button type="submit">{{ $lang == 'ar' ? 'إرسال' : 'Submit' }}</button>
            </form>
        </div>
</body>

</html>
