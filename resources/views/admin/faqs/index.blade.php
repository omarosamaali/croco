@extends('layouts.adminLayout')

@section('title', 'إدارة الأسئلة الشائعة')

@section('content')
<div style="padding: 1rem; min-height: 100vh; padding-top: 5rem;">
    <div style="max-width: 72rem; margin: 0 auto; background-color: #2c2c38; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 0.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.5rem; font-weight: 600; color: white;">
                {{ $lang == 'ar' ? 'إدارة الأسئلة الشائعة ❓' : 'Manage FAQs ❓' }}
            </h2>
            <a href="{{ route('faqs.create', ['lang' => $lang]) }}" style="padding: 0.5rem 1rem; background-color: #16a34a; color: white; border-radius: 0.5rem; text-decoration: none; transition: background-color 0.3s ease;">
                {{ $lang == 'ar' ? 'إضافة سؤال جديد' : 'Add New FAQ' }}
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
                        <th style="padding: 0.75rem 1rem; text-align: right;">{{ $lang == 'ar' ? 'السؤال' : 'Question' }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">{{ $lang == 'ar' ? 'الترتيب' : 'Order' }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">{{ $lang == 'ar' ? 'الحالة' : 'Status' }}</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">{{ $lang == 'ar' ? 'الإجراءات' : 'Actions' }}</th>
                    </tr>
                </thead>
                <tbody style="color: white;">
                    @forelse ($faqs as $index => $faq)
                    <tr style="border-bottom: 1px solid #2c2c38; background-color: #21212d;">
                        <td style="padding: 0.75rem 1rem; text-align: right;">{{ $index + 1 }}</td>
                        <td style="padding: 0.75rem 1rem; text-align: right;">
                            <div>{{ $lang == 'ar' ? $faq->question_ar : $faq->question_en }}</div>
                        </td>
                        <td style="padding: 0.75rem 1rem; text-align: right;">{{ $faq->order }}</td>
                        <td style="padding: 0.75rem 1rem; text-align: right;">
                            <span style="padding: 0.25rem 0.75rem; font-size: 0.875rem; font-weight: 600; border-radius: 9999px; 
                                        background-color: {{ $faq->is_active ? '#16a34a' : '#dc2626' }}; color: white;">
                                {{ $faq->is_active ? ($lang == 'ar' ? 'نشط' : 'Active') : ($lang == 'ar' ? 'غير نشط' : 'Inactive') }}
                            </span>
                        </td>
                        <td style="padding: 0.75rem 1rem; text-align: right;">
                            <div style="display: flex; gap: 0.5rem; justify-content: flex-start;">
                                <a href="{{ route('faqs.edit', ['lang' => $lang, 'faq' => $faq->id]) }}" 
                                    style="padding: 0.25rem 0.75rem; background-color: #eab308; color: white; border-radius: 0.25rem; text-decoration: none;">
                                    {{ $lang == 'ar' ? 'تعديل' : 'Edit' }}
                                </a>
                                <form class="delete-form" action="{{ route('faqs.destroy', ['lang' => $lang, 'faq' => $faq->id]) }}" method="POST">
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
                        <td colspan="5" style="padding: 1.5rem 0; text-align: center; color: #9ca3af;">
                            {{ $lang == 'ar' ? 'لا توجد أسئلة شائعة مضافة حتى الآن' : 'No FAQs added yet' }}
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1rem;">
            {{ $faqs->links() }}
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
                    text: '{{ $lang == "ar" ? "لن تتمكن من استرجاع هذا السؤال بعد الحذف!" : "You won’t be able to recover this FAQ after deletion!" }}',
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
