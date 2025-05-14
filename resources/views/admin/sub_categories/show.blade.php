@extends('layouts.adminLayout')

@section('title', 'عرض تصنيف فرعي')

@section('content')
<div style="padding: 1rem; min-height: 100vh; padding-top: 5rem;">
    <div style="max-width: 72rem; margin-left: auto; margin-right: auto; background-color: #2c2c38; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 0.5rem;">
        <h2 style="font-size: 1.5rem; font-weight: 600; color: white; margin-bottom: 1.5rem;">تفاصيل التصنيف الفرعي</h2>

        <div style="display: grid; gap: 1rem; color: white;">
            <div>
                <strong>الباقة:</strong> {{ $subCategory->mainCategory->name_ar }}
            </div>
            <div>
                <strong>الاسم (عربي):</strong> {{ $subCategory->name_ar }}
            </div>
            <div>
                <strong>الاسم (إنجليزي):</strong> {{ $subCategory->name_en }}
            </div>
            <div>
                <strong>السعر:</strong> {{ $subCategory->price }}
            </div>
            <div>
                <strong>المدة (أشهر):</strong> {{ $subCategory->duration }}
            </div>
            <div>
                <strong>الحالة:</strong> {{ $subCategory->status ? 'فعال' : 'غير فعال' }}
            </div>
            <div>
                <a href="{{ route('admin.sub_categories.edit', ['lang' => request()->route('lang'), 'sub_category' => $subCategory]) }}"
                    style="padding: 0.5rem 1rem; background-color: #eab308; color: white; border-radius: 0.5rem; text-decoration: none;">
                    تعديل
                </a>
                <a href="{{ route('admin.sub_categories.index', ['lang' => request()->route('lang')]) }}"
                    style="padding: 0.5rem 1rem; background-color: #6b7280; color: white; border-radius: 0.5rem; text-decoration: none; margin-left: 0.5rem;">
                    العودة إلى القائمة
                </a>
            </div>
        </div>
    </div>
</div>
@endsection