@extends('layouts.adminLayout')

@section('title', 'إنشاء سياسة الخصوصية')

@section('content')
<div style="padding: 16px; max-width: 768px; margin-bottom: 80px; margin: 0 auto; min-height: 100vh; padding-top: 80px;">

    <div style="max-width: 960px; margin: 0 auto; background-color: #2c2c38; padding: 24px; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 8px;">
        <h2 style="font-size: 1.25rem; font-weight: 600; color: white; margin-bottom: 24px;">إنشاء سياسة الخصوصية 📜</h2>

        <form action="{{ route('privacy-policy.store', ['lang' => $lang]) }}" method="POST">
            @csrf
            <div style="margin-bottom: 16px;">
                <label for="content_ar" style="display: block; color: white; text-align: right; margin-bottom: 8px;">المحتوى (عربي)</label>
                <textarea name="content_ar" id="content_ar" rows="5" style="width: 97%; padding: 12px; border-radius: 8px; background-color: #21212d; color: white; border: 1px solid #2c2c38; outline: none; transition: border-color 0.2s; focus:ring: 2px solid #2563eb;" required>{{ old('content_ar') }}</textarea>
                @error('content_ar')
                <p style="color: #ef4444; text-align: right; margin-top: 4px;">{{ $message }}</p>
                @enderror
            </div>

            <div style="margin-bottom: 16px;">
                <label for="content_en" style="display: block; color: white; text-align: right; margin-bottom: 8px;">المحتوى (إنجليزي)</label>
                <textarea name="content_en" id="content_en" rows="5" style="width: 97%; padding: 12px; border-radius: 8px; background-color: #21212d; color: white; border: 1px solid #2c2c38; outline: none; transition: border-color 0.2s; focus:ring: 2px solid #2563eb;" required>{{ old('content_en') }}</textarea>
                @error('content_en')
                <p style="color: #ef4444; text-align: right; margin-top: 4px;">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" style="padding: 8px 16px; background-color: #2563eb; color: white; border-radius: 8px; text-decoration: none; transition: background-color 0.3s;">
                إنشاء
            </button>
        </form>
    </div>
</div>
@endsection
