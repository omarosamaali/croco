@extends('layouts.adminLayout')

@section('title', 'إضافة واجهة جديدة')

@section('content')
    <div class="container">
        <div class="form-container">
            <h2 class="title">
                {{ $lang == 'ar' ? 'إضافة واجهة جديدة 🖼️' : 'Add New Banner 🖼️' }}
            </h2>

            @if ($errors->any())
                <div class="error-box">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('banners.store', ['lang' => $lang]) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- الصورة -->
                <div class="form-group">
                    <label class="form-label">
                        {{ $lang == 'ar' ? 'صورة الواجهة' : 'Banner Image' }}
                    </label>
                    <input type="file" name="image" accept="image/*" required class="file-input">
                </div>

                <!-- تاريخ ووقت البدء -->
                <div class="form-group">
                    <label class="form-label">
                        {{ $lang == 'ar' ? 'تاريخ ووقت البدء' : 'Start Date and Time' }}
                    </label>
                    <input type="datetime-local" name="start_date" value="{{ old('start_date') }}" required
                        class="input-field">
                </div>

                <!-- تاريخ ووقت الانتهاء -->
                <div class="form-group">
                    <label class="form-label">
                        {{ $lang == 'ar' ? 'تاريخ ووقت الانتهاء' : 'End Date and Time' }}
                    </label>
                    <input type="datetime-local" name="expiration_date" value="{{ old('expiration_date') }}" required
                        class="input-field">
                </div>

                <!-- الموقع -->
                <div class="form-group">
                    <label class="form-label">
                        {{ $lang == 'ar' ? 'الموقع' : 'Location' }}
                    </label>
                    <select style="width: 100%;" name="location" id="location" required class="input-field">
                        <option value="location" {{ old('location') == 'location' ? 'selected' : '' }}>
                            {{ $lang == 'ar' ? 'الموقع' : 'Location' }}
                        </option>
                        <option value="app" {{ old('location') == 'app' ? 'selected' : '' }}>
                            {{ $lang == 'ar' ? 'التطبيق' : 'App' }}
                        </option>
                    </select>
                </div>

                <!-- التصنيف (يظهر فقط إذا تم اختيار التطبيق) -->
                <div class="form-group" id="category-group"
                    style="display: {{ old('location', 'location') == 'app' ? 'block' : 'none' }};">
                    <label class="form-label">
                        {{ $lang == 'ar' ? 'التصنيف' : 'Category' }}
                    </label>
                    <input type="text" name="category" value="{{ old('category') }}" class="input-field"
                        id="category-input" {{ old('location', 'location') == 'app' ? 'required' : '' }}>
                </div>

                <!-- الحالة -->
                <div class="form-group">
                    <label class="form-label">
                        {{ $lang == 'ar' ? 'الحالة' : 'Status' }}
                    </label>
                    <div class="checkbox-container">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                            {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                        <label style="color: white;" for="is_active">
                            {{ $lang == 'ar' ? 'نشط' : 'Active' }}
                        </label>
                    </div>
                </div>

                <!-- الأزرار -->
                <div class="button-group">
                    <a href="{{ route('banners.index', ['lang' => $lang]) }}" class="btn btn-secondary">
                        {{ $lang == 'ar' ? 'العودة للقائمة' : 'Back to List' }}
                    </a>
                    <button type="submit" style="border: 0px;" class="btn btn-primary">
                        {{ $lang == 'ar' ? 'إضافة الواجهة' : 'Add Banner' }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <style>
        .container {
            padding: 20px;
            padding-top: 80px;
            display: flex;
            justify-content: center;
            font-family: 'cairo' !important;
        }
        * {
            font-family: 'cairo' !important;
        }
        .form-container {
            width: 582px;
            background-color: #2c2c38;
            padding: 24px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            color: #c9c8dc;
            margin-bottom: 16px;
        }

        .error-box {
            background-color: #ff4d4d;
            color: #c9c8dc;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 16px;
        }

        .form-group {
            margin-bottom: 16px;
        }

        .form-label {
            display: block;
            color: #c9c8dc;
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 6px;
        }

        .file-input,
        .input-field {
            width: 97%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background-color: #21212d;
            color: #c9c8dc;
        }

        .checkbox-container {
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .checkbox-container input {
            margin-left: 8px;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 24px;
        }

        .btn {
            padding: 10px 16px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            color: white;
            transition: background-color 0.3s;
            display: inline-block;
        }

        .btn-primary {
            background-color: #28a745;
        }

        .btn-primary:hover {
            background-color: #218838;
        }

        .btn-secondary {
            background-color: #6c757d;
        }

        .btn-secondary:hover {
            background-color: #5a6268;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const locationSelect = document.getElementById('location');
            const categoryGroup = document.getElementById('category-group');
            const categoryInput = document.getElementById('category-input');

            function toggleCategoryField() {
                if (locationSelect.value === 'app') {
                    categoryGroup.style.display = 'block';
                    categoryInput.setAttribute('required', 'required');
                } else {
                    categoryGroup.style.display = 'none';
                    categoryInput.removeAttribute('required');
                }
            }

            locationSelect.addEventListener('change', toggleCategoryField);
            toggleCategoryField(); // Initial check
        });
    </script>
@endsection
