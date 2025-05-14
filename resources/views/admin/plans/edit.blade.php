@extends('layouts.adminLayout')

@section('title', $lang == 'ar' ? 'تعديل الخطة' : 'Edit Plan')

@section('content')
<div style="padding: 1rem; min-height: 100vh; padding-top: 5rem;">
    <div style="max-width: 72rem; margin: 0 auto; background-color: #2c2c38; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 0.5rem;">
        <h2 style="font-size: 1.5rem; font-weight: 600; color: white; margin-bottom: 1.5rem;">
            {{ $lang == 'ar' ? 'تعديل الخطة' : 'Edit Plan' }}
        </h2>

        <form action="{{ route('admin.plans.update', ['lang' => $lang, 'plan' => $plan->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div style="display: grid; gap: 1rem;">
                <div>
                    <label style="color: white; font-weight: 600;">{{ $lang == 'ar' ? 'العنوان (عربي)' : 'Title (Arabic)' }}</label>
                    <input type="text" name="title_ar" value="{{ old('title_ar', $plan->title_ar) }}" required style="width: 100%; padding: 0.5rem; border-radius: 0.5rem; background-color: #21212d; color: white; border: 1px solid #4b4b5e;">
                    @error('title_ar')
                    <span style="color: #dc2626;">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label style="color: white; font-weight: 600;">{{ $lang == 'ar' ? 'العنوان (إنجليزي)' : 'Title (English)' }}</label>
                    <input type="text" name="title_en" value="{{ old('title_en', $plan->title_en) }}" required style="width: 100%; padding: 0.5rem; border-radius: 0.5rem; background-color: #21212d; color: white; border: 1px solid #4b4b5e;">
                    @error('title_en')
                    <span style="color: #dc2626;">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label style="color: white; font-weight: 600;">{{ $lang == 'ar' ? 'السعر' : 'Price' }}</label>
                    <input type="text" name="price" value="{{ old('price', $plan->price) }}" required style="width: 100%; padding: 0.5rem; border-radius: 0.5rem; background-color: #21212d; color: white; border: 1px solid #4b4b5e;">
                    @error('price')
                    <span style="color: #dc2626;">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label style="color: white; font-weight: 600;">{{ $lang == 'ar' ? 'الصورة الحالية' : 'Current Image' }}</label>
                    @if ($plan->image)
                        <img src="{{ asset('storage/' . $plan->image) }}" alt="{{ $lang == 'ar' ? $plan->title_ar : $plan->title_en }}" style="max-width: 200px; border-radius: 0.5rem; margin-bottom: 0.5rem;">
                    @else
                        <p style="color: #9ca3af;">{{ $lang == 'ar' ? 'لا توجد صورة' : 'No image available' }}</p>
                    @endif
                </div>
                <div>
                    <label style="color: white; font-weight: 600;">{{ $lang == 'ar' ? 'رفع صورة جديدة (اختياري)' : 'Upload New Image (Optional)' }}</label>
                    <input type="file" name="image" style="width: 100%; padding: 0.5rem; border-radius: 0.5rem; background-color: #21212d; color: white; border: 1px solid #4b4b5e;" accept="image/*">
                    @error('image')
                    <span style="color: #dc2626;">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label style="color: white; font-weight: 600;">{{ $lang == 'ar' ? 'الوصف (عربي)' : 'Description (Arabic)' }}</label>
                    <div id="description_ar_container">
                        @foreach ($plan->description_ar as $desc)
                        <input type="text" name="description_ar[]" value="{{ $desc }}" required style="width: 100%; padding: 0.5rem; border-radius: 0.5rem; background-color: #21212d; color: white; border: 1px solid #4b4b5e; margin-bottom: 0.5rem;">
                        @endforeach
                    </div>
                    <button type="button" onclick="addDescriptionField('description_ar')" style="padding: 0.5rem; background-color: #16a34a; color: white; border-radius: 0.5rem; border: none; cursor: pointer;">
                        {{ $lang == 'ar' ? 'إضافة وصف آخر' : 'Add Another Description' }}
                    </button>
                    @error('description_ar')
                    <span style="color: #dc2626;">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label style="color: white; font-weight: 600;">{{ $lang == 'ar' ? 'الوصف (إنجليزي)' : 'Description (English)' }}</label>
                    <div id="description_en_container">
                        @foreach ($plan->description_en as $desc)
                        <input type="text" name="description_en[]" value="{{ $desc }}" required style="width: 100%; padding: 0.5rem; border-radius: 0.5rem; background-color: #21212d; color: white; border: 1px solid #4b4b5e; margin-bottom: 0.5rem;">
                        @endforeach
                    </div>
                    <button type="button" onclick="addDescriptionField('description_en')" style="padding: 0.5rem; background-color: #16a34a; color: white; border-radius: 0.5rem; border: none; cursor: pointer;">
                        {{ $lang == 'ar' ? 'إضافة وصف آخر' : 'Add Another Description' }}
                    </button>
                    @error('description_en')
                    <span style="color: #dc2626;">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label style="color: white; font-weight: 600;">{{ $lang == 'ar' ? 'الترتيب' : 'Order' }}</label>
                    <input type="number" name="order" value="{{ old('order', $plan->order) }}" required style="width: 100%; padding: 0.5rem; border-radius: 0.5rem; background-color: #21212d; color: white; border: 1px solid #4b4b5e;">
                    @error('order')
                    <span style="color: #dc2626;">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label style="color: white; font-weight: 600;">
                        <input type="checkbox" name="is_active" value="1" {{ $plan->is_active ? 'checked' : '' }}>
                        {{ $lang == 'ar' ? 'نشط' : 'Active' }}
                    </label>
                </div>
                <div>
                    <button type="submit" style="padding: 0.5rem 1rem; background-color: #16a34a; color: white; border-radius: 0.5rem; border: none; cursor: pointer;">
                        {{ $lang == 'ar' ? 'تحديث الخطة' : 'Update Plan' }}
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    function addDescriptionField(container) {
        const containerElement = document.getElementById(`${container}_container`);
        const inputs = containerElement.querySelectorAll('input');
        if (inputs.length >= 7) {
            alert('{{ $lang == "ar" ? "لا يمكن إضافة أكثر من 7 أوصاف" : "Cannot add more than 7 descriptions" }}');
            return;
        }
        const newInput = document.createElement('input');
        newInput.type = 'text';
        newInput.name = `${container}[]`;
        newInput.required = true;
        newInput.style = 'width: 100%; padding: 0.5rem; border-radius: 0.5rem; background-color: #21212d; color: white; border: 1px solid #4b4b5e; margin-bottom: 0.5rem;';
        containerElement.appendChild(newInput);
    }
</script>
@endsection
