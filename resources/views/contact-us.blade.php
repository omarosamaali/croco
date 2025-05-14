@extends('layouts.app')
@section('title', 'كروكو | CROCO')

@section('content')
    <style>
        body {
            overflow-y: auto !important;
        }
        .overlay {
            /* Add Linear Gridean Background */
            background-image: linear-gradient(to bottom, rgba(253, 205, 11, 1), rgba(253, 205, 11, 0.2));
            /* background-color: #FDCD0B; */
            top: 0;
            position: fixed;
            width: 100%;
            z-index: 999;
            height: 100%;
        }

        .know-us-content {
            display: flex;
            z-index: 999999999999999999999999999999;
            align-items: center;
            justify-content: center;
            width: max-content;
            background-color: #fdcd08 !important;
            padding: 15px;
            margin: 0px auto;
            border-radius: 10px;
            text-align: center;
        }
    </style>
    <div>

        @if (isset($banners) && $banners->where('location')->first())
            @php
                $heroBanner = $banners->where('location')->first();
            @endphp
            <img src="{{ asset('storage/' . $heroBanner->image_path) }}"
                style="top: 0; position: fixed; width: 100%; height: 100%;" alt="Banner">
        @else
            {{-- Fallback to static image if no matching banner is found --}}
            {{-- <img src="{{ asset('assets/img/background.jpg') }}" style="top: 0; position: fixed;" alt=""> --}}
        @endif
        {{-- Add OverLay --}}
        <div class="overlay">
        </div>
    </div>
    <!-- Start Lines -->

<style>
    .contact-form {
        max-width: 32rem;
        margin-left: auto;
        margin-right: auto;
        background-color: #f7f7f7;
        padding: 1rem;
        border-radius: 0.75rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .input-field {
        width: 94%;
        padding-left: 0.75rem;
        padding-right: 0.75rem;
        padding-top: 0.5rem;
        padding-bottom: 0.5rem;
        background-color: #f8f8f8;
        color: rgb(0, 0, 0);
        border: 1px solid #dddddd;
        border-radius: 0.375rem;
        text-align: center;
        outline: none;
    }

    .input-field:focus {
        border-color: #2563EB;
        box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5);
    }

    .checkbox {
        margin-left: 0.5rem;
        background-color: #1E1E1E;
        color: #3B82F6;
    }

    .submit-button:hover {
        background-color: #2563EB;
    }

@media (max-width: 768px) {
    .contact-form {
        margin-left: 15px !important;
        margin-right: 15px !important;
    }
}
</style>

<section class="contact-section" style="margin-bottom: 150px; z-index: 99999; margin-top: -23px; margin-left: auto; margin-right: auto; width: 100%;">
    <h1 style="display: block; color: black; text-align: center; ">{{ $lang == 'ar' ? 'اتصل بنا' : 'Contact Us' }}</h1>
    <form class="contact-form" method="POST" action="{{ route('contact.store', ['lang' => app()->getLocale()]) }}">
        @csrf
        <div class="form-group" style="margin-bottom: 1rem;">
            <label for="name" class="label" style="display: block; color: black; text-align: center; margin-bottom: 0.5rem;" data-i18n="Full Name">{{ $lang == 'ar' ? 'الاسم الكامل' : 'Full Name' }}</label>
            <input type="text" id="name" name="name" required class="input-field">
        </div>

        <div class="form-group" style="margin-bottom: 1rem;">
            <label for="email" class="label" style="display: block; color: black; text-align: center; margin-bottom: 0.5rem;" data-i18n="Email">{{ $lang == 'ar' ? 'البريد الالكتروني' : 'Email' }}</label>
            <input type="email" id="email" name="email" required class="input-field">
        </div>

        <div class="form-group" style="margin-bottom: 1rem;">
            <label for="phone" class="label" style="display: block; color: black; text-align: center; margin-bottom: 0.5rem;" data-i18n="Phone">{{ $lang == 'ar' ? 'رقم الهاتف' : 'Phone' }}</label>
            <input type="tel" id="phone" name="phone" class="input-field">
        </div>

        <div class="form-group">
            <label for="message" class="label" style="display: block; color: black; text-align: center; margin-bottom: 0.5rem;" data-i18n="Your Message">{{ $lang == 'ar' ? 'رسالتك' : 'Your Message' }}</label>
            <textarea style="max-height: 40px;" id="message" name="message" rows="4" required class="input-field"></textarea>
        </div>

        <div class="form-group" style="margin-bottom: 1rem;">
            <label class="checkbox-label" style="
                direction: rtl;
                display: flex; align-items: center; color: rgb(0, 0, 0);">
                <input type="checkbox" name="privacy" required class="checkbox">
                <span data-i18n="I agree to the privacy policy and terms of use" class="privacy-text" style="color:black; text-decoration: underline; cursor: pointer;">
                    {{ $lang == 'ar' ? 'اوافق على سياسة الخصوصية وشروط الاستخدام' : 'I agree to the privacy policy and terms of use' }}
                </span>
            </label>
        </div>

        <button type="submit" class="submit-button" style="z-index: 99999999; cursor: pointer; font-family: 'cairo'; border: 0px; width: 100%; background-color: #a3b740; color: white; padding-top: 0.75rem; padding-bottom: 0.75rem; border-radius: 0.375rem; transition: background-color 0.3s;">
            {{ $lang == 'ar' ? 'إرسال' : 'Send' }}
        </button>
    </form>
