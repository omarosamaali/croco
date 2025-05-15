@extends('layouts.adminLayout')

@section('title', 'تعديل الإشتراك')

@section('content')
    <div style="padding: 1rem; min-height: 100vh; padding-top: 5rem;">
        <div
            style="max-width: 72rem; margin-left: auto; margin-right: auto; background-color: #2c2c38; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 0.5rem;">
            <h2 style="font-size: 1.5rem; font-weight: 600; color: white; margin-bottom: 1.5rem;">تعديل الإشتراك</h2>

            @if (session('success'))
                <div
                    style="background-color: #22c55e; color: white; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 1rem; text-align: right;">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('games.update', ['lang' => $lang, 'game' => $game->id]) }}" method="POST">
                @csrf
                @method('PUT')

                <div style="margin-bottom: 1rem;">
                    <label style="color: white; font-family: 'cairo';">اسم المستخدم</label>
                    <input type="text" name="dns_username" value="{{ old('dns_username', $game->dns_username) }}"
                        style="width: 100%; padding: 0.5rem; background-color: #21212d; color: white; border: 1px solid #4b5563; border-radius: 0.5rem;"
                        placeholder="مثال: user1">
                    @error('dns_username')
                        <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="color: white; font-family: 'cairo';">كلمة المرور</label>
                    <input type="text" name="dns_password" value="{{ old('dns_password', $game->dns_password) }}"
                        style="width: 100%; padding: 0.5rem; background-color: #21212d; color: white; border: 1px solid #4b5563; border-radius: 0.5rem;"
                        placeholder="••••••••••••">
                    @error('dns_password')
                        <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="color: white; font-family: 'cairo';">رابط DNS</label>
                    <input type="url" name="dns_link" value="{{ old('dns_link', $game->dns_link) }}"
                        style="width: 100%; padding: 0.5rem; background-color: #21212d; color: white; border: 1px solid #4b5563; border-radius: 0.5rem;"
                        placeholder="https://dns.example.com">
                    @error('dns_link')
                        <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="color: white; font-family: 'cairo';">تاريخ انتهاء DNS</label>
                    <input type="date" name="dns_expiry_date"
                        value="{{ old('dns_expiry_date', $game->dns_expiry_date ? $game->dns_expiry_date : '') }}"
                        style="width: 100%; padding: 0.5rem; background-color: #21212d; color: white; border: 1px solid #4b5563; border-radius: 0.5rem;">
                    @error('dns_expiry_date')
                        <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="color: white; font-family: 'cairo';">رقم التفعيل</label>
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <input type="text" name="activation_code" id="activation_code"
                            value="{{ old('activation_code', $game->activation_code) }}" readonly
                            style="width: 100%; padding: 0.5rem; background-color: #21212d; color: white; border: 1px solid #4b5563; border-radius: 0.5rem;">
                        <button type="button" id="regenerate_code"
                            style="min-width: 98px; padding: 0.5rem 1rem; background-color: #16a34a; color: white; border-radius: 0.5rem; border: none; cursor: pointer;">
                            إعادة توليد
                        </button>
                    </div>
                    @error('activation_code')
                        <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="margin-bottom: 1rem;">
                    <label style="color: white; font-family: 'cairo';">الحالة</label>
                    <select name="status"
                        style="width: 100%; padding: 0.5rem; background-color: #21212d; color: white; border: 1px solid #4b5563; border-radius: 0.5rem;">
                        <option value="active" {{ $game->status == 'active' ? 'selected' : '' }}>مفعل</option>
                        <option value="inactive" {{ $game->status == 'inactive' ? 'selected' : '' }}>غير مفعل</option>
                        <option value="expired" {{ $game->status == 'expired' ? 'selected' : '' }}>منتهي</option>
                        <option value="canceled" {{ $game->status == 'canceled' ? 'selected' : '' }}>ملغي</option>
                        <option value="pending_dns" {{ $game->status == 'pending_dns' ? 'selected' : '' }}>بانتظار DNS
                        </option>
                    </select>
                    @error('status')
                        <span style="color: #dc2626; font-size: 0.875rem;">{{ $message }}</span>
                    @enderror
                </div>

                <div style="display: flex; gap: 1rem;">
                    <button type="submit"
                        style="padding: 0.5rem 1rem; background-color: #16a34a; color: white; border-radius: 0.5rem; border: none; cursor: pointer;">
                        حفظ
                    </button>
                    <a href="{{ route('games.index', ['lang' => $lang]) }}"
                        style="padding: 0.5rem 1rem; background-color: #6b7280; color: white; border-radius: 0.5rem; text-decoration: none;">
                        إلغاء
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('regenerate_code').addEventListener('click', function() {
            // Generate a 10-digit random number
            const randomCode = Math.floor(1000000000 + Math.random() * 9000000000);
            document.getElementById('activation_code').value = randomCode;
        });
    </script>
@endsection
