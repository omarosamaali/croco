@extends('layouts.adminLayout')

@section('title', $lang == 'ar' ? 'عرض الشروط والأحكام' : 'View Terms and Conditions')

@section('content')
<div style="padding: 1rem; min-height: 100vh; padding-top: 5rem;">
    <div style="max-width: 75rem; margin: 0 auto; background-color: #2c2c38; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 0.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.5rem; font-weight: 600; color: white; text-align: right;">
                {{ $lang == 'ar' ? 'عرض الشروط والأحكام' : 'View Terms and Conditions' }}
            </h2>
            <a href="{{ route('terms.create', ['lang' => $lang]) }}" 
               style="padding: 0.5rem 1rem; background-color: #2563eb; color: white; border-radius: 0.375rem; text-decoration: none; transition: background-color 0.3s ease;">
                {{ $lang == 'ar' ? 'تعديل' : 'Edit' }}
            </a>
        </div>

        <div style="background-color: #21212d; padding: 1.5rem; border-radius: 0.5rem; color: white;">
            @if($terms)
                <div style="margin-bottom: 1rem;">
                    <h3 style="font-size: 1.125rem; font-weight: 600; text-align: right;">{{ $lang == 'ar' ? 'المحتوى بالعربية' : 'Content in Arabic' }}</h3>
                    <div style="text-align: right;">{!! $terms->content_ar !!}</div>
                </div>
                <div style="margin-bottom: 1rem;">
                    <h3 style="font-size: 1.125rem; font-weight: 600; text-align: right;">{{ $lang == 'ar' ? 'المحتوى بالإنجليزية' : 'Content in English' }}</h3>
                    <div style="text-align: right;">{!! $terms->content_en !!}</div>
                </div>
            @else
                <p style="text-align: center; color: #9ca3af;">
                    {{ $lang == 'ar' ? 'لم يتم تحديد الشروط والأحكام بعد' : 'Terms and Conditions have not been set yet' }}
                </p>
            @endif
        </div>
    </div>
</div>
@endsection
