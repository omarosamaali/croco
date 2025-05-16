<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/translation.js'])

<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/plans.css') }}">
    <link rel="stylesheet" type="text/css"
        href="https://www.paypalobjects.com/webstatic/en_US/developer/docs/css/cardfields.css" />
    <style>
        form {
            position: relative;
            z-index: 9999999999999999999;
        }
    </style>
</head>

<body>

    <div>
        <img style="position: fixed; width: 100%; height: 100%;"
            src="https://tod-preprod.enhance.diagnal.com/resources/images/eam/production/ed90d982b63f4238a93d4dd6f4c5988d/800x450/packageselection_bg_5447.jpg"
            alt="">
        <div class="overlay"></div>
        <!-- Start Header -->
        <header
            style="border-bottom: 5px solid var(--main-color); z-index: 9; position: relative; padding: 20px 35px; display: grid; align-items: center; grid-template-columns: repeat(3, minmax(0, 1fr));">
            <a href="/" style="text-align: right;">
                <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24"
                    fill="none">
                    <path d="M14.4297 5.92969L20.4997 11.9997L14.4297 18.0697" stroke="white" stroke-width="1.5"
                        stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M3.5 12H20.33" stroke="white" stroke-width="1.5" stroke-miterlimit="10"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>

            <div class="logo-container" style="text-align: center;">
                <a href="/" class="logo-link">
                    <img src="{{ asset('assets/img/joystik-logo.svg') }}" style="width: 120px !important;"
                        class="logo" alt="Joystick Logo">
                </a>
            </div>
            {{-- <a href="/"
                style="text-align: left; font-size: 20px; color: var(--main-color); text-decoration: none; font-weight: bold; font-family: 'cairo';">
                {{ $lang == 'ar' ? 'تسجيل دخول' : 'Login' }}
            </a> --}}
        </header>
        <!-- End Header -->

        <div class="container" style="position: relative; z-index: 999999999999; background: #ffffff !important;">
            <h1 style="text-align: center;">{{ $lang == 'ar' ? 'تأكيد الاشتراك' : 'Confirm Subscription' }}</h1>

            <div class="container-details" style="display: flex; gap: 35px;">
                <div style="width: 50%;" class="details">
                    <h3 style="text-align: center; margin-top: 0px;">
                        {{ $lang == 'ar' ? 'تفاصيل الباقة' : 'Package Details' }}</h3>
                    <p><strong>{{ $lang == 'ar' ? 'اسم الباقة' : 'Package Name' }}</strong>
                        {{ $lang == 'ar' ? $subscriber->mainCategory->name_ar : $subscriber->mainCategory->name_en }}
                    </p>
                    <p><strong>{{ $lang == 'ar' ? 'المدة' : 'Duration' }}</strong>
                        {{ floor($subscriber->duration / 30) }} {{ $lang == 'ar' ? 'شهر' : 'months' }}</p>
                    <div>
                        <p><strong>{{ $lang == 'ar' ? 'السعر' : 'Price' }}</strong>
                            <span style="display: flex; align-items: center;">
                                {{ $price }}
                                $ </span>
                        </p>
                    </div>
                    <p><strong>{{ $lang == 'ar' ? 'الاسم' : 'Name' }}</strong> {{ $subscriber->name }}</p>
                    <p><strong>{{ $lang == 'ar' ? 'البريد الإلكتروني' : 'Email' }}:</strong> {{ $subscriber->email }}
                    </p>
                    <p><strong>{{ $lang == 'ar' ? 'رقم الهاتف' : 'Phone' }}</strong> {{ $subscriber->phone }}</p>
                    <p><strong>{{ $lang == 'ar' ? 'البلد' : 'Country' }}</strong> {{ $subscriber->country }}</p>
                </div>
                <div class="divider"
                    style="width: 1px; background-color: #ccc; position: relative; z-index: 99999999999;"></div>
                <div style="width: 50%;" class="payment-methods">
                    <h2>{{ $lang == 'ar' ? 'اختر طريقة الدفع' : 'Choose Payment Method' }}</h2>
                    <form id="payment-form" method="POST">
                        @csrf
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="paypal" checked
                                onchange="showPayPalOptions()">
                            <img src="https://www.paypalobjects.com/webstatic/mktg/logo/pp_cc_mark_111x69.jpg"
                                alt="PayPal">
                            <span>{{ $lang == 'ar' ? 'الدفع عبر PayPal' : 'Pay with PayPal' }}</span>
                        </label>
                        <label class="payment-option">
                            <input type="radio" name="payment_method" value="fawry" onchange="showFawryOptions()">
                            <img src="{{ asset('assets/img/fawr.webp') }}" alt="Fawry">
                            <span>{{ $lang == 'ar' ? 'تحويل فوري' : 'Fawry Payment' }}</span>
                        </label>

                        <!-- PayPal Payment Options Container -->
                        <!-- PayPal Payment Options Container -->
                        <div class="payment-options-container" id="paypal-options-container">
                            <h3 style="text-align: center;">
                                {{ $lang == 'ar' ? 'اختر طريقة الدفع بـ PayPal' : 'Choose PayPal Payment Method' }}
                            </h3>
{{-- PayPal Payment Button --}}
<input type="hidden" id="price" value="{{ $price }}">
<input type="hidden" id="paypal-form-action" value="{{ route('payment.process-paypal', ['lang' => $lang, 'subscriber_id' => $subscriber->id]) }}">
<div id="paypal-button-container" class="paypal-button-container"></div>
<p id="result-message"></p>

                            <!-- Phone Payment Button (Mobile only) -->
                            <button type="button" class="payment-option-button" id="paypal-phone-btn"
                                onclick="selectPaymentOption('paypal-phone', '{{ route('payment.paypal', ['lang' => $lang, 'subscriber_id' => $subscriber->id, 'type' => 'phone']) }}')">
                                <img src="https://cdn-icons-png.flaticon.com/512/0/191.png" alt="Phone Payment"
                                    class="payment-icon">
                                {{ $lang == 'ar' ? 'الدفع بالهاتف' : 'Pay with Phone' }}
                            </button>
                        </div>

                        <!-- PayPal Account Form (hidden) -->
                        <form id="paypal-form"
                            action="{{ route('payment.paypal', ['lang' => $lang, 'subscriber_id' => $subscriber->id, 'type' => 'card']) }}"
                            method="POST" style="display: none;">
                            @csrf
                            <input type="hidden" name="type" value="account">
                            {{-- <button style="width: 100%;" type="submit">
                                {{ $lang == 'ar' ? 'متابعة الدفع' : 'Continue Payment' }}
                            </button> --}}
                        </form>
                        <!-- Fawry Instructions Container (Standalone Form) -->
                        <div id="fawry-instructions" style="display: none;">
                            <p>{{ $lang == 'ar' ? 'يرجى تحويل المبلغ إلى رقم فوري التالي:' : 'Please transfer the amount to the following Fawry number:' }}
                            </p>
                            <p><strong>+20 100 5677 471</strong></p>
                            <p>{{ $lang == 'ar' ? 'بعد التحويل، يرجى رفع صورة إيصال التحويل أدناه:' : 'After transferring, please upload the transfer receipt image below:' }}
                            </p>
                            <form
                                action="{{ route('payment.transfer.store', ['lang' => $lang, 'subscriber_id' => $subscriber->id]) }}"
                                method="POST" enctype="multipart/form-data" id="fawry-upload-form">
                                @csrf
                                <div class="form-group">
                                    <label
                                        for="transfer_image">{{ $lang == 'ar' ? 'صورة إيصال التحويل' : 'Transfer Receipt Image' }}</label>
                                    <input type="file" style="text-align: center;" name="transfer_image"
                                        id="transfer_image" required>
                                </div>
                                <button type="submit">{{ $lang == 'ar' ? 'إرسال' : 'Submit' }}</button>
                            </form>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" id="submit-button" style="display: none;">
                            {{ $lang == 'ar' ? 'متابعة الدفع' : 'Continue Payment' }}
                        </button>

                        <!-- PayPal Account Form -->
                        <form id="paypal-form"
                            action="{{ route('payment.paypal', ['lang' => $lang, 'subscriber_id' => $subscriber->id]) }}"
                            method="POST" style="display: none;">
                            @csrf
                            <button style="width: 100%;" type="submit">
                                {{ $lang == 'ar' ? 'متابعة الدفع' : 'Continue Payment' }}
                            </button>
                        </form>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="mt-4 text-center">
        <p>{{ __('Or pay with') }}</p>
        <a href="{{ route('payment.paypal', ['lang' => $lang, 'subscriber_id' => $subscriber->id]) }}"
            class="btn btn-secondary">
            <i class="fab fa-paypal"></i> PayPal
        </a>
        <a href="{{ route('payment.transfer', ['lang' => $lang, 'subscriber_id' => $subscriber->id]) }}"
            class="btn btn-outline-secondary ml-2">
            <i class="fas fa-university"></i> {{ __('Bank Transfer') }}
        </a>
    </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
        showPayPalOptions();

        function showPayPalOptions() {
            selectedPaymentMethod = 'paypal';
            document.getElementById('paypal-options-container').style.display = 'block';
            document.getElementById('fawry-instructions').style.display = 'none';
            document.getElementById('transfer_image').removeAttribute('required');
            resetOptionButtons();
            if (selectedPaymentOption) {
                document.getElementById(selectedPaymentOption + '-btn').classList.add('selected');
            }
        }

        function showFawryOptions() {
            selectedPaymentMethod = 'fawry';
            document.getElementById('paypal-options-container').style.display = 'none';
            document.getElementById('fawry-instructions').style.display = 'block';
            document.getElementById('submit-button').style.display = 'none';
            document.getElementById('transfer_image').setAttribute('required', '');
            selectedPaymentOption = null;
            resetOptionButtons();
        }

        function resetOptionButtons() {
            const optionButtons = document.querySelectorAll('.payment-option-button');
            optionButtons.forEach(button => button.classList.remove('selected'));
        }

        function selectPaymentOption(option, formAction) {
            console.log('Selected Option:', option, 'Form Action:', formAction);
            selectedPaymentOption = option;
            selectedFormAction = formAction;
            resetOptionButtons();
            document.getElementById(option + '-btn').classList.add('selected');
            updatePaymentTypeField(option);
            document.getElementById('submit-button').style.display = 'none';
            document.getElementById('paypal-form').style.display = 'none';
            document.getElementById('card-form').style.display = 'none';
            if (option === 'paypal-account') {
                document.getElementById('paypal-form').style.display = 'block';
                document.getElementById('paypal-form').action = formAction;
            } else if (option === 'paypal-card') {
                document.getElementById('card-form').style.display = 'block';
                document.getElementById('payment-form').action = formAction;
            } else {
                document.getElementById('submit-button').style.display = 'block';
            }
        }

        function updatePaymentTypeField(option) {
            let paymentTypeInput = document.querySelector('input[name="payment_type"]');
            if (!paymentTypeInput) {
                paymentTypeInput = document.createElement('input');
                paymentTypeInput.type = 'hidden';
                paymentTypeInput.name = 'payment_type';
                document.getElementById('payment-form').appendChild(paymentTypeInput);
            }
            paymentTypeInput.value = option;
        }
    });
</script>
    <script
        src="https://www.paypal.com/sdk/js?client-id=AQHf4W6pc8XEeFoa3AqCJp4zl6jqwaGtw867CiE5EWcatMvIRc9dNTifo9HDpFyo_F4DMyoCEPmXVgHy&buyer-country=US&currency=USD&components=buttons&enable-funding=venmo">
    </script>
    {{-- Import app.js  --}}
    <script src="{{ asset('js/app.js') }}"></script>
</body>

</html>
