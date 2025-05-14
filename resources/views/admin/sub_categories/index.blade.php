@extends('layouts.adminLayout')

@section('title', 'إدارة الخطط')

@section('content')
<div style="padding: 1rem; min-height: 100vh; padding-top: 5rem;">
    <div style="max-width: 72rem; margin-left: auto; margin-right: auto; background-color: #2c2c38; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 0.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.5rem; font-weight: 600; color: white;">قائمة الخطط 🗂️</h2>
            <a href="{{ route('admin.sub_categories.create', ['lang' => request()->route('lang')]) }}" 
                style="padding: 0.5rem 1rem; background-color: #16a34a; color: white; border-radius: 0.5rem; text-decoration: none;">
                إضافة خطة جديدة
            </a>
            <style>
                a[href*='admin.sub_categories.create']:hover { background-color: #15803d; }
            </style>
        </div>

        @if (session('success'))
        <div style="background-color: #22c55e; color: white; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 1rem; text-align: right;">
            {{ session('success') }}
        </div>
        @endif

        <div style="overflow-x: auto;">
            <table style="min-width: 100%; background-color: #21212d; border-radius: 0.5rem; overflow: hidden;">
                <thead style="background-color: #2c2c38; color: white;">
                    <tr>
                        <th style="padding: 0.75rem 1rem; text-align: right;">#</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">الباقة</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">الاسم (عربي)</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">الاسم (إنجليزي)</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">السعر</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">المدة (أشهر)</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">الحالة</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">التحكم</th>
                    </tr>
                </thead>
                <tbody style="color: white;">
                    @forelse ($subCategories as $index => $subCategory)
                    <tr style="border-bottom: 1px solid #2c2c38;">
                        <td style="padding: 0.75rem 1rem; text-align: right;">{{ $index + 1 }}</td>
                        <td style="padding: 0.75rem 1rem; text-align: right;">{{ $subCategory->mainCategory->name_ar }}</td>
                        <td style="padding: 0.75rem 1rem; text-align: right;">{{ $subCategory->name_ar }}</td>
                        <td style="padding: 0.75rem 1rem; text-align: right;">{{ $subCategory->name_en }}</td>
                        <td style="padding: 0.75rem 1rem; text-align: right;">{{ $subCategory->price }}$</td>
<td style="padding: 0.75rem 1rem; text-align: right;">
    {{ number_format($subCategory->duration / 30, 0) }} أشهر
</td>                        <td style="padding: 0.75rem 1rem; text-align: right;">
                            {{ $subCategory->status ? 'فعال' : 'غير فعال' }}
                        </td>
                        <td style="padding: 0.75rem 1rem; text-align: right;">
                            <div style="display: flex; gap: 0.5rem; justify-content: flex-start;">
                                <a href="{{ route('admin.sub_categories.edit', ['lang' => request()->route('lang'), 'sub_category' => $subCategory]) }}"
                                    style="margin-left: 0.75rem; padding: 0.25rem 0.75rem; background-color: #eab308; color: white; border-radius: 0.25rem; text-decoration: none;">
                                    تعديل
                                </a>
                                <form class="delete-form" action="{{ route('admin.sub_categories.destroy', ['sub_category' => $subCategory, 'lang' => request()->route('lang')]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        style="font-family: 'cairo'; padding: 0.25rem 0.75rem; height: 100%; background-color: #dc2626; color: white; border-radius: 0.25rem; border: none; cursor: pointer;">
                                        حذف
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <style>
                        tr:hover { background-color: #2c2c38; }
                        a[href*='admin.sub_categories.edit']:hover { background-color: #ca8a04; }
                        button[type="submit"]:hover { background-color: #b91c1c; }
                    </style>
                    @empty
                    <tr>
                        <td colspan="8" style="padding: 1.5rem 0; text-align: center; color: #9ca3af;">لا توجد تصنيفات فرعية مضافة حتى الآن</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1rem;">
            {{-- {{ $subCategories->links() }} --}}
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.delete-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: 'لن تتمكن من استرجاع هذا التصنيف الفرعي بعد الحذف!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'نعم، احذفه!',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                    }
                });
            });
        });
    });
</script>
@endsection