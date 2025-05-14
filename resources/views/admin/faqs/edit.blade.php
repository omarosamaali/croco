@extends('layouts.adminLayout')

@section('title', 'تعديل سؤال')

@section('content')
<div style="padding: 20px; padding-top: 80px;">
    <div style="max-width: 768px; margin: 0 auto; background-color: #2c2c38; padding: 24px; box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.1); border-radius: 8px;">
        <h2 style="font-size: 1.5rem; font-weight: 600; text-align: center; color: white; margin-bottom: 16px;">
            {{ $lang == 'ar' ? 'تعديل السؤال ❓' : 'Edit FAQ ❓' }}
        </h2>

        @if ($errors->any())
        <div style="background-color: #f44336; color: white; padding: 12px; border-radius: 8px; margin-bottom: 16px;">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('faqs.update', ['lang' => $lang, 'faq' => $faq->id]) }}" method="POST">
            @csrf
            @method('PUT')

            <!-- السؤال -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; color: white; font-size: 0.875rem; font-weight: 500; margin-bottom: 8px; text-align: right;">
                        {{ $lang == 'ar' ? 'السؤال (بالعربية)' : 'Question (Arabic)' }}
                    </label>
                    <input type="text" name="question_ar" value="{{ old('question_ar', $faq->question_ar) }}" required style="text-align: right; background-color: #21212d; color: white; width: 100%; padding: 8px; border: 1px solid #4a4a4a; border-radius: 8px;">
                </div>
                <div>
                    <label style="display: block; color: white; font-size: 0.875rem; font-weight: 500; margin-bottom: 8px; text-align: right;">
                        {{ $lang == 'ar' ? 'السؤال (بالإنجليزية)' : 'Question (English)' }}
                    </label>
                    <input type="text" name="question_en" value="{{ old('question_en', $faq->question_en) }}" required style="background-color: #21212d; color: white; width: 100%; padding: 8px; border: 1px solid #4a4a4a; border-radius: 8px;">
                </div>
            </div>

            <!-- الإجابة -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div>
                    <label style="display: block; color: white; font-size: 0.875rem; font-weight: 500; margin-bottom: 8px; text-align: right;">
                        {{ $lang == 'ar' ? 'الإجابة (بالعربية)' : 'Answer (Arabic)' }}
                    </label>
                    <textarea name="answer_ar" rows="5" required style="text-align: right; background-color: #21212d; color: white; width: 100%; padding: 8px; border: 1px solid #4a4a4a; border-radius: 8px;">{{ old('answer_ar', $faq->answer_ar) }}</textarea>
                </div>
                <div>
                    <label style="display: block; color: white; font-size: 0.875rem; font-weight: 500; margin-bottom: 8px; text-align: right;">
                        {{ $lang == 'ar' ? 'الإجابة (بالإنجليزية)' : 'Answer (English)' }}
                    </label>
                    <textarea name="answer_en" rows="5" required style="background-color: #21212d; color: white; width: 100%; padding: 8px; border: 1px solid #4a4a4a; border-radius: 8px;">{{ old('answer_en', $faq->answer_en) }}</textarea>
                </div>
            </div>

            <!-- الترتيب والحالة -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                <div style="text-align: right;">
                    <label style="display: block; color: white; font-size: 0.875rem; font-weight: 500; margin-bottom: 8px;">
                        {{ $lang == 'ar' ? 'الترتيب' : 'Order' }}
                    </label>
                    <input type="number" name="order" value="{{ old('order', $faq->order) }}" style="background-color: #21212d; color: white; width: 100%; padding: 8px; border: 1px solid #4a4a4a; border-radius: 8px;" min="0">
                </div>
                <div style="text-align: right;">
                    <label style="display: block; color: white; font-size: 0.875rem; font-weight: 500; margin-bottom: 8px;">
                        {{ $lang == 'ar' ? 'الحالة' : 'Status' }}
                    </label>
                    <div style="display: flex; align-items: center; justify-content: flex-end;">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $faq->is_active) ? 'checked' : '' }} style="margin-left: 8px;">
                        <label for="is_active" style="color: white; font-size: 0.875rem;">
                            {{ $lang == 'ar' ? 'نشط' : 'Active' }}
                        </label>
                    </div>
                </div>
            </div>

            <!-- زر الحفظ -->
            <div style="text-align: center; margin-top: 24px; display: flex; justify-content: space-between; align-items: center;">
                <a href="{{ route('faqs.index', ['lang' => $lang]) }}" style="padding: 12px 24px; background-color: #4b4b4b; color: white; font-weight: 600; border-radius: 8px; text-decoration: none; transition: background-color 0.3s;">
                    {{ $lang == 'ar' ? 'العودة للقائمة' : 'Back to List' }}
                </a>
                <button type="submit" style="padding: 12px 24px; background-color: #38a169; color: white; font-weight: 600; border-radius: 8px; transition: background-color 0.3s;">
                    💾 {{ $lang == 'ar' ? 'حفظ التعديلات' : 'Save Changes' }}
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Inline CSS for Hover Effects -->
<style>
    a:hover {
        background-color: #6b7280;
    }

    button:hover {
        background-color: #48bb78;
    }
</style>
@endsection
