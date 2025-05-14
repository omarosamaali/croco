@extends('layouts.adminLayout')

@section('title', 'ادارة الواجهات')

@section('content')
<style>
    .container {
        padding: 16px;
        min-height: 100vh;
        padding-top: 80px;
    }

    .content-box {
        max-width: 960px;
        margin: auto;
        background: #2c2c38;
        padding: 24px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 24px;
    }

    .header h2 {
        font-size: 1.5rem;
        font-weight: 600;
        color: white;
    }

    .btn {
        padding: 8px 16px;
        border-radius: 8px;
        color: white;
        text-decoration: none;
    }

    .btn-green {
        background: #16a34a;
    }

    .btn-green:hover {
        background: #15803d;
    }

    .btn-yellow {
        background: #facc15;
        color: black;
    }

    .btn-yellow:hover {
        background: #eab308;
    }

    .btn-red {
        background: #dc2626;
    }

    .btn-red:hover {
        background: #b91c1c;
    }

    .alert {
        background: #16a34a;
        color: white;
        padding: 12px;
        border-radius: 8px;
        margin-bottom: 16px;
        text-align: right;
    }

    .table-container {
        overflow-x: auto;
    }

    .styled-table {
        width: 100%;
        background: #21212d;
        border-radius: 8px;
        overflow: hidden;
        color: white;
    }

    .styled-table thead {
        background: #2c2c38;
    }

    .styled-table th,
    .styled-table td {
        padding: 12px 16px;
        text-align: right;
    }

    .banner-image {
        width: 64px;
        height: 64px;
        object-fit: cover;
        border-radius: 8px;
    }

    .no-image {
        width: 64px;
        height: 64px;
        background: gray;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
    }

    .status {
        padding: 4px 8px;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 999px;
    }

    .status.active {
        background: #16a34a;
        color: white;
    }

    .status.inactive {
        background: #dc2626;
        color: white;
    }

    .actions {
        display: flex;
        gap: 8px;
    }

    .empty-table {
        text-align: center;
        color: #a1a1aa;
        padding: 24px;
    }
</style>

<div class="container">
    <div class="content-box">
        <div class="header">
            <h2>{{ $lang == 'ar' ? 'ادارة الواجهات 🖼️' : 'Manage Banners 🖼️' }}</h2>
            <a href="{{ route('banners.create', ['lang' => $lang]) }}" class="btn btn-green">
                {{ $lang == 'ar' ? 'إضافة واجهة جديدة' : 'Add New Banner' }}
            </a>
        </div>

        @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif

        <div class="table-container">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ $lang == 'ar' ? 'الصورة' : 'Image' }}</th>
                        <th>{{ $lang == 'ar' ? 'تاريخ البدء' : 'Start Date' }}</th>
                        <th>{{ $lang == 'ar' ? 'تاريخ الانتهاء' : 'End Date' }}</th>
                        <th>{{ $lang == 'ar' ? 'الموقع' : 'Location' }}</th>
                        <th>{{ $lang == 'ar' ? 'التصنيف' : 'Category' }}</th>
                        <th>{{ $lang == 'ar' ? 'الحالة' : 'Status' }}</th>
                        <th>{{ $lang == 'ar' ? 'الإجراءات' : 'Actions' }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($banners as $index => $banner)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>
                            @if($banner->image_path)
                            <img src="{{ asset('storage/' . $banner->image_path) }}" alt="Banner" class="banner-image">
                            @else
                            <div class="no-image">{{ $lang == 'ar' ? 'لا توجد صورة' : 'No Image' }}</div>
                            @endif
                        </td>
                        <td>{{ $banner->start_date->format('Y-m-d') }}</td>
                        <td>{{ $banner->expiration_date->format('Y-m-d') }}</td>
                        <td>{{ $banner->location == 'app' ? ($lang == 'ar' ? 'التطبيق' : 'App') : ($lang == 'ar' ? 'الموقع' : 'Location') }}</td>
                        <td>{{ $banner->category ?? ($lang == 'ar' ? 'غير محدد' : 'Not Specified') }}</td>
                        <td>
                            <span class="status {{ $banner->is_active ? 'active' : 'inactive' }}">
                                {{ $banner->is_active ? ($lang == 'ar' ? 'نشط' : 'Active') : ($lang == 'ar' ? 'غير نشط' : 'Inactive') }}
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                <a href="{{ route('banners.edit', ['lang' => $lang, 'banner' => $banner->id]) }}" class="btn btn-yellow">
                                    {{ $lang == 'ar' ? 'تعديل' : 'Edit' }}
                                </a>
                                <form class="delete-form" action="{{ route('banners.destroy', ['lang' => $lang, 'banner' => $banner->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="width: 100%; font-family: 'cairo'; height: 100%; border:0px;" class="btn btn-red">{{ $lang == 'ar' ? 'حذف' : 'Delete' }}</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="empty-table">{{ $lang == 'ar' ? 'لا توجد بانرات مضافة حتى الآن' : 'No banners added yet' }}</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
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
                    title: '{{ $lang == "ar" ? "هل أنت متأكد؟" : "Are you sure?" }}',
                    text: '{{ $lang == "ar" ? "لن تتمكن من استرجاع هذا البانر بعد الحذف!" : "You won’t be able to recover this banner after deletion!" }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: '{{ $lang == "ar" ? "نعم، احذفه!" : "Yes, delete it!" }}',
                    cancelButtonText: '{{ $lang == "ar" ? "إلغاء" : "Cancel" }}'
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