<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield(section: 'title')</title>
    <link rel="icon" type="image/png" href="https://joystick.zone/assets/img/favicon.png">
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @else
        <style>
        </style>
    @endif
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

    <style>
        body,
        html {
            font-family: 'Cairo', sans-serif !important;
        }

        body {
            font-family: "Cairo", sans-serif;
            background-color: #21212d;
            color: #1b1b18;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            direction: rtl;
        }

        li {
            list-style: none;
        }

        .antialiased {
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            /* background-color: #f9fafb; */
        }

        nav {
            background-color: #2c2c38;
            border-bottom: 1px solid #374151;
            padding: 0.625rem 1rem;
            position: fixed;
            left: 0;
            right: 0;
            top: 0;
            z-index: 50;
        }

        nav .flex {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
        }

        nav .justify-between {
            justify-content: space-between;
        }

        button[data-drawer-target] {
            padding: 0.5rem;
            margin-right: 0.5rem;
            color: #6b7280;
            border-radius: 0.5rem;
            cursor: pointer;
        }

        button[data-drawer-target]:hover {
            color: #111827;
            background-color: #f3f4f6;
        }

        button[data-drawer-target]:focus {
            background-color: #f3f4f6;
            ring: 2px solid #e5e7eb;
        }

        .dark button[data-drawer-target]:focus {
            background-color: #374151;
            ring: 2px solid #4b5563;
        }

        .dark button[data-drawer-target] {
            color: #9ca3af;
        }

        .dark button[data-drawer-target]:hover {
            background-color: #374151;
            color: #fff;
        }

        .sr-only {
            position: absolute;
            width: 1px;
            height: 1px;
            padding: 0;
            margin: -1px;
            overflow: hidden;
            clip: rect(0, 0, 0, 0);
            white-space: nowrap;
            border-width: 0;
        }

        a {
            text-decoration: none;
        }

        .flex {
            display: flex;
        }

        .items-center {
            align-items: center;
        }

        .justify-between {
            justify-content: space-between;
        }

        .mr-4 {
            margin-right: 1rem;
        }

        .w-12 {
            width: 3rem;
        }

        .z-10 {
            z-index: 10;
        }

        .text-2xl {
            font-size: 1.5rem;
            line-height: 2rem;
        }

        .text-white {
            color: #fff;
        }

        aside {
            position: fixed;
            top: 0;
            right: 0;
            z-index: 40;
            width: 16rem;
            height: 100vh;
            padding-top: 3.5rem;
            transition: transform 0.3s ease-in-out;
            transform: translateX(-100%);
            background-color: #fff;
            border-right: 1px solid #e5e7eb;
        }

        .md\:translate-x-0 {
            transform: translateX(0);
        }

        .overflow-y-auto {
            overflow-y: auto;
        }

        .py-5 {
            padding-top: 1.25rem;
            padding-bottom: 1.25rem;
        }

        .px-3 {
            padding-left: 0.75rem;
            padding-right: 0.75rem;
        }

        .h-full {
            height: 100%;
        }

        .dark aside {
            background-color: #1e1d32;
            border-color: #374151;
        }

        .md\:hidden {
            display: none;
        }

        .mb-2 {
            margin-bottom: 0.5rem;
        }

        .relative {
            position: relative;
        }

        .absolute {
            position: absolute;
        }

        .inset-y-0 {
            top: 0;
            bottom: 0;
        }

        .left-0 {
            left: 0;
        }

        .pointer-events-none {
            pointer-events: none;
        }

        .w-5,
        .h-5 {
            width: 1.25rem;
            height: 1.25rem;
        }

        .text-gray-500 {
            color: #6b7280;
        }

        .dark .text-gray-400 {
            color: #9ca3af;
        }

        input[type="text"] {
            background-color: #f9fafb;
            border: 1px solid #d1d5db;
            color: #111827;
            font-size: 0.875rem;
            line-height: 1.25rem;
            border-radius: 0.5rem;
            width: 100%;
            padding-left: 2.5rem;
            padding: 0.5rem;
        }

        input[type="text"]:focus {
            ring: 2px solid #3b82f6;
            border-color: #3b82f6;
        }

        .dark input[type="text"] {
            background-color: #374151;
            border-color: #4b5563;
            color: #fff;
        }

        .dark input[type="text"]::placeholder {
            color: #9ca3af;
        }

        .dark input[type="text"]:focus {
            ring: 2px solid #3b82f6;
            border-color: #3b82f6;
        }

        .space-y-2>*+* {
            margin-top: 0.5rem;
        }

        .p-2 {
            padding: 0.5rem;
        }

        .text-base {
            font-size: 1rem;
            line-height: 1.5rem;
        }

        .font-medium {
            font-weight: 500;
        }

        .text-gray-900 {
            color: #ffffff;
        }

        .rounded-lg {
            border-radius: 0.5rem;
        }

        .dark .text-white {
            color: #fff;
        }

        li a:hover {
            background-color: #47475a;

        }

        .dark li a:hover {
            background-color: #374151;
        }

        .group {
            position: relative;
        }

        .w-6,
        .h-6 {
            width: 1.5rem;
            height: 1.5rem;
        }

        .group:hover .group-hover\:text-gray-900 {
            color: #111827;
        }

        .dark .group:hover .dark\:group-hover\:text-white {
            color: #fff;
        }

        .mr-3 {
            margin-right: 0.35rem;
        }

        i {
            position: relative;
            top: 6px;
        }

        .border-t {
            border-top-width: 1px;
        }

        .border-gray-200 {
            border-color: #e5e7eb;
        }

        .dark .border-gray-700 {
            border-color: #374151;
        }

        .inline-flex {
            display: inline-flex;
        }

        .justify-center {
            justify-content: center;
        }

        .w-5,
        .h-5 {
            width: 1.25rem;
            height: 1.25rem;
        }

        .mx-2 {
            margin-left: 0.5rem;
            margin-right: 0.5rem;
        }

        .text-xs {
            font-size: 0.75rem;
            line-height: 1rem;
        }

        .font-semibold {
            font-weight: 600;
        }

        .bg-red-500 {
            background-color: #ef4444;
        }

        .rounded-full {
            border-radius: 9999px;
        }

        .bottom-\[-175px\] {
            bottom: -175px;
        }

        .cursor-pointer {
            cursor: pointer;
        }

        main {
            background-color: #21212d;
            padding: 1rem;
            padding-top: 5rem;
            min-height: 100vh;
        }

        @media (min-width: 768px) {
            main {
                margin-right: 16rem;
            }

            .md\:translate-x-0 {
                transform: translateX(0);
            }

            .md\:hidden {
                display: none;
            }
        }

        .paypal-button-container {
            width: 92% !important;
        }
    </style>
