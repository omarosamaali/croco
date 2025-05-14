@extends('layouts.adminLayout')

@section('title', 'إضافة إشتراك جديد')

@section('content')
    <div style="padding: 1rem; padding-top: 5rem;">
        <div
            style="max-width: 48rem; margin-bottom: 5rem; margin-left: auto; margin-right: auto; background-color: #2c2c38; padding: 1.5rem; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); border-radius: 0.5rem;">
            <h2 style="font-size: 1.5rem; font-weight: 600; text-align: center; color: #c9c8dc; margin-bottom: 1rem;">إضافة
                إشتراك جديد 🖥️</h2>

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

            <form action="{{ route('games.store', ['lang' => $lang]) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- الاسم والبريد الإلكتروني -->
                <div style="display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 1rem;">
                    <div>
                        <label
                            style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">الاسم</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            style="text-align: right; background-color: #21212d; color: #c9c8dc; width: 98%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
                    </div>
                    <div>
                        <label
                            style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">البريد
                            الإلكتروني</label>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            style="background-color: #21212d; color: #c9c8dc; width: 98%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
                    </div>
                </div>

                <!-- قائمة الدولة -->
                <div style="margin-bottom: 1rem; text-align: right;">
                    <label
                        style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">الدولة</label>
                    <select name="country" required
                        style="background-color: #21212d; color: #c9c8dc; width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem; direction: rtl;">
                        <option value="" disabled selected>اختر الدولة</option>
                        <option value="EG" {{ old('country') == 'EG' ? 'selected' : '' }}>مصر</option>
                        <option value="SA" {{ old('country') == 'SA' ? 'selected' : '' }}>السعودية</option>
                        <option value="AE" {{ old('country') == 'AE' ? 'selected' : '' }}>الإمارات</option>
                        <option value="KW" {{ old('country') == 'KW' ? 'selected' : '' }}>الكويت</option>
                        <option value="QA" {{ old('country') == 'QA' ? 'selected' : '' }}>قطر</option>
                        <option value="BH" {{ old('country') == 'BH' ? 'selected' : '' }}>البحرين</option>
                        <option value="OM" {{ old('country') == 'OM' ? 'selected' : '' }}>عمان</option>
                        <option value="JO" {{ old('country') == 'JO' ? 'selected' : '' }}>الأردن</option>
                        <option value="LB" {{ old('country') == 'LB' ? 'selected' : '' }}>لبنان</option>
                        <option value="MA" {{ old('country') == 'MA' ? 'selected' : '' }}>المغرب</option>
                        <option value="TN" {{ old('country') == 'TN' ? 'selected' : '' }}>تونس</option>
                        <option value="DZ" {{ old('country') == 'DZ' ? 'selected' : '' }}>الجزائر</option>
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
                        <option value="" disabled selected>اختر الباقة</option>
                        @foreach ($mainCategories as $category)
                            <option value="{{ $category->id }}" data-image="{{ $category->image_url }}"
                                {{ old('main_category') == $category->id ? 'selected' : '' }}>
                                {{ $category->name_ar }}
                            </option>
                        @endforeach
                    </select>
                    <!-- حاوية الصورة -->
                    <div id="category-image-container" style="margin-top: 0.5rem; text-align: center;">
                        <img id="category-image" src="" alt="صورة الباقة"
                            style="max-width: 100%; height: 200px !important; width: 200px !important; display: none; border-radius: 0.5rem;">
                    </div>
                </div>
                <!-- صورة اللعبة (مخفية لأنها أوتوماتيكية) -->
                <input type="hidden" name="image" id="game_image">

                <!-- الخطط -->
                <div style="margin-bottom: 1rem; text-align: right;">
                    <label
                        style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">
                        خطة الباقة</label>
                    <select name="sub_categories[]" required
                        style="background-color: #21212d; color: #c9c8dc; width: 100%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem; direction: rtl;"
                        onchange="updateExpiryDate()">
                        <option selected>
                            اختر الخطة
                        </option>
                        @foreach ($subCategories as $subCategory)
                            <option value="{{ $subCategory->id }}" data-duration="{{ $subCategory->duration ?? 30 }}"
                                {{ in_array($subCategory->id, old('sub_categories', [])) ? 'selected' : '' }}>
                                اسم الخطة : {{ $subCategory->name_ar }}
                                -
                                التكلفة : {{ $subCategory->price }}
                                -
                                المدة : {{ $subCategory->duration }}

                            </option>
                        @endforeach
                    </select>
                    {{-- <p style="color: #c9c8dc; font-size: 0.75rem; margin-top: 0.25rem;">
                        اضغط على Ctrl أو Cmd لاختيار أكثر من خطة
                    </p> --}}
                    @error('sub_categories')
                        <span style="color: #ef4444;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- الوصف (عربي) -->
                <div style="margin-bottom: 1rem; text-align: right;">
                    <label
                        style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">
                        الوصف (عربي)</label>
                    <div id="description_ar_container">
                        @foreach (old('description_ar', ['']) as $description)
                            <input type="text" name="description_ar[]" value="{{ $description }}" required
                                style="background-color: #21212d; color: #c9c8dc; width: 98%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem; margin-bottom: 0.5rem; text-align: right;">
                        @endforeach
                    </div>
                    <button type="button" onclick="addDescriptionField('description_ar')"
                        style="padding: 0.5rem; background-color: #16a34a; color: white; border-radius: 0.5rem; border: none; cursor: pointer;">
                        إضافة وصف آخر
                    </button>
                    @error('description_ar')
                        <span style="color: #ef4444;">{{ $message }}</span>
                    @enderror
                </div>

                <!-- الوصف (إنجليزي) -->
                <div style="margin-bottom: 1rem; text-align: right;">
                    <label
                        style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">
                        الوصف (إنجليزي)</label>
                    <div id="description_en_container">
                        @foreach (old('description_en', ['']) as $description)
                            <input type="text" name="description_en[]" value="{{ $description }}" required
                                style="background-color: #21212d; color: #c9c8dc; width: 98%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem; margin-bottom: 0.5rem;">
                        @endforeach
                    </div>
                    <button type="button" onclick="addDescriptionField('description_en')"
                        style="padding: 0.5rem; background-color: #16a34a; color: white; border-radius: 0.5rem; border: none; cursor: pointer;">
                        Add Another Description
                    </button>
                    @error('description_en')
                        <span style="color: #ef4444;">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label
                        style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">اسم
                        المستخدم</label>
                    <input type="text" name="username" value="{{ old('username') }}" required
                        style="text-align: right; background-color: #21212d; color: #c9c8dc; width: 98%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
                </div>
                <!-- تاريخ التسجيل وتاريخ الانتهاء -->
                <div style="display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 1rem; margin-top: 1rem;">
                    <div>
                        <label
                            style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">تاريخ
                            التسجيل</label>
                        <input type="text" name="registration_date" value="{{ now()->format('Y-m-d') }}" readonly
                            style="background-color: #21212d; color: #c9c8dc; width: 98%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
                    </div>
                    <div>
                        <label
                            style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">تاريخ
                            الانتهاء</label>
                        <input type="text" name="expiry_date" id="expiry_date" readonly
                            style="background-color: #21212d; color: #c9c8dc; width: 98%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
                    </div>
                </div>

                <div style="margin-bottom: 1rem; text-align: right;">
                    <label
                        style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">بيانات
                        سيرفر DNS</label>
                    <div id="dns-container">
                        <div
                            style="background-color: #2c2c38; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                            <div style="display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 0.5rem;">
                                <div>
                                    <label
                                        style="display: block; color: #c9c8dc; font-size: 0.75rem; margin-bottom: 0.25rem;">اسم
                                        المستخدم</label>
                                    <input type="text" name="dns_usernames[]"
                                        style="background-color: #21212d; color: #c9c8dc; text-align: right; width: 98%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;"
                                        placeholder="مثال: user1">
                                </div>
                                <div>
                                    <label
                                        style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem; text-align: right;">كلمة
                                        المرور</label>
                                    <input type="password" name="password" required
                                        style="background-color: #21212d; color: #c9c8dc; width: 98%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
                                </div>
                                <div>
                                    <label
                                        style="display: block; color: #c9c8dc; font-size: 0.75rem; margin-bottom: 0.25rem;">رابط
                                        DNS</label>
                                    <input type="url" name="dns_urls[]"
                                        style="background-color: #21212d; color: #c9c8dc; width: 98%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;"
                                        placeholder="https://dns.example.com">
                                </div>
                                <div>
                                    <label
                                        style="display: block; color: #c9c8dc; font-size: 0.75rem; margin-bottom: 0.25rem;">تاريخ
                                        انتهاء DNS</label>
                                    <input type="date" name="dns_expiry_date" required
                                        style="background-color: #21212d; color: #c9c8dc; width: 98%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;">
                                </div>
                            </div>
                        </div>
                    </div>
                    <button type="button" id="add-dns"
                        style="display: none; margin-top: 0.5rem; padding: 0.5rem 0.75rem; background-color: #2563eb; color: white; border-radius: 0.5rem; border: none; cursor: pointer;">
                        + إضافة سيرفر DNS آخر
                    </button>
                </div>

                <!-- رقم التفعيل -->
                <div style="margin-bottom: 1rem; text-align: right;">
                    <label
                        style="display: block; color: #c9c8dc; font-size: 0.875rem; font-weight: 500; margin-bottom: 0.25rem;">رقم
                        التفعيل</label>
                    <div style="display: flex; gap: 0.5rem;">
                        <input type="text" name="activation_code" id="activation_code" readonly
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
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>فعال</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>غير فعال</option>
                        <option value="expired" {{ old('status') == 'expired' ? 'selected' : '' }}>منتهي</option>
                        <option value="canceled" {{ old('status') == 'canceled' ? 'selected' : '' }}>ملغي</option>
                        <option value="pending_dns" {{ old('status') == 'pending_dns' ? 'selected' : '' }}>بإنتظار DNS
                        </option>
                    </select>
                </div>

                <div style=" margin-top: 1.5rem; display: flex; justify-content: flex-end;">
                    <button type="submit"
                        style="padding: 0.5rem 1rem; background-color: #16a34a; color: white; border-radius: 0.5rem; border: none; cursor: pointer;">
                        إضافة الإشتراك
                    </button>
                </div>
            </form>

            <!-- إضافة الـ CSS العام لتطبيق font-family: 'Cairo' -->
            <style>
                div,
                h2,
                label,
                input,
                select,
                button,
                li {
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
            </style>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addDnsBtn = document.getElementById('add-dns');
            const dnsContainer = document.getElementById('dns-container');

            // إضافة سيرفر DNS جديد
            addDnsBtn.addEventListener('click', function() {
                const dnsEntry = document.createElement('div');
                dnsEntry.style.cssText =
                    "background-color: #2c2c38; padding: 0.75rem; border-radius: 0.5rem; margin-bottom: 1rem;";
                dnsEntry.innerHTML = `
                <div style="display: grid; grid-template-columns: 1fr; gap: 1rem; margin-bottom: 0.5rem;">
                    <div>
                        <label style="display: block; color: #c9c8dc; font-size: 0.75rem; margin-bottom: 0.25rem;">اسم المستخدم</label>
                        <input type="text" name="dns_usernames[]" style="width: 97%; direction: rtl; background-color: #21212d; color: #c9c8dc; text-align: right; width: 98%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem;" placeholder="مثال: user1">
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

                const style = document.createElement('style');
                style.textContent = `
                .remove-dns:hover { background-color: #b91c1c; }
                @media (min-width: 768px) {
                    div[style*="grid-template-columns: 1fr"]:last-child { grid-template-columns: 1fr 1fr; }
                }
            `;
                dnsEntry.appendChild(style);
            });

            // إضافة وصف جديد
            window.addDescriptionField = function(container) {
                const containerElement = document.getElementById(`${container}_container`);
                const newInput = document.createElement('input');
                newInput.type = 'text';
                newInput.name = `${container}[]`;
                newInput.required = true;
                newInput.style.cssText = 'background-color: #21212d; color: #c9c8dc; width: 98ಸ್‌ಕನ್ನಡ: width: 98%; padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem; margin-bottom: 0.5rem; text-align: right;';
                if (container === 'description_ar') {
                    newInput.style.textAlign = 'right';
                }
                containerElement.appendChild(newInput);
            };

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

            generateActivationCode();
            document.getElementById('regenerate_code').addEventListener('click', generateActivationCode);

            // تحديث الصورة بناءً على الباقة
            window.updateImage = function(select) {
                const categoryId = select.value;
                const selectedOption = select.options[select.selectedIndex];
                const imageUrl = selectedOption.getAttribute('data-image') || '';
                const imageContainer = document.getElementById('category-image');

                if (imageUrl) {
                    imageContainer.src = imageUrl;
                    imageContainer.style.display = 'block';
                } else {
                    imageContainer.src = '';
                    imageContainer.style.display = 'none';
                }

                const imageField = document.getElementById('game_image');
                imageField.value = imageUrl;
            };

            // تحديث تاريخ الانتهاء بناءً على أطول مدة في الخطط
            window.updateExpiryDate = function() {
                const select = document.querySelector('select[name="sub_categories[]"]');
                const selectedOptions = Array.from(select.selectedOptions);
                if (selectedOptions.length === 0) {
                    document.getElementById('expiry_date').value = '';
                    return;
                }

                let maxDays = 30;
                selectedOptions.forEach(option => {
                    const days = parseInt(option.getAttribute('data-duration')) || 30;
                    if (days > maxDays) {
                        maxDays = days;
                    }
                });

                const today = new Date();
                today.setDate(today.getDate() + maxDays);
                const expiryDate = today.toISOString().split('T')[0];
                document.getElementById('expiry_date').value = expiryDate;
            };

            // تشغيل الدالة عند تحميل الصفحة إذا كان هناك قيم محددة مسبقًا
            if (document.querySelector('select[name="sub_categories[]"]').selectedOptions.length > 0) {
                updateExpiryDate();
            }
        });
    </script>
@endsection