<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@vite(['resources/css/app.css', 'resources/js/app.js', 'resources/js/translation.js'])

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield(section: 'title')</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    <script src="{{ asset('js/main.js') }}"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css" />
</head>

<body class="body">
    <div class="popup" id="appPopup">
        <div class="popup-content">
            <div class="popup-text-container">
                <button class="close-btn" onclick="closePopup()">×</button>
                <img src="{{ asset('assets/img/croco-icon.png') }}" class="footer-image" alt="">
                <div class="texts">
                    <p>{{ $lang == 'ar' ? 'حمل تطبيق' : 'Download App' }}</p>
                    <p>CROCO</p>
                </div>
            </div>
            <div style="display: flex; align-items: center; justify-content: center; gap: 10px;">
                <a onclick="downloadApp()">
                    <img src="{{ asset('assets/img/ios.svg') }}" style="width: 30px;" alt="">
                </a>
                <a onclick="downloadApp()">
                    <img src="{{ asset('assets/img/googleplay.svg') }}"
                        style="width: 30px; border-radius: 0px !important;" alt="">
                </a>
                <a>
                    <img src="{{ asset('assets/img/3.webp') }}" style="width: 30px;" alt="">
                </a>
            </div>
        </div>
    </div>
    <div class="grid-lines">
        <div class="lines horizontal-lines">
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
            <div class="line"></div>
        </div>
        <div class="lines vertical-lines">
            <div class="line-vertical"></div>
            <div class="line-vertical"></div>
            <div class="line-vertical"></div>
            <div class="line-vertical"></div>
            <div class="line-vertical"></div>
            <div class="line-vertical"></div>
            <div class="line-vertical"></div>
            <div class="line-vertical"></div>
            <div class="line-vertical"></div>
            <div class="line-vertical"></div>
        </div>
    </div>
    <header class="navbar">
        @if (Route::has('login'))
            <nav class="navbar-nav">
                <ul class="nav-links left-links">
                    <li style="min-width: 121px;">
                        <a href="{{ url(app()->getLocale() . '/plans') }}"
                            style="background-image: linear-gradient(to right, #A3B740, #bad62d); color: #000000; 
                            padding: 5px 25px; border-radius: 39px;">
                            {{ $lang == 'ar' ? 'اشترك الان' : 'Sign up' }}
                        </a>
                    </li>
                    <li>
                        <?php
                        $currentPath = request()->path();
                        $segments = explode('/', $currentPath);
                        $currentLang = array_shift($segments); // Remove current language
                        $cleanPath = implode('/', $segments);
                        $newLocale = $currentLang === 'en' ? 'ar' : 'en';
                        $newUrl = url("/{$newLocale}/{$cleanPath}");
                        ?>
                        <a href="{{ $newUrl }}">
                            {{ $lang == 'ar' ? 'English' : 'العربية' }}
                        </a>
                    </li>
                    <li style="min-width: 121px;">
                        <a href="{{ url(app()->getLocale() . '/privacy-policy') }}">
                            {{ $lang == 'ar' ? 'سياسة الخصوصية' : 'Privacy Policy' }}
                        </a>
                    </li>
                    <li style="min-width: 111px;"><a href="{{ url(app()->getLocale() . '/terms-and-conditions') }}">
                            {{ $lang == 'ar' ? 'الشروط والأحكام' : 'Terms & Conditions' }}
                        </a></li>
                </ul>

                <div class="logo-container">
                    <a href="/" class="logo-link">
                        <img src="{{ asset('assets/img/joystik-logo.svg') }}" class="logo" alt="Joystick Logo">
                    </a>
                </div>

                <ul class="nav-links right-links">
                    <li style="min-width: 108px;"><a href="{{ url(app()->getLocale() . '/faq') }}">
                            {{ $lang == 'ar' ? 'الأسئلة الشائعة' : 'FAQs' }}
                        </a></li>
                    <li style="min-width: 90px;"><a href="{{ url(app()->getLocale() . '/contact-us') }}">
                            {{ $lang == 'ar' ? 'تواصل معنا' : 'Contact us' }}
                        </a></li>
                    <li style="min-width: 50px;"><a href="{{ url(app()->getLocale() . '/know-us') }}">
                            {{ $lang == 'ar' ? 'من نحن' : 'Know us' }}
                        </a>
                    </li>
                    <li style="min-width: 50px;"><a href="{{ url(app()->getLocale() . '/news') }}">
                            {{ $lang == 'ar' ? 'الأخبار' : 'News' }}
                        </a>
                    </li>

                    <li><a href="/">الرئيسية</a></li>
                </ul>
                <button id="sidebarToggle" style="z-index: 999999999999999;" class="sidebar-toggle-btn">
                    <i class="fas fa-bars" style="font-size: 20px;"></i>
                </button>

            </nav>
        @endif
    </header>
    <sidebar class="sidebar" id="sidebar" style="background: #fdcd0b !important;">
        <button id="close-sidebar"
            style="position: absolute; top: 10px; right: 10px; background: #e1b500; border: none; border-radius: 50%; width: 30px; height: 30px; cursor: pointer; display: flex; align-items: center; justify-content: center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                fill="none" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>

        <a href="/" class="logo-link">
            <img src="{{ asset('assets/img/joystik-logo.svg') }}" style="width: 120px !important; margin-top: 10px;"
                class="logo" alt="Joystick Logo">
        </a>
        <div id="by-now-container"
            style="width: 95%; margin-top: 10px; margin-left: 20px !important; margin-right: 20px !important; padding-top: 0px !important; padding-bottom: 0px !important;">
            <a href="" id="by-now"
                style="width: unset !important; background-image: linear-gradient(to right, #A3B740, #bad62d)">اشترك
                الان</a>
        </div>

        <div id="by-now-container-2"
            style="display: flex !important;width: 90%; flex-direction: column; text-align: right; background: #e1b500; margin-top: 8px; border-radius: 27px; padding: 10px 27px;"
            class="mobile-links">
            <p><a href="/">الرئيسية</a></p>
            <p><a href="/news">الأخبار</a></p>
            <p><a href="/about">من نحن</a></p>
            <p><a href="/contact">تواصل معنا</a></p>
            <p><a href="/faq">الأسئلة الشائعة</a></p>
            <p><a href="/terms">الشروط والأحكام</a></p>
            <p><a href="/privacy">سياسة الخصوصية</a></p>
        </div>

        <div id="by-now-container-2"
            style="display: flex !important;width: 90%; flex-direction: column; text-align: right; background: #e1b500; margin-top: 8px; border-radius: 27px; padding: 10px 27px;"
            class="mobile-links">
            <p>
                <a style="display: flex !important; justify-content: space-between !important; align-items: center !important;"
                    href="{{ app()->getLocale() == 'ar' ? '/en' : '/ar' }}">
                    <span style="display: flex !important; align-items: center !important; gap: 15px;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                            class="ltr:mr-2 rtl:ml-2">
                            <g fill="none" fill-rule="evenodd">
                                <g>
                                    <g>
                                        <g>
                                            <g>
                                                <g>
                                                    <path d="M0 0H24V24H0z"
                                                        transform="translate(-284 -183) translate(100 64) translate(0 111) translate(121.79 8) translate(62.21)">
                                                    </path>
                                                    <path fill="black"
                                                        d="M12 2.25c5.385 0 9.75 4.365 9.75 9.75s-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12 6.615 2.25 12 2.25zm2.81 10.5H9.19c.12 2.593.86 5.163 2.22 7.479.195.014.392.021.59.021.198 0 .395-.007.59-.020 1.36-2.317 2.1-4.887 2.22-7.48zm-7.122 0H3.784c.303 3.358 2.617 6.136 5.728 7.118-1.113-2.247-1.72-4.674-1.824-7.117zm12.528 0h-3.904c-.103 2.444-.711 4.87-1.823 7.118 3.11-.982 5.424-3.76 5.727-7.117zM9.511 4.133l-.164.054C6.319 5.214 4.08 7.952 3.784 11.25h3.904c.103-2.443.71-4.87 1.823-7.118zM12 3.75c-.198 0-.395.007-.59.020-1.36 2.317-2.101 4.888-2.22 7.48h5.62c-.119-2.592-.86-5.163-2.22-7.479-.195-.014-.392-.021-.59-.021zm2.488.382l.103.21c1.047 2.188 1.621 4.54 1.721 6.908h3.904c-.302-3.358-2.617-6.135-5.728-7.118z"
                                                        transform="translate(-284 -183) translate(100 64) translate(0 111) translate(121.79 8) translate(62.21)">
                                                    </path>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </g>
                        </svg>
                        Language
                    </span>
                    <span style="display: flex !important; align-items: center !important; gap: 15px;">
                        {{ app()->getLocale() == 'ar' ? 'English (EN)' : 'العربية (AR)' }}
                    </span>
                </a>
            </p>
        </div>
        <div class="footer-text" style="align-items: center; justify-content: center; display: flex;">
            <p class="footer-message" style="margin-top: 0px; position: relative; top: -23px;">
                <img src="{{ asset('assets/img/footer-logo.webp') }}"
                    style=" z-index: 99999999; position: relative; width: 130px; bottom: -33px;" alt="">
            </p>
            <p class="footer Rights" style="color: black;">
                {{ $lang == 'ar' ? '© ٢٠٢٥ البوابة. جميع الحقوق محفوظة' : '© 2025 The Gate. All Rights Reserved.' }}
            </p>
        </div>
    </sidebar>
    @yield('content')


    <footer class="footer" style="z-index: 9999;">
        <div class="footer-container">
            <div class="footer-left">
                <div class="footer-links">
                </div>
                <div class="footer-text">
                    <p class="footer-message" style="">
                        <img src="{{ asset('assets/img/footer-logo.webp') }}"
                            style=" z-index: 99999999; position: relative; width: 105px; bottom: -33px;"
                            alt="">
                    </p>
                    <p class="footer-rights" style="color: black;">
                        {{ $lang == 'ar'
                            ? '© ٢٠٢٥ البوابة. جميع الحقوق محفوظة
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 '
                            : '© 2025 The Gate. All Rights Reserved.' }}
                    </p>
                </div>
            </div>
        </div>
    </footer>
    @if (Route::has('login'))
        <div class="h-14.5 hidden lg:block"></div>
    @endif
    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
</body>

</html>
