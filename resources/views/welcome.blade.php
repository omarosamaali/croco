@extends('layouts.app')

@section('title', 'كروكو | CROCO')

@section('content')
    <style>
        .overlay {
            /* Add Linear Gridean Background */
            background-image: linear-gradient(to bottom, rgba(253, 205, 11, 1), rgba(253, 205, 11, 0.2));
            /* background-color: #FDCD0B; */
            top: 0;
            position: fixed;
            width: 100%;
            z-index: 999;
            height: 100%;
        }
    </style>
    <div>

        @if (isset($banners) && $banners->where('location')->first())
            @php
                $heroBanner = $banners->where('location')->first();
            @endphp
            <img src="{{ asset('storage/' . $heroBanner->image_path) }}"
                style="top: 0; position: fixed; width: 100%; height: 100%;" alt="Banner">
        @else
            {{-- Fallback to static image if no matching banner is found --}}
            {{-- <img src="{{ asset('assets/img/background.jpg') }}" style="top: 0; position: fixed;" alt=""> --}}
        @endif
        {{-- Add OverLay --}}
        <div class="overlay">
        </div>
    </div>
    <!-- Start Lines -->
    {{-- Start Lines --}}
    <div class="grid-lines" style="z-index: 9999; top: 100%; position: absolute; width: 100%; height: 100%; left: 0%;">
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
    {{-- End Lines --}}<!-- End Lines -->


    {{-- <div>
        <h1 class="page-title" style="color: black !important;">{{ $lang == 'ar' ? 'آخر الأخبار ' : '  latest news' }}</h1>
        <section class="news-section">
            <div class="news-container" style="z-index: 9999999999999999; position: relative;">
                @foreach ($news->sortByDesc('created_at')->take(3) as $index => $item)
                    <a href="ar/news">
                        <div class="news-card {{ $index == 1 ? 'news-card-alt' : '' }}">
                            @if ($index != 1 && $item->image_path)
                                <img style="width: 100%; height: 280px;" src="{{ asset('storage/' . $item->image_path) }}"
                                    alt="unkown image" class="news-image">
                            @endif
                            <div class="news-meta">
                                <span><i class="fa fa-user"></i> {{ $item->author }}</span>
                                <span><i class="fa fa-calendar"></i> {{ $item->created_at->format('d F, Y') }}</span>
                            </div>
                            <h3 class="news-title">{{ Str::limit($lang === 'ar' ? $item->title_ar : $item->title_en, 30) }}
                            </h3>

                            @if ($index == 1 && $item->image_path)
                                <img style="width: 100%; height: 280px;" src="{{ asset('storage/' . $item->image_path) }}"
                                    alt="{{ $item->title_ar }}" class="news-image-alt">
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>
        </section>
    </div> --}}
    {{-- Start Lines --}}
    <div class="grid-lines" style="z-index: 9999; top: 150%; position: absolute; width: 100%; height: 100%; left: 0%;">
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
    <div id="container-images">
        <div>
            <img src="{{ asset('assets/img/logo1.svg') }}" style="width: 124px; height: min-content;"
                class="footer-image" alt="">
            <p>
                {{ $lang == 'ar' ? 'أفلام ومسلسلات' : 'Movies and Series' }}
            </p>
        </div>
        <div>
            <img src="{{ asset('assets/img/logo2.svg') }}" style="width: 124px; height: min-content;"
                class="footer-image" alt="">
            <p style="right: 5px !important;">
                {{ $lang == 'ar' ? 'أطفال' : 'Kids' }}
            </p>
        </div>
        <div>
            <img src="{{ asset('assets/img/logo3.svg') }}" style="width: 124px; height: min-content;"
                class="footer-image" alt="">
            <p style="right: 2px !important;">
                {{ $lang == 'ar' ? 'رياضة' : 'Sports' }}
            </p>
        </div>
    </div>
    <div class="footer-image-container">
        <div id="container-images-container" class="swiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="{{ asset('assets/img/logo1.svg') }}" class="footer-image" alt="">
                    <p>{{ $lang == 'ar' ? 'أفلام ومسلسلات' : 'Movies and Series' }}</p>
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('assets/img/logo2.svg') }}" class="footer-image" alt="">
                    <p>{{ $lang == 'ar' ? 'أطفال' : 'Kids' }}</p>
                </div>
                <div class="swiper-slide">
                    <img src="{{ asset('assets/img/logo3.svg') }}" class="footer-image" alt="">
                    <p>{{ $lang == 'ar' ? 'رياضة' : 'Sports' }}</p>
                </div>
            </div>
            <div class="swiper-pagination"></div>
            <div class="swiper-button-next"></div>
            <div class="swiper-button-prev"></div>
        </div>
    </div>

    <style>
        /* خطوط الخلفية */
        .grid-lines-container {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 100%;
        }

        .line,
        .line-vertical {
            background-color: rgba(255, 255, 255, 0.1);
            width: 1px;
            height: 100%;
            display: inline-block;
        }

        /* العناوين */
        .page-title {
            font-size: 2rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
            color: white;
        }

        /* قسم الأخبار */
        .news-section {
            text-align: center;
            padding: 50px 0;
        }

        .news-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 0 20px;
        }

        .news-card {
            background-color: #2c2c38;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 20px;
        }

        .news-card-alt {
            padding-top: 30px;
        }

        .news-meta {
            color: #c4d5f6;
            font-size: 14px;
            display: flex;
            justify-content: space-between;
        }

        .news-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: white;
        }

        /* الألعاب */
        .game-cards-container {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            /* 4 أعمدة بشكل افتراضي */
            z-index: 99999;
            position: relative;
            gap: 20px;
            padding: 20px;

        }

        /* Media Queries لتقليل عدد الأعمدة بناءً على حجم الشاشة */

        /* شاشات أصغر من 1200px: 3 أعمدة */
        @media (max-width: 1200px) {
            .game-cards-container {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        /* شاشات أصغر من 900px: عمودين */
        @media (max-width: 900px) {
            .game-cards-container {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* شاشات أصغر من 600px: عمود واحد */
        @media (max-width: 600px) {
            .game-cards-container {
                grid-template-columns: 1fr;
                /* أو repeat(1, 1fr) */
            }
        }

        .game-card {
            background-color: #0d0f12;
            border: 1px solid #242829;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
        }

        .game-title {
            font-size: 1.2rem;
            font-weight: bold;
            color: white;
        }

        .game-genre {
            color: gray;
            font-size: 0.9rem;
        }

        .available-text {
            font-size: 0.9rem;
            color: white;
        }

        .platform-icons {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .platform-icon {
            position: relative;
        }

        .platform-image {
            width: 24px;
            height: 24px;
            border-radius: 50%;
        }

        .tooltip {
            position: absolute;
            top: -30px;
            left: 10px;
            background: black;
            color: white;
            padding: 5px;
            font-size: 12px;
            border-radius: 5px;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .platform-icon:hover .tooltip {
            opacity: 1;
        }

        /* زر عرض المزيد */
        .show-more-container {
            text-align: center;
            margin-top: 30px;
        }

        .show-more-btn {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>

@endsection
