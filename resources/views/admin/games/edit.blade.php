@extends('layouts.adminLayout')

@section('title', 'تعديل الإشتراك')

@section('content')
<div style="padding: 1rem; padding-top: 5rem;">
    <div
        style="max-width: 48rem; margin-bottom: 5rem; margin-left: auto; margin-right: auto; background-color: #2c2c38; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 0.5rem;">
        <h2 style="font-size: 1.5rem; font-weight: 600; text-align: center; color: #c9c8dc; margin-bottom: 1rem;">تعديل
            إشتراك: {{ $game->name }} 🖥️</h2>

        @if ($errors->any())
        <div
            style="background-color: #ef4444; color: #c9c8dc; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 1rem;">
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form action="{{ route('games.update', ['lang' => $lang, 'game' => $game->id]) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- الاسم والبريد الإلكتروني -->
            <div style="display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label
                        style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">الاسم</label>
                    <input type="text" name="name" value="{{ old('name', $game->name) }}" required
                        style="text-align: right; background-color: #21212d; color: #c9c8dc; width: 98%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
                </div>
                <div>
                    <label
                        style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">البريد
                        الإلكتروني</label>
                    <input type="email" name="email" value="{{ old('email', $game->email) }}" required
                        style="background-color: #21212d; color: #c9c8dc; width: 98%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
                </div>
            </div>

            <!-- قائمة الدولة -->
            <div style="margin-bottom: 1rem; text-align: right;">
                <label
                    style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">الدولة</label>
                <select name="country" required
                    style="background-color: #21212d; color: #c9c8dc; width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem; direction: rtl;">
                    <option value="" disabled>اختر الدولة</option>
                    <option value="EG" {{ old('country', $game->country) == 'EG' ? 'selected' : '' }}>مصر</option>
                    <option value="SA" {{ old('country', $game->country) == 'SA' ? 'selected' : '' }}>السعودية</option>
                    <option value="AE" {{ old('country', $game->country) == 'AE' ? 'selected' : '' }}>الإمارات</option>
                    <option value="KW" {{ old('country', $game->country) == 'KW' ? 'selected' : '' }}>الكويت</option>
                    <option value="QA" {{ old('country', $game->country) == 'QA' ? 'selected' : '' }}>قطر</option>
                    <option value="BH" {{ old('country', $game->country) == 'BH' ? 'selected' : '' }}>البحرين</option>
                    <option value="OM" {{ old('country', $game->country) == 'OM' ? 'selected' : '' }}>عمان</option>
                    <option value="JO" {{ old('country', $game->country) == 'JO' ? 'selected' : '' }}>الأردن</option>
                    <option value="LB" {{ old('country', $game->country) == 'LB' ? 'selected' : '' }}>لبنان</option>
                    <option value="MA" {{ old('country', $game->country) == 'MA' ? 'selected' : '' }}>المغرب</option>
                    <option value="TN" {{ old('country', $game->country) == 'TN' ? 'selected' : '' }}>تونس</option>
                    <option value="DZ" {{ old('country', $game->country) == 'DZ' ? 'selected' : '' }}>الجزائر</option>
                </select>
            </div>

            <!-- الباقة -->
            <div style="margin-bottom: 1rem; text-align: right;">
                <label
                    style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">
                    الباقة</label>
                <select name="main_category" required
                    style="background-color: #21212d; color: #c9c8dc; width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem; direction: rtl;"
                    onchange="updateImage(this)">
                    <option value="" disabled>اختر الباقة</option>
                    @foreach ($mainCategories as $category)
                    <option value="{{ $category->id }}" {{ old('main_category', $game->main_category) == $category->id ? 'selected' : '' }}>
                        {{ $lang == 'ar' ? $category->name_ar : $category->name_en }}
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- صورة اللعبة (مخفية لأنها أوتوماتيكية) -->
            <input type="hidden" name="image" id="game_image" value="{{ old('image', $game->image) }}">

            <!-- الخطط -->
            <div style="margin-bottom: 1rem; text-align: right;">
                <label
                    style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">
                    خطة الباقة</label>
                <select name="sub_categories[]" required
                    style="background-color: #21212d; color: #c9c8dc; width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem; direction: rtl;"
                    onchange="updateExpiryDate()">
                    @foreach ($subCategories as $subCategory)
                    <option value="{{ $subCategory->id }}" {{ in_array($subCategory->id, old('sub_categories', $selectedSubCategories)) ? 'selected' : '' }}>
                        {{ $lang == 'ar' ? $subCategory->name_ar : $subCategory->name_en }}
                    </option>
                    @endforeach
                </select>
                {{-- <div style="color: #9ca3af; font-size: 0.75rem; margin-top: 0.25rem;">اضغط مع الاستمرار لاختيار أكثر من تصنيف</div> --}}
            </div>

            <!-- تاريخ التسجيل وتاريخ الانتهاء -->

            <!-- الوصف بالعربية -->
            <div style="margin-bottom: 1rem; text-align: right;">
                <label
                    style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">
                    الوصف (عربي)
