@extends('layouts.adminLayout')

@section('title', 'إضافة محتوى من نحن')

@section('content')
<div style="padding: 1rem; padding-top: 5rem;">
    <div style="max-width: 48rem; margin-bottom: 5rem; margin-left: auto; margin-right: auto; background-color: #2c2c38; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 0.5rem;">
        <h2 style="font-size: 1.5rem; font-weight: 600; text-align: center; color: white; margin-bottom: 1rem;">إضافة محتوى من نحن 📋</h2>

        @if ($errors->any())
        <div style="background-color: #ef4444; color: white; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 1rem;">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('know-us.store', ['lang' => $lang]) }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- العنوان -->
            <div style="display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; color: white; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">العنوان (بالعربية)</label>
                    <input type="text" name="title_ar" value="{{ old('title_ar') }}" required 
                        style="text-align: right; background-color: #21212d; color: white; width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
                </div>
                <div>
                    <label style="display: block; color: white; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">العنوان (بالإنجليزية)</label>
                    <input type="text" name="title_en" value="{{ old('title_en') }}" required 
                        style="background-color: #21212d; color: white; width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
                </div>
            </div>

            <!-- الوصف -->
            <div style="display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; color: white; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">الوصف (بالعربية)</label>
                    <textarea name="description_ar" rows="4" 
                        style="text-align: right; background-color: #21212d; color: white; width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">{{ old('description_ar') }}</textarea>
                </div>
                <div>
                    <label style="display: block; color: white; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">الوصف (بالإنجليزية)</label>
                    <textarea name="description_en" rows="4" 
                        style="background-color: #21212d; color: white; width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">{{ old('description_en') }}</textarea>
                </div>
            </div>

            <!-- صورة -->
            <div style="margin-bottom: 1rem;">
                <label style="display: block; color: white; font-size: 0.875rem; font-weight: 500; text-align: right; margin-bottom: 0.25rem;">صورة</label>
                <input type="file" name="image" accept="image/*" 
                    style="direction: rtl; text-align: right; color: white; width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
            </div>

            <!-- معلومات إضافية -->
            <div style="display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; color: white; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">الكاتب</label>
                    <input type="text" name="author" value="{{ old('author', 'Admin') }}" 
                        style="text-align: right; background-color: #21212d; color: white; width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
                </div>
                <div>
                    <label style="display: block; color: white; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">عدد التعليقات</label>
                    <input type="number" name="comments_count" value="{{ old('comments_count', 0) }}" min="0" 
                        style="text-align: right; background-color: #21212d; color: white; width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
                </div>
            </div>

            <!-- زر الحفظ -->
            <div style="text-align: center; margin-top: 1.5rem;">
                <button type="submit" 
                    style="padding: 0.5rem 1.5rem; background-color: #16a34a; color: white; font-weight: 600; border-radius: 0.5rem; border: none; cursor: pointer;">
                    💾 حفظ المحتوى
                </button>
            </div>

            <style>
                @media (min-width: 768px) {
                    div[style*="grid-template-columns: 1fr"] { grid-template-columns: 1fr 1fr; }
                }
                button[type="submit"]:hover { background-color: #15803d; }
            </style>
        </form>
    </div>
</div>
@endsection