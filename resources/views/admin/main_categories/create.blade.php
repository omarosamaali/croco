<!-- في ملف resources/views/admin/main_categories/create.blade.php -->

@extends('layouts.adminLayout')

@section('title', 'إضافة باقة جديد')

@section('content')
    <div style="padding: 1rem; padding-top: 5rem;">
        <div style="max-width: 48rem; margin-bottom: 5rem; margin-left: auto; margin-right: auto; background-color: #2c2c38; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 0.5rem;">
            <h2 style="font-size: 1.5rem; font-weight: 600; text-align: center; color: #c9c8dc; margin-bottom: 1rem;">إضافة باقة جديد</h2>

            @if ($errors->any())
                <div style="background-color: #ef4444; color: #c9c8dc; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.main_categories.store', ['lang' => app()->getLocale()]) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">الاسم (عربي)</label>
                        <input type="text" name="name_ar" value="{{ old('name_ar') }}" required 
                            style="font-family: 'cairo'; text-align: right; background-color: #21212d; color: #c9c8dc; width: 97%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
                    </div>
                    <div>
                        <label style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">الاسم (إنجليزي)</label>
                        <input type="text" name="name_en" value="{{ old('name_en') }}" required 
                            style="font-family: 'cairo'; background-color: #21212d; color: #c9c8dc; width: 97%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
                    </div>
                </div>
                <div style="margin-bottom: 1rem;">
                    <label style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">الصورة</label>
                    <input type="file" name="image" accept="image/*" 
                        style="font-family: 'cairo'; background-color: #21212d; color: #c9c8dc; width: 97%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
                </div>
                <div style="margin-bottom: 1rem; text-align: right;">
                    <label style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">الحالة</label>
                    <select name="status" required 
                        style="font-family: 'cairo'; background-color: #21212d; color: #c9c8dc; width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem; direction: rtl;">
                        <option value="1" {{ old('status') == '1' ? 'selected' : '' }}>فعال</option>
                        <option value="0" {{ old('status') == '0' ? 'selected' : '' }}>غير فعال</option>
                    </select>
                </div>
                <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end;">
                    <button type="submit" 
                        style="font-family: 'cairo'; padding: 0.5rem 1rem; background-color: #16a34a; color: white; border-radius: 0.5rem; border: none; cursor: pointer;">
                        إضافة التصنيف
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection