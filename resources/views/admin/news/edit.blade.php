@extends('layouts.adminLayout')

@section('title', 'تعديل الخبر')

@section('content')
<div style="padding: 1rem; padding-top: 5rem;">
    <div style="max-width: 48rem; margin-bottom: 5rem; margin-left: auto; margin-right: auto; background-color: #2c2c38; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 0.5rem;">
        <h2 style="font-size: 1.5rem; font-weight: 600; text-align: center; color: white; margin-bottom: 1rem;">تعديل الخبر: {{ $news->title_ar }} 📰</h2>

        @if ($errors->any())
        <div style="background-color: #ef4444; color: white; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 1rem;">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('news.update', ['lang' => $lang, 'news' => $news->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- عنوان الخبر -->
            <div style="display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; color: white; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">عنوان الخبر (بالعربية)</label>
                    <input type="text" name="title_ar" value="{{ old('title_ar', $news->title_ar) }}" required 
                        style="text-align: right; background-color: #21212d; color: white; width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
                </div>
                <div>
                    <label style="display: block; color: white; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">عنوان الخبر (بالإنجليزية)</label>
                    <input type="text" name="title_en" value="{{ old('title_en', $news->title_en) }}" required 
                        style="background-color: #21212d; color: white; width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
                </div>
            </div>

            <!-- وصف الخبر -->
            <div style="display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; color: white; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">وصف الخبر (بالعربية)</label>
                    <textarea name="description_ar" rows="4" 
                        style="text-align: right; background-color: #21212d; color: white; width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">{{ old('description_ar', $news->description_ar) }}</textarea>
                </div>
                <div>
                    <label style="display: block; color: white; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">وصف الخبر (بالإنجليزية)</label>
                    <textarea name="description_en" rows="4" 
                        style="background-color: #21212d; color: white; width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">{{ old('description_en', $news->description_en) }}</textarea>
                </div>
            </div>

            <!-- صورة الخبر -->
            <div style="margin-bottom: 1rem;">
                <label style="display: block; color: white; font-size: 0.875rem; font-weight: 500; text-align: right; margin-bottom: 0.25rem;">صورة الخبر</label>
                @if($news->image_path)
                <div style="margin-bottom: 0.5rem; display: flex; align-items: center;">
                    <img src="{{ asset('storage/' . $news->image_path) }}" alt="{{ $news->title_ar }}" 
                        style="width: 6rem; height: 6rem; object-fit: cover; border-radius: 0.25rem; margin-left: 0.5rem;">
                    <span style="color: white; font-size: 0.875rem;">الصورة الحالية</span>
                </div>
                @endif
                <input type="file" name="image" accept="image/*" 
                    style="direction: rtl; text-align: right; color: white; width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
                <div style="color: #9ca3af; font-size: 0.75rem; margin-top: 0.25rem; text-align: right;">اترك هذا الحقل فارغًا إذا كنت لا ترغب في تغيير الصورة الحالية</div>
            </div>
            
            <!-- الصورة الخطط -->
<div style="margin-bottom: 1rem;">
    <label style="display: block; color: white; font-size: 0.875rem; font-weight: 500; text-align: right; margin-bottom: 0.25rem;">الصورة الخطط</label>
    @if($news->secondary_image)
    <div style="margin-bottom: 0.5rem; display: flex; align-items: center;">
        <img src="{{ asset('storage/' . $news->secondary_image) }}" alt="{{ $news->title_ar }}" 
            style="width: 6rem; height: 6rem; object-fit: cover; border-radius: 0.25rem; margin-left: 0.5rem;">
        <span style="color: white; font-size: 0.875rem;">الصورة الحالية</span>
    </div>
    @endif
    <input type="file" name="secondary_image" accept="image/*" 
        style="direction: rtl; text-align: right; color: white; width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
    <div style="color: #9ca3af; font-size: 0.75rem; margin-top: 0.25rem; text-align: right;">اترك هذا الحقل فارغًا إذا كنت لا ترغب في تغيير الصورة الحالية</div>
</div>


            <!-- معلومات إضافية -->
            <div style="display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; color: white; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">الكاتب</label>
                    <input type="text" name="author" value="{{ old('author', $news->author) }}" 
                        style="text-align: right; background-color: #21212d; color: white; width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
                </div>
                <div>
                    <label style="display: block; color: white; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">عدد التعليقات</label>
                    <input type="number" name="comments_count" value="{{ old('comments_count', $news->comments_count) }}" min="0" 
                        style="text-align: right; background-color: #21212d; color: white; width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
                </div>
            </div>

            <!-- زر الحفظ -->
            <div style="text-align: center; margin-top: 1.5rem;">
                <button type="submit" 
                    style="padding: 0.5rem 1.5rem; background-color: #16a34a; color: white; font-weight: 600; border-radius: 0.5rem; border: none; cursor: pointer;">
                    💾 حفظ التعديلات
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