</section>
{{-- End Contact Us Section --}}

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if (session('success'))
    <script>
        Swal.fire({
            icon: 'success',
            title: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 3000,
            toast: true,
            position: 'top-end'
        });
    </script>
@endif



    <style>
        /* خطوط الخلفية */
        .grid-lines-container {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 100%;
        }

        .line,
        .line-vertical {
            background-color: rgba(255, 255, 255, 0.1);
            width: 1px;
            height: 100%;
            display: inline-block;
        }

        /* العناوين */
        .page-title {
            font-size: 2rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            color: white;
        }

        /* قسم الأخبار */
        .news-section {
            text-align: center;
            padding: 50px 0;
        }

        .news-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 0 20px;
        }

        .news-card {
            background-color: #2c2c38;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .news-card-alt {
            padding-top: 30px;
        }

        .news-meta {
            color: #c4d5f6;
            font-size: 14px;
            display: flex;
            justify-content: space-between;
        }

        .news-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: white;
        }

        /* الألعاب */
        .game-cards-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            /* 4 أعمدة بشكل افتراضي */
            z-index: 99999;
            position: relative;
            gap: 20px;
            padding: 20px;

        }

        /* Media Queries لتقليل عدد الأعمدة بناءً على حجم الشاشة */

        /* شاشات أصغر من 1200px: 3 أعمدة */
        @media (max-width: 1200px) {
            .game-cards-container {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        /* شاشات أصغر من 900px: عمودين */
        @media (max-width: 900px) {
            .game-cards-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* شاشات أصغر من 600px: عمود واحد */
        @media (max-width: 600px) {
            .game-cards-container {
                grid-template-columns: 1fr;
                /* أو repeat(1, 1fr) */
            }
        }

        .game-card {
            background-color: #0d0f12;
            border: 1px solid #242829;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
        }

        .game-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: white;
        }

        .game-genre {
            color: gray;
            font-size: 0.9rem;
        }

        .available-text {
            font-size: 0.9rem;
            color: white;
        }

        .platform-icons {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .platform-icon {
            position: relative;
        }

        .platform-image {
            width: 24px;
            height: 24px;
            border-radius: 50%;
        }

        .tooltip {
            position: absolute;
            top: -30px;
            left: 10px;
            background: black;
            color: white;
            padding: 5px;
            font-size: 12px;
            border-radius: 5px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .platform-icon:hover .tooltip {
            opacity: 1;
        }

        /* زر عرض المزيد */
        .show-more-container {
            text-align: center;
            margin-top: 30px;
        }

        .show-more-btn {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>

@endsection
