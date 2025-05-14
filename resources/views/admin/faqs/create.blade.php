@extends('layouts.adminLayout')

@section('title', 'إضافة سؤال جديد')

@section('content')
<div style="padding: 1rem; padding-top: 5rem;">
    <div style="max-width: 48rem; margin: 0 auto; background-color: #2c2c38; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 0.5rem; margin-bottom: 5rem;">
        <h2 style="font-size: 1.5rem; font-weight: 600; text-align: center; color: #c9c8dc; margin-bottom: 1rem;">
            {{ $lang == 'ar' ? 'إضافة سؤال جديد ❓' : 'Add New FAQ ❓' }}
        </h2>

        @if ($errors->any())
        <div style="background-color: #ef4444; color: #c9c8dc; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 1rem;">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('faqs.store', ['lang' => $lang]) }}" method="POST">
            @csrf

            <!-- السؤال -->
            <div style="display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">
                        {{ $lang == 'ar' ? 'السؤال (بالعربية)' : 'Question (Arabic)' }}
                    </label>
                    <input type="text" name="question_ar" value="{{ old('question_ar') }}" required style="text-align: right; background-color: #21212d; color: #c9c8dc; width: 100%; padding: 0.5rem; border: 1px solid #4b4b55; border-radius: 0.375rem;">
                </div>
                <div>
                    <label style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">
                        {{ $lang == 'ar' ? 'السؤال (بالإنجليزية)' : 'Question (English)' }}
                    </label>
                    <input type="text" name="question_en" value="{{ old('question_en') }}" required style="background-color: #21212d; color: #c9c8dc; width: 100%; padding: 0.5rem; border: 1px solid #4b4b55; border-radius: 0.375rem;">
                </div>
            </div>

            <!-- الإجابة -->
            <div style="display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">
                        {{ $lang == 'ar' ? 'الإجابة (بالعربية)' : 'Answer (Arabic)' }}
                    </label>
                    <textarea name="answer_ar" rows="5" required style="text-align: right; background-color: #21212d; color: #c9c8dc; width: 100%; padding: 0.5rem; border: 1px solid #4b4b55; border-radius: 0.375rem;">{{ old('answer_ar') }}</textarea>
                </div>
                <div>
                    <label style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">
                        {{ $lang == 'ar' ? 'الإجابة (بالإنجليزية)' : 'Answer (English)' }}
                    </label>
                    <textarea name="answer_en" rows="5" required style="background-color: #21212d; color: #c9c8dc; width: 100%; padding: 0.5rem; border: 1px solid #4b4b55; border-radius: 0.375rem;">{{ old('answer_en') }}</textarea>
                </div>
            </div>

            <!-- الترتيب والحالة -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div style="text-align: right;">
                    <label style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">
                        {{ $lang == 'ar' ? 'الترتيب' : 'Order' }}
                    </label>
                    <input type="number" name="order" value="{{ old('order', 0) }}" style="background-color: #21212d; color: #c9c8dc; width: 100%; padding: 0.5rem; border: 1px solid #4b4b55; border-radius: 0.375rem;" min="0">
                </div>
                <div style="text-align: right;">
                    <label style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">
                        {{ $lang == 'ar' ? 'الحالة' : 'Status' }}
                    </label>
                    <div style="display: flex; align-items: center; justify-content: flex-end;">
                        <input type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }} style="margin-left: 0.5rem;">
                        <label for="is_active" style="color: #c9c8dc; font-size: 0.875rem;">
                            {{ $lang == 'ar' ? 'نشط' : 'Active' }}
                        </label>
                    </div>
                </div>
            </div>

            <!-- الأزرار -->
            <div style="margin-top: 1.5rem; display: flex; justify-content: space-between; align-items: center;">
                <a href="{{ route('faqs.index', ['lang' => $lang]) }}" style="padding: 0.5rem 1rem; background-color: #4b4b55; color: white; border-radius: 0.375rem; text-decoration: none; transition: background-color 0.3s ease;">
                    {{ $lang == 'ar' ? 'العودة للقائمة' : 'Back to List' }}
                </a>
                <button type="submit" style="padding: 0.5rem 1rem; background-color: #16a34a; color: white; border-radius: 0.375rem; border: none; cursor: pointer; transition: background-color 0.3s ease;">
                    {{ $lang == 'ar' ? 'إضافة السؤال' : 'Add FAQ' }}
                </button>
            </div>
        </form>
    </div>
</div>
<script>
    document.querySelectorAll('a, button').forEach(element => {
        element.addEventListener('mouseover', () => {
            element.style.backgroundColor = element.style.backgroundColor === 'rgb(16, 163, 74)' ? '#15803d' : '#4b4b55';
        });
        element.addEventListener('mouseout', () => {
            element.style.backgroundColor = element.style.backgroundColor === '#15803d' ? '#16a34a' : '#4b4b55';
        });
    });
</script>
@endsection