</head>

<body>
    <div class="">
        <nav>
            <div class="flex">
                <div class="flex justify-between items-center">
                    <a href="/ar/dashboard" class="flex items-center justify-between mr-4">
                        <img src="{{ asset('assets/img/joystik-logo.svg') }}" class="w-12 z-10" alt="Joystick Logo">
                    </a>
                </div>
            </div>
        </nav>

        <!-- Sidebar -->
        <aside class="md:translate-x-0"
            style="padding-bottom: 20px; height: -webkit-fill-available; top: 7px; background-color: #2c2c38; direction: rtl; right: 0px; left: unset;"
            aria-label="Sidenav" id="drawer-navigation">
            <div class="overflow-y-auto py-5 px-3 h-full">
                <form action="#" method="GET" class="md:hidden mb-2">
                    <label for="sidebar-search" class="sr-only">Search</label>
                    <div class="relative">
                        <div class="flex absolute inset-y-0 left-0 items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20"
                                xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" clip-rule="evenodd"
                                    d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z">
                                </path>
                            </svg>
                        </div>
                        <input type="text" name="search" id="sidebar-search" placeholder="Search" />
                    </div>
                </form>
                <ul class="space-y-2" style=" padding-right: 0px;   direction: rtl;">
                    {{-- <li>
                        <a href="{{ route('plans.index', ['lang' => request()->route('lang')]) }}"
                            class="group flex items-center p-2 text-base font-medium text-gray-900 rounded-lg"
                            aria-label="عرض الباقات">
                            <i class="fas fa-list w-6 h-6 text-gray-500 group-hover:text-gray-900"></i>
                            <span class="mr-3">عرض جميع الخطط</span>
                        </a>
                    </li> --}}
{{-- 
                    <li>
                        <a href="{{ route('plans.create', ['lang' => request()->route('lang')]) }}"
                            class="group flex items-center p-2 text-base font-medium text-gray-900 rounded-lg"
                            aria-label="إضافة تصنيف رئيسي">
                            <i class="fas fa-plus w-6 h-6 text-gray-500 group-hover:text-gray-900"></i>
                            <span class="mr-3">إضافة خطة </span>
                        </a>
                    </li>

                    <div style="border-top: 1px solid; border-color: #E5E7EB"></div> --}}


                    <li>
                        <a href="{{ route('admin.main_categories.index', ['lang' => request()->route('lang')]) }}"
                            class="group flex items-center p-2 text-base font-medium text-gray-900 rounded-lg"
                            aria-label="عرض الباقات">
                            <i class="fas fa-list w-6 h-6 text-gray-500 group-hover:text-gray-900"></i>
                            <span class="mr-3">عرض جميع الباقات</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.main_categories.create', ['lang' => request()->route('lang')]) }}"
                            class="group flex items-center p-2 text-base font-medium text-gray-900 rounded-lg"
                            aria-label="إضافة تصنيف رئيسي">
                            <i class="fas fa-plus w-6 h-6 text-gray-500 group-hover:text-gray-900"></i>
                            <span class="mr-3">إضافة باقة جديدة</span>
                        </a>
                    </li>

                    <div style="border-top: 1px solid; border-color: #E5E7EB"></div>

                    <li>
                        <a href="{{ route('admin.sub_categories.index', ['lang' => request()->route('lang')]) }}"
                            class="group flex items-center p-2 text-base font-medium text-gray-900 rounded-lg"
                            aria-label="عرض الخطط">
                            <i class="fas fa-sitemap w-6 h-6 text-gray-500 group-hover:text-gray-900"></i>
                            <span class="mr-3">عرض خطط الباقات</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('admin.sub_categories.create', ['lang' => request()->route('lang')]) }}"
                            class="group flex items-center p-2 text-base font-medium text-gray-900 rounded-lg"
                            aria-label="إضافة تصنيف فرعي">
                            <i class="fas fa-plus w-6 h-6 text-gray-500 group-hover:text-gray-900"></i>
                            <span class="mr-3">إضافة خطة باقة جديدة</span>
                        </a>
                    </li>
                    <div style="border-top: 1px solid; border-color: #E5E7EB"></div>

                    <li>
                        <a href="{{ route('games.index', ['lang' => request()->route('lang')]) }}"
                            class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg">
                            <i class="fas fa-gamepad w-6 h-6 text-gray-500 group-hover:text-gray-900"></i>
                            <span class="mr-3">عرض جميع الإشتراكات</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('games.create', ['lang' => request()->route('lang')]) }}"
                            class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg">
                            <i class="fas fa-plus w-6 h-6 text-gray-500 group-hover:text-gray-900"></i>
                            <span class="mr-3">إضافة إشتراك جديد</span>
                        </a>
                    </li>
                    <div style="border-top: 1px solid; border-color: #E5E7EB"></div>

                    <li>
                        <a href="{{ route('news.index', ['lang' => request()->route('lang')]) }}"
                            class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg">
                            <i class="fas fa-newspaper w-6 h-6 text-gray-500 group-hover:text-gray-900"></i>
                            <span class="mr-3">عرض جميع الأخبار</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('news.create', ['lang' => request()->route('lang')]) }}"
                            class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg">
                            <i class="fas fa-plus w-6 h-6 text-gray-500 group-hover:text-gray-900"></i>
                            <span class="mr-3">إضافة خبر جديد</span>
                        </a>
                    </li>
                    <div style="border-top: 1px solid; border-color: #E5E7EB"></div>

                    <li>
                        <a href="{{ route('know-us.index', ['lang' => request()->route('lang')]) }}"
                            class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg">
                            <i class="fas fa-info w-6 h-6 text-gray-500 group-hover:text-gray-900"></i>
                            <span class="mr-3">عرض معلومات عنا</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('know-us.create', ['lang' => request()->route('lang')]) }}"
                            class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg">
                            <i class="fas fa-plus w-6 h-6 text-gray-500 group-hover:text-gray-900"></i>
                            <span class="mr-3">إضافة معلومات عنا</span>
                        </a>
                    </li>

                    <div style="border-top: 1px solid; border-color: #E5E7EB"></div>

                    <li>
                        <a href="{{ route('faqs.index', ['lang' => request()->route('lang')]) }}"
                            class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg">
                            <i
                                class="fa-solid fa-clipboard-question w-6 h-6 text-gray-500 group-hover:text-gray-900"></i>
                            <span class="mr-3">عرض الأسئلة الشائعة</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('faqs.create', ['lang' => request()->route('lang')]) }}"
                            class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg">
                            <i class="fas fa-plus w-6 h-6 text-gray-500 group-hover:text-gray-900"></i>
                            <span class="mr-3">إضافة سؤال</span>
                        </a>
                    </li>
                    <div style="border-top: 1px solid; border-color: #E5E7EB"></div>

                    <li>
                        <a href="{{ route('contact.dashboard', ['lang' => request()->route('lang')]) }}"
                            class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg">
                            <i class="fas fa-comments w-6 h-6 text-gray-500 group-hover:text-gray-900"></i>
                            <span class="mr-3">الرسائل</span>
                            <span
                                class="inline-flex items-center justify-center w-5 h-5 mx-2 text-xs font-semibold text-white bg-red-500 rounded-full">
                                {{ \App\Models\ContactMessage::where('is_read', false)->count() }}
                            </span>
                        </a>
                    </li>

                    <div style="border-top: 1px solid; border-color: #E5E7EB"></div>

                    <li>
                        <a href="{{ route('terms.index', ['lang' => request()->route('lang')]) }}"
                            class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg">
                            <i class="fa-brands fa-teamspeak w-6 h-6 text-gray-500 group-hover:text-gray-900"></i>
                            <span class="mr-3">عرض الشروط والأحكام</span>
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('terms.create', ['lang' => request()->route('lang')]) }}"
                            class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg">
                            <i class="fas fa-plus w-6 h-6 text-gray-500 group-hover:text-gray-900"></i>
                            <span class="mr-3">تعديل شروط وأحكام</span>
                        </a>
                    </li>

                    <div style="border-top: 1px solid; border-color: #E5E7EB"></div>

                    <li>
                        <a href="{{ route('privacy-policy.index', ['lang' => request()->route('lang')]) }}"
                            class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg">
                            <i class="fas fa-user-secret w-6 h-6 text-gray-500 group-hover:text-gray-900"></i>
                            <span class="mr-3">سياسة الخصوصية</span>
                        </a>
                    </li>
                    <div style="border-top: 1px solid; border-color: #E5E7EB"></div>
                    <li>
                        <a href="{{ route('banners.index', ['lang' => request()->route('lang')]) }}"
                            class="flex items-center p-2 text-base font-medium text-gray-900 rounded-lg">
                            <i class="fas fa-ad w-6 h-6 text-gray-500 group-hover:text-gray-900"></i>
                            <span class="mr-3">{{ $lang == 'ar' ? 'ادارة الواجهات' : 'Manage Banners' }}</span>
                        </a>
                    </li>

                    <div style="border-top: 1px solid; border-color: #E5E7EB"></div>

                    <form method="POST" class="" action="{{ route('logout') }}">
                        @csrf

                        <a :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();">
                            <div
                                class="cursor-pointer flex items-center p-2 text-base font-medium text-gray-900 rounded-lg">
                                <i class="fas fa-sign-out-alt w-6 h-6 text-gray-500 group-hover:text-gray-900"></i>
                                <span class="mr-3">Logout</span>
                            </div>
                        </a>
                    </form>
                </ul>
            </div>
        </aside>

        <main>
            @yield('content')
        </main>
    </div>

</body>

</html>
