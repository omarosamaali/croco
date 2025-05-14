@extends('layouts.adminLayout')

@section('title', $lang == 'ar' ? 'إدارة الخطط' : 'Manage Plans')

@section('content')
<div style="padding: 1rem; min-height: 100vh; padding-top: 5rem;">
    <div style="max-width: 72rem; margin: 0 auto; background-color: #2c2c38; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 0.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.5rem; font-weight: 600; color: white;">
                {{ $lang == 'ar' ? 'إدارة الخطط 📋' : 'Manage Plans 📋' }}
            </h2>
            <a href="{{ route('plans.create', ['lang' => $lang]) }}" style="padding: 0.5rem 1rem; background-color: #16a34a; color: white; border-radius: 0.5rem; text-decoration: none; transition: background-color 0.3s ease;">
                {{ $lang == 'ar' ? 'إضافة خطة جديدة' : 'Add New Plan' }}
            </a>
            <style>
                a:hover { background-color: #15803d; }
            </style>
        </div>

        @if(session('success'))
        <div style="background-color: #22c55e; color: white; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 1rem; text-align: right;">
            {{ session('success') }}
        </div>
        @endif

        <div style="overflow-x: auto;">
            <table style="min-width: 100%; background-color: #21212d; border-radius: 0.5rem; overflow: hidden;">
                <thead style="background-color: #2c2c38; color: white;">
                    <tr>
                        <th style="padding: 0.75rem 1rem; text-align: right;">#</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">{{ $lang == 'ar' ? 'العنوان' : 'Title' }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">{{ $lang == 'ar' ? 'السعر' : 'Price' }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">{{ $lang == 'ar' ? 'الصورة' : 'Image' }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">{{ $lang == 'ar' ? 'الترتيب' : 'Order' }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">{{ $lang == 'ar' ? 'الحالة' : 'Status' }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">{{ $lang == 'ar' ? 'الإجراءات' : 'Actions' }}</th>
                    </tr>
                </thead>
                <tbody style="color: white;">
                    @forelse ($plans as $index => $plan)
                    <tr style="border-bottom: 1px solid #2c2c38; background-color: #21212d;">
                        <td style="padding: 0.75rem 1rem; text-align: right;">{{ $index + 1 }}</td>
                        <td style="padding: 0.75rem 1rem; text-align: right;">
                            <div>{{ $lang == 'ar' ? $plan->title_ar : $plan->title_en }}</div>
                        </td>
                        <td style="padding: 0.75rem 1rem; text-align: right;">{{ $plan->price }}</td>
                        <td style="padding: 0.75rem 1rem; text-align: right;">
                            @if ($plan->image)
                                <img src="{{ asset('storage/' . $plan->image) }}" alt="{{ $lang == 'ar' ? $plan->title_ar : $plan->title_en }}" style="max-width: 100px; border-radius: 0.5rem;">
                            @else
                                <span style="color: #9ca3af;">{{ $lang == 'ar' ? 'لا توجد صورة' : 'No image' }}</span>
                            @endif
                        </td>
                        <td style="padding: 0.75rem 1rem; text-align: right;">{{ $plan->order }}</td>
                        <td style="padding: 0.75rem 1rem; text-align: right;">
                            <span style="padding: 0.25rem 0.75rem; font-size: 0.875rem; font-weight: 600; border-radius: 9999px; 
                                        background-color: {{ $plan->is_active ? '#16a34a' : '#dc2626' }}; color: white;">
                                {{ $plan->is_active ? ($lang == 'ar' ? 'نشط' : 'Active') : ($lang == 'ar' ? 'غير نشط' : 'Inactive') }}
                            </span>
                        </td>
                        <td style="padding: 0.75rem 1rem; text-align: right;">
                            <div style="display: flex; gap: 0.5rem; justify-content: flex-start;">
                                <a href="{{ route('plans.edit', ['lang' => $lang, 'plan' => $plan->id]) }}" 
                                    style="padding: 0.25rem 0.75rem; background-color: #eab308; color: white; border-radius: 0.25rem; text-decoration: none;">
                                    {{ $lang == 'ar' ? 'تعديل' : 'Edit' }}
                                </a>
                                <form class="delete-form" action="{{ route('plans.destroy', ['lang' => $lang, 'plan' => $plan->id]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                            style="padding: 0.25rem 0.75rem; background-color: #dc2626; color: white; border-radius: 0.25rem; border: none; cursor: pointer;">
                                        {{ $lang == 'ar' ? 'حذف' : 'Delete' }}
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <style>
                        tr:hover { background-color: #2c2c38; }
                        a:hover { background-color: #ca8a04; }
                        button[type="submit"]:hover { background-color: #b91c1c; }
                    </style>
                    @empty
                    <tr>
                        <td colspan="7" style="padding: 1.5rem 0; text-align: center; color: #9ca3af;">
                            {{ $lang == 'ar' ? 'لا توجد خطط مضافة حتى الآن' : 'No plans added yet' }}
                        </td>
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
                    text: '{{ $lang == "ar" ? "لن تتمكن من استرجاع هذه الخطة بعد الحذف!" : "You won’t be able to recover this plan after deletion!" }}',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: '{{ $lang == "ar" ? "نعم، احذفها!" : "Yes, delete it!" }}',
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