</label>
                <div id="description-ar-container">
                    @foreach (old('description_ar', $game->description_ar ?? []) as $index => $desc)
                    <div style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem;">
                        <input type="text" name="description_ar[]" value="{{ $desc }}" required
                            style="background-color: #21212d; color: #c9c8dc; width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem; text-align: right;">
                        <button type="button" class="remove-description-ar"
                            style="padding: 0.5rem; background-color: #dc2626; color: white; border-radius: 0.5rem; border: none; cursor: pointer;">-</button>
                    </div>
                    @endforeach
                </div>
                <button type="button" id="add-description-ar"
                    style="margin-top: 0.5rem; padding: 0.5rem 0.75rem; background-color: #2563eb; color: white; border-radius: 0.5rem; border: none; cursor: pointer;">
                    + إضافة وصف جديد
                </button>
            </div>

            <!-- الوصف بالإنجليزية -->
            <div style="margin-bottom: 1rem; text-align: right;">
                <label
                    style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">
                    الوصف (إنجليزي)
</label>
                <div id="description-en-container">
                    @foreach (old('description_en', $game->description_en ?? []) as $index => $desc)
                    <div style="display: flex; gap: 0.5rem; margin-bottom: 0.5rem;">
                        <input type="text" name="description_en[]" value="{{ $desc }}" required
                            style="background-color: #21212d; color: #c9c8dc; width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
                        <button type="button" class="remove-description-en"
                            style="padding: 0.5rem; background-color: #dc2626; color: white; border-radius: 0.5rem; border: none; cursor: pointer;">-</button>
                    </div>
                    @endforeach
                </div>
                <button type="button" id="add-description-en"
                    style="margin-top: 0.5rem; padding: 0.5rem 0.75rem; background-color: #2563eb; color: white; border-radius: 0.5rem; border: none; cursor: pointer;">
                    + إضافة وصف جديد
                </button>
            </div>

            <!-- اسم المستخدم وكلمة المرور -->
            <div style="display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label
                        style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">اسم
                        المستخدم</label>
                    <input type="text" name="username" value="{{ old('username', $game->name) }}" required
                        style="text-align: right; background-color: #21212d; color: #c9c8dc; width: 98%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 1rem;">
                <div>
                    <label
                        style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">تاريخ
                        التسجيل</label>
                    <input  type="text" name="registration_date" value="{{ old('registration_date', $game->registration_date) }}" readonly
                        style="background-color: #21212d; color: #c9c8dc; width: 98%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
                </div>
                <div>
                    <label
                        style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">تاريخ
                        الانتهاء</label>
                    <input type="text" name="expiry_date" id="expiry_date" value="{{ old('expiry_date', $game->expiry_date) }}" readonly
                        style="background-color: #21212d; color: #c9c8dc; width: 98%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
                </div>
            </div>

            <!-- بيانات سيرفر DNS -->
            <div style="margin-bottom: 1rem; text-align: right;">
                <label
                    style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">بيانات
                    سيرفر DNS</label>
                <div id="dns-container">
                    @php
                    $dns_servers = is_array($game->dns_servers) ? $game->dns_servers : json_decode($game->dns_servers, true);
                    @endphp
                    @foreach($dns_servers as $index => $dns)
                    <div
                        style="background-color: #2c2c38; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                        <input type="hidden" name="existing_dns[{{ $index }}][username]" value="{{ $dns['username'] }}">
                        <input type="hidden" name="existing_dns[{{ $index }}][url]" value="{{ $dns['url'] }}">
                        <div style="display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 0.5rem;">
                            <div>
                                <label
                                    style="display: block; color: #c9c8dc; font-size: 0.75rem; margin-bottom: 0.25rem;">اسم
                                    المستخدم</label>
                                <input type="text" name="updated_dns[{{ $index }}][username]" value="{{ $dns['username'] }}"
                                    style="background-color: #21212d; color: #c9c8dc; text-align: right; width: 98%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
                            </div>
                                            <div>
                    <label
                        style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">كلمة
                        المرور</label>
                    <input type="password" name="password" value=""
                        style="background-color: #21212d; color: #c9c8dc; width: 98%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
                    <div style="color: #9ca3af; font-size: 0.75rem; margin-top: 0.25rem; text-align: right;">اترك هذا الحقل فارغًا إذا كنت لا ترغب في تغيير كلمة المرور</div>
                </div>

                            <div>
                                <label
                                    style="display: block; color: #c9c8dc; font-size: 0.75rem; margin-bottom: 0.25rem;">رابط
                                    DNS</label>
                                <input type="url" name="updated_dns[{{ $index }}][url]" value="{{ $dns['url'] }}"
                                    style="background-color: #21212d; color: #c9c8dc; text-align: right; width: 98%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
                            </div>
                        </div>
                        <button type="button" class="remove-dns"
                            style="display: none; margin-top: 0.5rem; padding: 0.5rem 0.75rem; background-color: #dc2626; color: white; border-radius: 0.5rem; border: none; cursor: pointer;">
                            ❌ حذف السيرفر
                        </button>
                    </div>
                    @endforeach
                </div>
                <button type="button" id="add-dns"
                    style="display: none; margin-top: 0.5rem; padding: 0.5rem 0.75rem; background-color: #2563eb; color: white; border-radius: 0.5rem; border: none; cursor: pointer;">
                    + إضافة سيرفر DNS آخر
                </button>
            </div>

            <!-- تاريخ انتهاء DNS -->
            <div style="margin-bottom: 1rem; text-align: right;">
                <label
                    style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">تاريخ
                    انتهاء DNS</label>
                    {{ $game->dns_expiry_date }}
                    <input type="date" name="dns_expiry_date" value="{{ old('dns_expiry_date', \Carbon\Carbon::parse($game->dns_expiry_date)->format('Y-m-d')) }}"
    style="background-color: #21212d; color: #c9c8dc; width: 97%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
            </div>

            <!-- رقم التفعيل -->
            <div style="margin-bottom: 1rem; text-align: right;">
                <label
                    style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">رقم
                    التفعيل</label>
                <div style="display: flex; gap: 0.5rem;">
                    <input type="text" name="activation_code" id="activation_code" value="{{ old('activation_code', $game->activation_code) }}"
                        style="background-color: #21212d; color: #c9c8dc; width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
                    <button type="button" id="regenerate_code"
                        style="min-width: 80px; padding: 0.5rem; background-color: #2563eb; color: white; border-radius: 0.5rem; border: none; cursor: pointer;">إعادة
                        توليد</button>
                </div>
            </div>

            <!-- الحالة -->
            <div style="margin-bottom: 1rem; text-align: right;">
                <label
                    style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">الحالة</label>
                <select name="status" required
                    style="background-color: #21212d; color: #c9c8dc; width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem; direction: rtl;">
                    <option value="active" {{ old('status', $game->status) == 'active' ? 'selected' : '' }}>فعال</option>
                    <option value="inactive" {{ old('status', $game->status) == 'inactive' ? 'selected' : '' }}>غير فعال</option>
                    <option value="expired" {{ old('status', $game->status) == 'expired' ? 'selected' : '' }}>منتهي</option>
                    <option value="canceled" {{ old('status', $game->status) == 'canceled' ? 'selected' : '' }}>ملغي</option>
                    <option value="pending_dns" {{ old('status', $game->status) == 'pending_dns' ? 'selected' : '' }}>بإنتظار DNS</option>
                </select>
            </div>

            <div style="margin-top: 1.5rem; display: flex; justify-content: flex-end;">
                <button type="submit"
                    style="padding: 0.5rem 1rem; background-color: #16a34a; color: white; border-radius: 0.5rem; border: none; cursor: pointer;">
                    💾 حفظ التعديلات
                </button>
            </div>
        </form>

        <!-- إضافة الـ CSS العام لتطبيق font-family: 'Cairo' -->
        <style>
            div, h2, label, input, select, button, li {
                font-family: 'Cairo', sans-serif !important;
            }

            @media (min-width: 768px) {
                div[style*="grid-template-columns: 1fr"] {
                    grid-template-columns: 1fr 1fr;
                }
            }

            button[type="submit"]:hover {
                background-color: #15803d;
            }

            .remove-dns:hover, .remove-description-ar:hover, .remove-description-en:hover {
                background-color: #b91c1c;
            }

            #add-dns:hover, #add-description-ar:hover, #add-description-en:hover, #regenerate_code:hover {
                background-color: #1e4fc6;
            }
        </style>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addDnsBtn = document.getElementById('add-dns');
        const dnsContainer = document.getElementById('dns-container');
        const addDescriptionArBtn = document.getElementById('add-description-ar');
        const addDescriptionEnBtn = document.getElementById('add-description-en');
        const descriptionArContainer = document.getElementById('description-ar-container');
        const descriptionEnContainer = document.getElementById('description-en-container');

        // إضافة سيرفر DNS جديد
        addDnsBtn.addEventListener('click', function() {
            const dnsEntry = document.createElement('div');
            dnsEntry.style.cssText =
                "background-color: #2c2c38; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 1rem;";
            dnsEntry.innerHTML = `
                <div style="display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 0.5rem;">
                    <div>
                        <label style="display: block; color: #c9c8dc; font-size: 0.75rem; margin-bottom: 0.25rem;">اسم المستخدم</label>
                        <input type="text" name="dns_usernames[]" style="width: 97%; direction: rtl; background-color: #21212d; color: #c9c8dc; text-align: right; width: 98%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;" placeholder="اسم المستخدم">
                    </div>
                    <div>
                        <label style="display: block; color: #c9c8dc; font-size: 0.75rem; margin-bottom: 0.25rem;">رابط DNS</label>
                        <input type="url" name="dns_urls[]" style="width: 97%; direction: rtl; background-color: #21212d; color: #c9c8dc; text-align: right; width: 98%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;" placeholder="https://dns.example.com">
                    </div>
                </div>
                <button type="button" class="remove-dns" style="margin-top: 0.5rem; padding: 0.5rem 0.75rem; background-color: #dc2626; color: white; border-radius: 0.5rem; border: none; cursor: pointer;">❌ حذف السيرفر</button>
            `;
            dnsContainer.appendChild(dnsEntry);

            dnsEntry.querySelector('.remove-dns').addEventListener('click', function() {
                dnsEntry.remove();
            });
        });

        // إزالة سيرفر DNS موجود
        dnsContainer.querySelectorAll('.remove-dns').forEach(button => {
            button.addEventListener('click', function() {
                button.closest('div').remove();
            });
        });

        // إضافة وصف جديد بالعربية
        addDescriptionArBtn.addEventListener('click', function() {
            const descEntry = document.createElement('div');
            descEntry.style.cssText = "display: flex; gap: 0.5rem; margin-bottom: 0.5rem;";
            descEntry.innerHTML = `
                <input type="text" name="description_ar[]" required
                    style="background-color: #21212d; color: #c9c8dc; width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem; text-align: right;">
                <button type="button" class="remove-description-ar"
                    style="padding: 0.5rem; background-color: #dc2626; color: white; border-radius: 0.5rem; border: none; cursor: pointer;">-</button>
            `;
            descriptionArContainer.appendChild(descEntry);

            descEntry.querySelector('.remove-description-ar').addEventListener('click', function() {
                descEntry.remove();
            });
        });

        // إزالة وصف موجود بالعربية
        descriptionArContainer.querySelectorAll('.remove-description-ar').forEach(button => {
            button.addEventListener('click', function() {
                button.closest('div').remove();
            });
        });

        // إضافة وصف جديد بالإنجليزية
        addDescriptionEnBtn.addEventListener('click', function() {
            const descEntry = document.createElement('div');
            descEntry.style.cssText = "display: flex; gap: 0.5rem; margin-bottom: 0.5rem;";
            descEntry.innerHTML = `
                <input type="text" name="description_en[]" required
                    style="background-color: #21212d; color: #c9c8dc; width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
                <button type="button" class="remove-description-en"
                    style="padding: 0.5rem; background-color: #dc2626; color: white; border-radius: 0.5rem; border: none; cursor: pointer;">-</button>
            `;
            descriptionEnContainer.appendChild(descEntry);

            descEntry.querySelector('.remove-description-en').addEventListener('click', function() {
                descEntry.remove();
            });
        });

        // إزالة وصف موجود بالإنجليزية
        descriptionEnContainer.querySelectorAll('.remove-description-en').forEach(button => {
            button.addEventListener('click', function() {
                button.closest('div').remove();
            });
        });

        // توليد رقم تفعيل
        function generateActivationCode() {
            const digits = '0123456789';
            let code = '';
            const usedDigits = new Set();
            while (code.length < 10) {
                const randomDigit = digits[Math.floor(Math.random() * digits.length)];
                if (!usedDigits.has(randomDigit)) {
                    code += randomDigit;
                    usedDigits.add(randomDigit);
                }
            }
            document.getElementById('activation_code').value = code;
        }

        document.getElementById('regenerate_code').addEventListener('click', generateActivationCode);

        // تحديث الصورة بناءً على الباقة
        window.updateImage = function(select) {
            const categoryId = select.value;
            const imageField = document.getElementById('game_image');
            const images = @json($mainCategories->pluck('image', 'id'));
            imageField.value = images[categoryId] || 'verified_image.jpg';
        };

        // تحديث تاريخ الانتهاء بناءً على الخطط
        window.updateExpiryDate = function() {
            const subCategorySelect = document.querySelector('select[name="sub_categories[]"]');
            const selectedSubCategories = Array.from(subCategorySelect.selectedOptions).map(option => option.value);
            const subCategoriesData = @json($subCategories->mapWithKeys(function ($item) {
                return [$item->id => $item->duration];
            }));
            
            let maxDays = 30; // الافتراضي 30 يومًا
            selectedSubCategories.forEach(subCategoryId => {
                const duration = subCategoriesData[subCategoryId] || 30;
                if (duration > maxDays) {
                    maxDays = duration;
                }
            });

            const registrationDate = new Date(document.querySelector('input[name="registration_date"]').value);
            registrationDate.setDate(registrationDate.getDate() + parseInt(maxDays));
            const expiryDate = registrationDate.toISOString().split('T')[0];
            document.getElementById('expiry_date').value = expiryDate;
        };

        // تحديث تاريخ الانتهاء عند تحميل الصفحة إذا كان هناك تصنيفات فرعية مختارة
        if (document.querySelector('select[name="sub_categories[]"]').selectedOptions.length > 0) {
            updateExpiryDate();
        }
    });
</script>
@endsection