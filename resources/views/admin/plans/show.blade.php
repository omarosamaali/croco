@extends('layouts.adminLayout')

@section('title', $lang == 'ar' ? 'عرض الخطة' : 'Show Plan')

@section('content')
<div style="padding: 1rem; min-height: 100vh; padding-top: 5rem;">
    <div style="max-width: 72rem; margin: 0 auto; background-color: #2c2c38; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 0.5rem;">
        <h2 style="font-size: 1.5rem; font-weight: 600; color: white; margin-bottom: 1.5rem;">
            {{ $lang == 'ar' ? 'تفاصيل الخطة' : 'Plan Details' }}
        </h2>

        <div style="display: grid; gap: 1rem;">
            <div>
                <label style="color: white; font-weight: 600;">{{ $lang == 'ar' ? 'العنوان (عربي)' : 'Title (Arabic)' }}</label>
                <p style="color: white;">{{ $plan->title_ar }}</p>
            </div>
            <div>
                <label style="color: white; font-weight: 600;">{{ $lang == 'ar' ? 'العنوان (إنجليزي)' : 'Title (English)' }}</label>
                <p style="color: white;">{{ $plan->title_en }}</p>
            </div>
            <div>
                <label style="color: white; font-weight: 600;">{{ $lang == 'ar' ? 'السعر' : 'Price' }}</label>
                <p style="color: white;">{{ $plan->price }}</p>
            </div>
            <div>
                <label style="color: white; font-weight: 600;">{{ $lang == 'ar' ? 'الصورة' : 'Image' }}</label>
                <img src="{{ $plan->image }}" alt="{{ $lang == 'ar' ? $plan->title_ar : $plan->title_en }}" style="max-width: 100%; border-radius: 0.5rem;">
            </div>
            <div>
                <label style="color: white; font-weight: 600;">{{ $lang == 'ar' ? 'الوصف (عربي)' : 'Description (Arabic)' }}</label>
                <ul style="color: white;">
                    @foreach ($plan->description_ar as $desc)
                    <li>{{ $desc }}</li>
                    @endforeach
                </ul>
            </div>
            <div>
                <label style="color: white; font-weight: 600;">{{ $lang == 'ar' ? 'الوصف (إنجليزي)' : 'Description (English)' }}</label>
                <ul style="color: white;">
                    @foreach ($plan->description_en as $desc)
                    <li>{{ $desc }}</li>
                    @endforeach
                </ul>
            </div>
            <div>
                <label style="color: white; font-weight: 600;">{{ $lang == 'ar' ? 'الترتيب' : 'Order' }}</label>
                <p style="color: white;">{{ $plan->order }}</p>
            </div>
            <div>
                <label style="color: white; font-weight: 600;">{{ $lang == 'ar' ? 'الحالة' : 'Status' }}</label>
                <p style="color: white;">{{ $plan->is_active ? ($lang == 'ar' ? 'نشط' : 'Active') : ($lang == 'ar' ? 'غير نشط' : 'Inactive') }}</p>
            </div>
            <div>
                <a href="{{ route('plans.index', ['lang' => $lang]) }}" style="padding: 0.5rem 1rem; background-color: #16a34a; color: white; border-radius: 0.5rem; text-decoration: none;">
                    {{ $lang == 'ar' ? 'رجوع' : 'Back' }}
                </a>
            </div>
        </div>
    </div>
</div>
@endsection