@extends('layouts.adminLayout')

@section('title', $lang == 'ar' ? 'إضافة/تعديل الشروط والأحكام' : 'Add/Edit Terms and Conditions')

@section('content')
<div style="padding: 1rem; max-width: 48rem; margin-bottom: 5rem; margin: 0 auto; min-height: 100vh; padding-top: 5rem;">
    <div style="max-width: 75rem; margin: 0 auto; background-color: #2c2c38; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 0.5rem;">
        <h2 style="font-size: 1.5rem; font-weight: 600; color: white; margin-bottom: 1.5rem; text-align: right;">
            {{ $lang == 'ar' ? 'إضافة/تعديل الشروط والأحكام' : 'Add/Edit Terms and Conditions' }}
        </h2>

        @if (session('success'))
        <div style="background-color: #22c55e; color: white; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 1rem; text-align: right;">
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('terms.update', ['lang' => $lang]) }}" method="POST">
            @csrf
            <div style="margin-bottom: 1rem;">
                <label style="display: block; color: white; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem; text-align: right;" for="content_ar">
                    {{ $lang == 'ar' ? 'الشروط والأحكام (عربي)' : 'Terms and Conditions (Arabic)' }}
                </label>
                <textarea name="content_ar" id="content_ar" rows="5" style="width: 97%; padding: 0.75rem; border-radius: 0.375rem; background-color: #21212d; color: white; border: 1px solid #2c2c38; font-size: 0.875rem; outline: none; transition: border-color 0.3s ease;" placeholder="{{ $lang == 'ar' ? 'أدخل الشروط والأحكام بالعربية' : 'Enter Terms and Conditions in Arabic' }}">{{ $terms ? $terms->content_ar : '' }}</textarea>
            </div>

            <div style="margin-bottom: 1rem;">
                <label style="display: block; color: white; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.5rem; text-align: right;" for="content_en">
                    {{ $lang == 'ar' ? 'الشروط والأحكام (إنجليزي)' : 'Terms and Conditions (English)' }}
                </label>
                <textarea name="content_en" id="content_en" rows="5" style="width: 97%; padding: 0.75rem; border-radius: 0.375rem; background-color: #21212d; color: white; border: 1px solid #2c2c38; font-size: 0.875rem; outline: none; transition: border-color 0.3s ease;" placeholder="{{ $lang == 'ar' ? 'أدخل الشروط والأحكام بالإنجليزية' : 'Enter Terms and Conditions in English' }}">{{ $terms ? $terms->content_en : '' }}</textarea>
            </div>

            <div style="text-align: right;">
                <button type="submit" style="padding: 0.5rem 1rem; background-color: #2563eb; color: white; border-radius: 0.375rem; border: none; cursor: pointer; transition: background-color 0.3s ease;">
                    {{ $lang == 'ar' ? 'حفظ' : 'Save' }}
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
