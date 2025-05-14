@extends('layouts.adminLayout')

@section('title', 'عرض التصنيف')

@section('content')
    <div style="padding: 1rem; padding-top: 5rem;">
        <div style="max-width: 48rem; margin-bottom: 5rem; margin-left: auto; margin-right: auto; background-color: #2c2c38; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 0.5rem;">
            <h2 style="font-size: 1.5rem; font-weight: 600; text-align: center; color: #c9c8dc; margin-bottom: 1rem;">تفاصيل التصنيف</h2>
            
            <div style="margin-bottom: 1rem;">
                <label style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">الاسم (عربي)</label>
                <p style="background-color: #21212d; color: #c9c8dc; width: 98%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem; text-align: right;">
                    {{ $mainCategory->name_ar }}
                </p>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">الاسم (إنجليزي)</label>
                <p style="background-color: #21212d; color: #c9c8dc; width: 98%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
                    {{ $mainCategory->name_en }}
                </p>
            </div>
            <div style="margin-bottom: 1rem;">
                <label style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">الحالة</label>
                <p style="background-color: #21212d; color: #c9c8dc; width: 98%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem; text-align: right;">
                    {{ $mainCategory->status ? 'فعال' : 'غير فعال' }}
                </p>
            </div>
            <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end;">
                <a href="{{ route('admin.main_categories.index') }}" 
                    style="padding: 0.5rem 1rem; background-color: #2563eb; color: white; border-radius: 0.5rem; text-decoration: none;">
                    العودة إلى القائمة
                </a>
            </div>
        </div>
    </div>
@endsection