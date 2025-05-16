@extends('layouts.adminLayout')

@section('title', 'إدارة الحسابات')

@section('content')
    <div style="padding: 1rem; min-height: 100vh; padding-top: 5rem;">
        <div
            style="max-width: 72rem; margin-left: auto; margin-right: auto; background-color: #2c2c38; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 0.5rem;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                <h2 style="font-size: 1.5rem; font-weight: 600; color: white;">قائمة الإشتراكات 🖥️</h2>
                <a href="{{ route('games.create', ['lang' => $lang]) }}"
                    style="padding: 0.5rem 1rem; background-color: #16a34a; color: white; border-radius: 0.5rem; text-decoration: none;">
                    إضافة إشتراك جديد
                </a>
                <style>
                    a:hover {
                        background-color: #15803d;
                    }
                </style>
            </div>
            <div style="display: flex; align-items: center; justify-content: space-between; width: 98%;">
                <form action="{{ route('games.index', ['lang' => $lang]) }}" method="GET" id="status-filter-form">
                    <select name="status" onchange="document.getElementById('status-filter-form').submit()"
                        style="font-family: 'cairo'; margin-bottom: 20px; padding: 0.5rem; background-color: #21212d; color: white; border: 1px solid #4b5563; border-radius: 0.5rem;">
                        <option class="font-family: 'cairo';" value="">كل الحالات</option>
                        <option class="font-family: 'cairo';" value="active"
                            {{ request('status') == 'active' ? 'selected' : '' }}>مفعل</option>
                        <option class="font-family: 'cairo';" value="inactive"
                            {{ request('status') == 'inactive' ? 'selected' : '' }}>غير مفعل</option>
                        <option class="font-family: 'cairo';" value="expired"
                            {{ request('status') == 'expired' ? 'selected' : '' }}>منتهي</option>
                        <option class="font-family: 'cairo';" value="canceled"
                            {{ request('status') == 'canceled' ? 'selected' : '' }}>ملغي</option>
                        <option class="font-family: 'cairo';" value="pending_dns"
                            {{ request('status') == 'pending_dns' ? 'selected' : '' }}>بانتظار DNS
                        </option>
                    </select>
                </form>
                <div style="margin-bottom: 1.5rem;">
                    <form action="{{ route('games.index', ['lang' => $lang]) }}" method="GET" id="search-form">
                        <input type="text" name="search" id="search-input"
                            placeholder="ابحث بالاسم أو البريد الإلكتروني" value="{{ request('search') }}"
                            style="min-width: 215px !important; font-family: 'cairo'; padding: 0.5rem; background-color: #21212d; color: white; border: 1px solid #4b5563; border-radius: 0.5rem; width: 100%; max-width: 300px;">
                        <!-- الاحتفاظ بفلتر الحالة إذا كان موجودًا -->
                        @if (request('status'))
                            <input type="hidden" name="status" value="{{ request('status') }}">
                        @endif
                    </form>
                </div>
            </div>

            <script>
                // دالة تأخير (debounce) لتقليل عدد الطلبات
                function debounce(func, wait) {
                    let timeout;
                    return function executedFunction(...args) {
                        const later = () => {
                            clearTimeout(timeout);
                            func(...args);
                        };
                        clearTimeout(timeout);
                        timeout = setTimeout(later, wait);
                    };
                }

                // التعامل مع البحث الفوري
                document.addEventListener('DOMContentLoaded', function() {
                    const searchInput = document.getElementById('search-input');
                    const searchForm = document.getElementById('search-form');

                    // إرسال النموذج عند الكتابة مع تأخير 300 مللي ثانية
                    const performSearch = debounce(function() {
                        searchForm.submit();
                    }, 1000);

                    searchInput.addEventListener('input', performSearch);
                });
            </script>
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
                            <th style="padding: 0.75rem 1rem; text-align: right;">الاسم</th>
                            <th style="padding: 0.75rem 1rem; text-align: right;">الصورة</th>
                            <th style="padding: 0.75rem 1rem; text-align: right;">الباقة</th>
                            <th style="padding: 0.75rem 1rem; text-align: right;">خطة الباقة</th>
                            <th style="padding: 0.75rem 1rem; text-align: right;">انتهاء الباقة</th>
                            <th style="padding: 0.75rem 1rem; text-align: right;">الدولة</th>
                            <th style="padding: 0.75rem 1rem; text-align: right;">رقم التفعيل</th>
                            <th style="padding: 0.75rem 1rem; text-align: right;">انتهاء DNS</th>
                            <th style="padding: 0.75rem 1rem; text-align: right;">الحالة</th>
                            <th style="padding: 0.75rem 1rem; text-align: right;">التحكم</th>
                        </tr>
                    </thead>
                    <tbody style="color: white;">
                        @forelse ($subscribers as $index => $game)
                            <tr style="border-bottom: 1px solid #2c2c38;">
                                <td style="padding: 0.75rem 1rem; text-align: right;">{{ $index + 1 }}</td>
                                <td style="padding: 0.75rem 1rem;">
                                    <div>{{ $game->name }}</div>
                                    <div style="color: #9ca3af; font-size: 0.875rem;">{{ Str::limit($game->email, 10) }}
                                    </div>
                                </td>
                                <td style="padding: 0.75rem 1rem; text-align: right;">
                                    @if ($game->mainCategory && $game->mainCategory->image)
                                        <img src="{{ asset('storage/' . $game->mainCategory->image) }}" alt="unknown"
                                            style="width: 50px; height: 50px; object-fit: cover; border-radius: 0.25rem;">
                                    @else
                                        <div
                                            style="width: 50px; height: 50px; background-color: #4b5563; border-radius: 0.25rem; display: flex; align-items: center; justify-content: center;">
                                            <span style="color: #9ca3af; font-size: 0.75rem;">لا توجد صورة</span>
                                        </div>
                                    @endif
                                </td>

                                <td style="padding: 0.75rem 1rem; text-align: right;">
                                    {{ $game->mainCategory ? ($lang == 'ar' ? $game->mainCategory->name_ar : $game->mainCategory->name_en) : ($lang == 'ar' ? 'غير محدد' : 'Not specified') }}
                                </td>

                                <td style="padding: 0.75rem 1rem; text-align: right;">
                                    {{ $game->subCategory ? ($lang == 'ar' ? $game->subCategory->name_ar : $game->subCategory->name_en) : ($lang == 'ar' ? 'غير محدد' : 'Not specified') }}
                                </td>

                                <td style="padding: 0.75rem 1rem; text-align: right;">
                                    @if ($game->subCategory && $game->subCategory->duration)
                                        {{ \Carbon\Carbon::parse($game->created_at)->addDays($game->subCategory->duration)->format('Y-m-d') }}
                                    @else
                                        {{ $lang == 'ar' ? 'غير محدد' : 'Not specified' }}
                                    @endif
                                </td>
                                <td style="padding: 0.75rem 1rem; text-align: right;">
                                    {{ $game->country }}
                                </td>
                                <td style="padding: 0.75rem 1rem; text-align: right;">
                                    {{ $game->activation_code }}
                                </td>
                                <td style="padding: 0.75rem 1rem; text-align: right;">
                                    {{ Str::limit($game->dns_expiry_date, 10) }}
                                </td>
                                <td style="padding: 0.75rem 1rem; text-align: right;">{{ $game->status }}</td>
                                <td style="padding: 0.75rem 1rem; text-align: right;">
                                    <div style="display: flex; gap: 0.5rem; justify-content: flex-start;">
                                        <a href="{{ route('games.edit', ['game' => $game->id, 'lang' => request()->route('lang')]) }}"
                                            style="margin-left: 0.75rem; padding: 0.25rem 0.75rem; background-color: #eab308; color: white; border-radius: 0.25rem; text-decoration: none;">
                                            تعديل
                                        </a>
                                        <style>
                                            a[href*='games.edit']:hover {
                                                background-color: #ca8a04;
                                            }
                                        </style>

                                        <form class="delete-form"
                                            action="{{ route('games.destroy', ['lang' => $lang, 'game' => $game->id]) }}"
                                            method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                style="height: 100%; font-family: 'cairo'; padding: 0.25rem 0.75rem; background-color: #dc2626; color: white; border-radius: 0.25rem; border: none; cursor: pointer;">
                                                حذف
                                            </button>
                                            <style>
                                                button[type="submit"]:hover {
                                                    background-color: #b91c1c;
                                                }
                                            </style>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <style>
                                tr:hover {
                                    background-color: #2c2c38;
                                }
                            </style>
                        @empty
                            <tr>
                                <td colspan="11" style="padding: 1.5rem 0; text-align: center; color: #9ca3af;">لا توجد
                                    حسابات مضافة حتى الآن</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- <div style="margin-top: 1rem;">
                {{ $games->links() }}
            </div> --}}
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
                        text: 'لن تتمكن من استرجاع هذا الإشتراك بعد الحذف!',
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
