@extends('layouts.adminLayout')

@section('title', 'تعديل تصنيف فرعي')

@section('content')
<div style="padding: 1rem; min-height: 100vh; padding-top: 5rem;">
    <div style="max-width: 72rem; margin-left: auto; margin-right: auto; background-color: #2c2c38; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 0.5rem;">
        <h2 style="font-size: 1.5rem; font-weight: 600; color: white; margin-bottom: 1.5rem;">تعديل تصنيف فرعي</h2>

        @if ($errors->any())
        <div style="background-color: #ef4444; color: white; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 1rem; text-align: right;">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('admin.sub_categories.update', ['lang' => request()->route('lang'), 'sub_category' => $subCategory]) }}" method="POST">
            @csrf
            @method('PUT')
            <div style="display: grid; gap: 1rem;">
                <div>
                    <label style="color: white; font-weight: 500; margin-bottom: 0.5rem; display: block;">الباقة</label>
                    <select name="main_category_id" style="font-family: 'cairo';  width: 100%; padding: 0.5rem; background-color: #21212d; color: white; border: 1px solid #2c2c38; border-radius: 0.5rem;">
                        <option value="">اختر الباقة</option>
                        @foreach ($mainCategories as $mainCategory)
                        <option value="{{ $mainCategory->id }}" {{ $subCategory->main_category_id == $mainCategory->id ? 'selected' : '' }}>
                            {{ $mainCategory->name_ar }}
                        </option>
                        @endforeach
                    </select>
                    @error('main_category_id')
                    <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label style="color: white; font-weight: 500; margin-bottom: 0.5rem; display: block;">الاسم (عربي)</label>
                    <input type="text" name="name_ar" value="{{ old('name_ar', $subCategory->name_ar) }}" style="font-family: 'cairo'; width: 98.4%; padding: 0.5rem; background-color: #21212d; color: white; border: 1px solid #2c2c38; border-radius: 0.5rem;">
                    @error('name_ar')
                    <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label style="color: white; font-weight: 500; margin-bottom: 0.5rem; display: block;">الاسم (إنجليزي)</label>
                    <input type="text" name="name_en" value="{{ old('name_en', $subCategory->name_en) }}" style="font-family: 'cairo'; width: 98.4%; padding: 0.5rem; background-color: #21212d; color: white; border: 1px solid #2c2c38; border-radius: 0.5rem;">
                    @error('name_en')
                    <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label style="color: white; font-weight: 500; margin-bottom: 0.5rem; display: block;">السعر</label>
                    <input type="number" name="price" value="{{ old('price', $subCategory->price) }}" step="0.01" 
                    style="font-family: 'cairo'; width: 98.4%; padding: 0.5rem; background-color: #21212d; color: white; border: 1px solid #2c2c38; border-radius: 0.5rem;">
                    @error('price')
                    <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label style="color: white; font-weight: 500; margin-bottom: 0.5rem; display: block;">المدة (أشهر)</label>
                    <select name="duration" style="font-family: 'cairo'; width: 100%; padding: 0.5rem; background-color: #21212d; color: white; border: 1px solid #2c2c38; border-radius: 0.5rem;">
                        <option value="">اختر المدة</option>
                        <option value="30" {{ old('duration', $subCategory->duration) == '30' ? 'selected' : '' }}>1 شهر</option>
                        <option value="90" {{ old('duration', $subCategory->duration) == '90' ? 'selected' : '' }}>3 أشهر</option>
                        <option value="180" {{ old('duration', $subCategory->duration) == '180' ? 'selected' : '' }}>6 أشهر</option>
                        <option value="365" {{ old('duration', $subCategory->duration) == '365' ? 'selected' : '' }}>12 شهر</option>
                    </select>
                    @error('duration')
                    <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label style="color: white; font-weight: 500; margin-bottom: 0.5rem; display: block;">الحالة</label>
                    <select name="status" style="font-family: 'cairo'; width: 100%; padding: 0.5rem; background-color: #21212d; color: white; border: 1px solid #2c2c38; border-radius: 0.5rem;">
                        <option value="1" {{ old('status', $subCategory->status) ? 'selected' : '' }}>فعال</option>
                        <option value="0" {{ old('status', $subCategory->status) ? '' : 'selected' }}>غير فعال</option>
                    </select>
                    @error('status')
                    <span style="color: #ef4444; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <button type="submit" style="font-family: 'cairo'; padding: 0.5rem 1rem; background-color: #16a34a; color: white; border-radius: 0.5rem; border: none; cursor: pointer;">
                        تحديث التصنيف الفرعي
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection