@extends('layouts.adminLayout')

@section('title', 'إدارة التصنيفات')

@section('content')

    <div style="padding: 1rem; min-height: 100vh; padding-top: 5rem;">
        <div
            style="max-width: 72rem; margin-left: auto; margin-right: auto; background-color: #2c2c38; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 0.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h2 style="font-size: 1.5rem; font-weight: 600; color: white;">قائمة الباقات 🗂️</h2>
                <a href="{{ route('admin.main_categories.create', ['lang' => request()->route('lang')]) }}"
                    style="padding: 0.5rem 1rem; background-color: #16a34a; color: white; border-radius: 0.5rem; text-decoration: none;">
                    إضافة باقة جديدة
                </a>
                <style>
                    a[href*='admin.main_categories.create']:hover {
                        background-color: #15803d;
                    }
                </style>
            </div>

            @if (session('success'))
                <div
                    style="background-color: #22c55e; color: white; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 1rem; text-align: right;">
                    {{ session('success') }}
                </div>
            @endif

            <div style="overflow-x: auto;">
                <table style="min-width: 100%; background-color: #21212d; border-radius: 0.5rem; overflow: hidden;">
                    <thead style="background-color: #2c2c38; color: white;">
                        <tr>
                            <th style="padding: 0.75rem 1rem; text-align: right;">#</th>
                            <th style="padding: 0.75rem 1rem; text-align: right;">الصورة</th>
                            <th style="padding: 0.75rem 1rem; text-align: right;">الاسم (عربي)</th>
                            <th style="padding: 0.75rem 1rem; text-align: right;">الاسم (إنجليزي)</th>
                            <th style="padding: 0.75rem 1rem; text-align: right;">الحالة</th>
                            <th style="padding: 0.75rem 1rem; text-align: right;">التحكم</th>
                        </tr>
                    </thead>
                    <tbody style="color: white;">
                        @forelse ($categories as $index => $category)
                            <tr style="border-bottom: 1px solid #2c2c38;">
                                <td style="padding: 0.75rem 1rem; text-align: right;">{{ $index + 1 }}</td>
                                <td style="padding: 0.75rem 1rem; text-align: center;">
                                    @if($category->image)
                                        <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name_ar }}" 
                                             style="width: 50px; height: 50px; object-fit: cover; border-radius: 0.25rem;">
                                    @else
                                        <div style="width: 50px; height: 50px; background-color: #4b5563; border-radius: 0.25rem; display: flex; align-items: center; justify-content: center;">
                                            <span style="color: #9ca3af; font-size: 0.75rem;">لا توجد صورة</span>
                                        </div>
                                    @endif
                                </td>
                                <td style="padding: 0.75rem 1rem; text-align: right;">{{ $category->name_ar }}</td>
                                <td style="padding: 0.75rem 1rem; text-align: right;">{{ $category->name_en }}</td>
                                <td style="padding: 0.75rem 1rem; text-align: right;">
                                    {{ $category->status ? 'فعال' : 'غير فعال' }}
                                </td>
                                <td style="padding: 0.75rem 1rem; text-align: right;">
                                    <div style="display: flex; gap: 0.5rem; justify-content: flex-start;">

                                        <a href="{{ route('admin.main_categories.edit', ['lang' => request()->route('lang'), 'main_category' => $category]) }}"
                                            style="margin-left: 0.75rem; padding: 0.25rem 0.75rem; background-color: #eab308; color: white; border-radius: 0.25rem; text-decoration: none;">
                                            تعديل
                                        </a>
                                        <form class="delete-form"
                                            action="{{ route('admin.main_categories.destroy', ['main_category' => $category->id, 'lang' => request()->route('lang')]) }}"
                                            method="POST">
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
                                tr:hover {
                                    background-color: #2c2c38;
                                }

                                a[href*='admin.main_categories.edit']:hover {
                                    background-color: #ca8a04;
                                }

                                button[type="submit"]:hover {
                                    background-color: #b91c1c;
                                }
                            </style>
                        @empty
                            <tr>
                                <td colspan="6" style="padding: 1.5rem 0; text-align: center; color: #9ca3af;">لا توجد
                                    تصنيفات مضافة حتى الآن</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div style="margin-top: 1rem;">
                {{-- {{ $categories->links() }} --}}
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
                        text: 'لن تتمكن من استرجاع هذا التصنيف بعد الحذف!',
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