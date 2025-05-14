@extends('layouts.adminLayout')

@section('title', $lang == 'ar' ? 'إضافة خطة جديدة' : 'Add New Plan')

@section('content')
<div style="padding: 1rem; min-height: 100vh; padding-top: 5rem;">
    <div style="max-width: 72rem; margin: 0 auto; background-color: #2c2c38; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 0.5rem;">
        <h2 style="font-size: 1.5rem; font-weight: 600; color: white; margin-bottom: 1.5rem;">
            {{ $lang == 'ar' ? 'إضافة خطة جديدة' : 'Add New Plan' }}
        </h2>

        <form action="{{ route('plans.store', ['lang' => $lang]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div style="display: grid; gap: 1rem;">
                <div>
                    <label style="color: white; font-weight: 600;">{{ $lang == 'ar' ? 'العنوان (عربي)' : 'Title (Arabic)' }}</label>
                    <input type="text" name="title_ar" value="{{ old('title_ar') }}" required style="width: 100%; padding: 0.5rem; border-radius: 0.5rem; background-color: #21212d; color: white; border: 1px solid #4b4b5e;">
                    @error('title_ar')
                    <span style="color: #dc2626;">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label style="color: white; font-weight: 600;">{{ $lang == 'ar' ? 'العنوان (إنجليزي)' : 'Title (English)' }}</label>
                    <input type="text" name="title_en" value="{{ old('title_en') }}" required style="width: 100%; padding: 0.5rem; border-radius: 0.5rem; background-color: #21212d; color: white; border: 1px solid #4b4b5e;">
                    @error('title_en')
                    <span style="color: #dc2626;">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label style="color: white; font-weight: 600;">{{ $lang == 'ar' ? 'السعر' : 'Price' }}</label>
                    <input type="text" name="price" value="{{ old('price') }}" required style="width: 100%; padding: 0.5rem; border-radius: 0.5rem; background-color: #21212d; color: white; border: 1px solid #4b4b5e;">
                    @error('price')
                    <span style="color: #dc2626;">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label style="color: white; font-weight: 600;">{{ $lang == 'ar' ? 'الصورة' : 'Image' }}</label>
                    <input type="file" name="image" required style="width: 100%; padding: 0.5rem; border-radius: 0.5rem; background-color: #21212d; color: white; border: 1px solid #4b4b5e;" accept="image/*">
                    @error('image')
                    <span style="color: #dc2626;">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label style="color: white; font-weight: 600;">{{ $lang == 'ar' ? 'اختر الخطط المتاحة' : 'Subcategories' }}</label>
                    <select name="sub_categories[]" multiple required style="width: 100%; padding: 0.5rem; border-radius: 0.5rem; background-color: #21212d; color: white; border: 1px solid #4b4b5e; height: 150px;">
                        @foreach ($subCategories as $subCategory)
                            <option value="{{ $subCategory->id }}" {{ in_array($subCategory->id, old('sub_categories', [])) ? 'selected' : '' }}>
                                {{ $lang == 'ar' ? $subCategory->name_ar : $subCategory->name_en }}
                            </option>
                        @endforeach
                    </select>
                    @error('sub_categories')
                    <span style="color: #dc2626;">{{ $message }}</span>
                    @enderror
                    <p style="color: #a0a0a0; font-size: 0.875rem; margin-top: 0.25rem;">
                        {{ $lang == 'ar' ? 'اضغط على Ctrl أو Cmd لاختيار أكثر من تصنيف' : 'Hold Ctrl or Cmd to select multiple subcategories' }}
                    </p>
                </div>
                <div>
                    <label style="color: white; font-weight: 600;">{{ $lang == 'ar' ? 'الوصف (عربي)' : 'Description (Arabic)' }}</label>
                    <div id="description_ar_container">
                        @foreach (old('description_ar', ['']) as $description)
                            <input type="text" name="description_ar[]" value="{{ $description }}" required style="width: 100%; padding: 0.5rem; border-radius: 0.5rem; background-color: #21212d; color: white; border: 1px solid #4b4b5e; margin-bottom: 0.5rem;">
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
                        @foreach (old('description_en', ['']) as $description)
                            <input type="text" name="description_en[]" value="{{ $description }}" required style="width: 100%; padding: 0.5rem; border-radius: 0.5rem; background-color: #21212d; color: white; border: 1px solid #4b4b5e; margin-bottom: 0.5rem;">
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
                    <input type="number" name="order" value="{{ old('order', 0) }}" required style="width: 100%; padding: 0.5rem; border-radius: 0.5rem; background-color: #21212d; color: white; border: 1px solid #4b4b5e;">
                    @error('order')
                    <span style="color: #dc2626;">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label style="color: white; font-weight: 600;">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active') ? 'checked' : '' }}>
                        {{ $lang == 'ar' ? 'نشط' : 'Active' }}
                    </label>
                </div>
                <div>
                    <button type="submit" style="padding: 0.5rem 1rem; background-color: #16a34a; color: white; border-radius: 0.5rem; border: none; cursor: pointer;">
                        {{ $lang == 'ar' ? 'إضافة الخطة' : 'Add Plan' }}
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