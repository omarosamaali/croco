@extends('layouts.adminLayout')

@section('title', 'إدارة الأخبار')

@section('content')
<div style="padding: 1rem; min-height: 100vh; padding-top: 5rem;">
    <div style="max-width: 72rem; margin-left: auto; margin-right: auto; background-color: #2c2c38; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 0.5rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 1.5rem; font-weight: 600; color: white;">قائمة الأخبار 📰</h2>
            <a href="{{ route('news.create', ['lang' => request()->route('lang')]) }}" 
                style="padding: 0.5rem 1rem; background-color: #16a34a; color: white; border-radius: 0.5rem; text-decoration: none;">
                إضافة خبر جديد
            </a>
            <style>
                a[href*='news.create']:hover { background-color: #15803d; }
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
                        <th style="padding: 0.75rem 1rem; text-align: right;">صورة</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">الصورة الخطط</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">عنوان الخبر</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">الكاتب</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">التعليقات</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">تاريخ النشر</th>
                        <th style="padding: 0.75rem 1rem; text-align: right;">التحكم</th>
                    </tr>
                </thead>
                <tbody style="color: white;">
                    @forelse ($news as $index => $item)
                    <tr style="border-bottom: 1px solid #2c2c38;">
                        <td style="padding: 0.75rem 1rem; text-align: right;">{{ $index + 1 }}</td>
                        <td style="padding: 0.75rem 1rem;">
                            @if($item->image_path)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title_ar }}" 
                                style="width: 4rem; height: 4rem; object-fit: cover; border-radius: 0.25rem;">
                            @else
                            <div style="width: 4rem; height: 4rem; background-color: #374151; border-radius: 0.25rem; display: flex; align-items: center; justify-content: center;">
                                <span style="color: #9ca3af;">لا توجد صورة</span>
                            </div>
                            @endif
                        </td>
                                                <td style="padding: 0.75rem 1rem;">
                            @if($item->secondary_image)
                            <img src="{{ asset('storage/' . $item->secondary_image) }}" alt="no image" 
                                style="width: 4rem; height: 4rem; object-fit: cover; border-radius: 0.25rem;">
                            @else
                            <div style="width: 4rem; height: 4rem; background-color: #374151; border-radius: 0.25rem; display: flex; align-items: center; justify-content: center;">
                                <span style="color: #9ca3af;">لا توجد صورة</span>
                            </div>
                            @endif
                        </td>
<!--                        <td style="padding: 0.75rem 1rem;">-->
<!--    @if($item->secondary_image_path)-->
<!--        <img src="{{ asset('storage/' . $item->secondary_image_path) }}" alt="صورة إضافية" -->
<!--            style="width: 4rem; height: 4rem; object-fit: cover; border-radius: 0.25rem;">-->
<!--    @else-->
<!--        <div style="width: 4rem; height: 4rem; background-color: #374151; border-radius: 0.25rem; display: flex; align-items: center; justify-content: center;">-->
<!--            <span style="color: #9ca3af;">لا توجد صورة إضافية</span>-->
<!--        </div>-->
<!--    @endif-->
<!--</td>-->

                        <td style="padding: 0.75rem 1rem; text-align: right;">
                            <div>{{ $item->title_ar }}</div>
                            <div style="color: #9ca3af; font-size: 0.875rem;">{{ $item->title_en }}</div>
                        </td>
                        <td style="padding: 0.75rem 1rem; text-align: right;">{{ $item->author }}</td>
                        <td style="padding: 0.75rem 1rem; text-align: right;">{{ $item->comments_count }}</td>
                        <td style="padding: 0.75rem 1rem; text-align: right;">{{ $item->created_at->format('Y-m-d') }}</td>
                        <td style="padding: 0.75rem 1rem; text-align: right;">
                            <div style="display: flex; gap: 0.5rem; justify-content: flex-start;">
                                <a href="{{ route('news.edit', ['news' => $item->id, 'lang' => request()->route('lang')]) }}" 
                                    style="margin-left: 0.75rem; padding: 0.25rem 0.75rem; background-color: #eab308; color: white; border-radius: 0.25rem; text-decoration: none;">
                                    تعديل
                                </a>
                                <form class="delete-form" action="{{ route('news.destroy', ['news' => $item->id, 'lang' => request()->route('lang')]) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" 
                                        style="padding: 0.25rem 0.75rem; background-color: #dc2626; color: white; border-radius: 0.25rem; border: none; cursor: pointer;">
                                        حذف
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <style>
                        tr:hover { background-color: #2c2c38; }
                        a[href*='news.edit']:hover { background-color: #ca8a04; }
                        button[type="submit"]:hover { background-color: #b91c1c; }
                    </style>
                    @empty
                    <tr>
                        <td colspan="7" style="padding: 1.5rem 0; text-align: center; color: #9ca3af;">لا توجد أخبار مضافة حتى الآن</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1rem;">
            {{ $news->links() }}
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
                    text: 'لن تتمكن من استرجاع هذه اللعبة بعد الحذف!',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'نعم، احذفها!',
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