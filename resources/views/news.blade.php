@extends('layouts.app')

@section('title', 'كروكو | CROCO')

@section('content')
    <style>
        body {
            overflow-y: auto !important;
        }

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

    <section class="news-section">
        <h2 class="news-title">
            {{ $lang == 'ar' ? 'الأخبار' : 'News' }}
        </h2>
        <div class="news-grid">
            @foreach ($news->sortByDesc('created_at')->take(4) as $index => $item)
                <div class="news-card">
                    @if ($item->image_path)
                        <div class="news-card-image">
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title_ar }}">
                        </div>
                    @endif
                    <div class="news-card-content">
                        <h3 class="news-card-title" style="direction: rtl;">
                            {{ Str::limit($lang == 'ar' ? $item->title_ar : $item->title_en, 15) }}
                        </h3>
                        <p class="news-card-description" style="direction: rtl;">
                            {{ $lang == 'ar' ? Str::limit($item->description_ar, 15) : Str::limit($item->description_en, 100) }}...
                        </p>
                        <a href="{{ url(app()->getLocale() . '/news/' . $item->id) }}" class="read-more-btn">
                            {{ $lang == 'ar' ? 'قراءة المزيد' : 'Read More' }}
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <style>
        .news-section {
            max-width: 1200px;
            margin: 0 auto;
        }

        @media (max-width: 1240px) {
            .news-section {
                margin: 0px 20px;
            }
        }

        .news-title {
            font-size: 3rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 12px;
            color: rgb(0, 0, 0);
        }

        .news-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            row-gap: 63px;
            column-gap: 25px;
        }

        .news-card {
            border-radius: 12px;
            overflow: hidden;
            background-color: #ffffff !important;
            transition: all 0.3s ease;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        .news-card:hover {
            background-color: #e6e3e3 !important;
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }

        .news-card-image {
            width: 100%;
            height: 200px;
            overflow: hidden;
        }

        .news-card-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .news-card:hover .news-card-image img {
            transform: scale(1.05);
        }

        .news-card-content {
            padding: 9px;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
        }

        .news-card-title {
            font-size: 1.25rem;
            font-weight: 700;
            margin: 0px;
            color: rgb(0, 0, 0);
            line-height: 1.4;
        }

        .news-card-description {
            font-size: 0.95rem;
            color: #303030;
            margin-bottom: 20px;
            height: 0px;
            flex-grow: 1;
        }

        .read-more-btn {
            background-color: #a3b740;
            color: white;
            padding: 8px 16px;
            font-size: 0.875rem;
            font-weight: 500;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            transition: background-color 0.2s ease;
            text-align: center;
            margin-top: auto;
        }

        .read-more-btn:hover {
            background-color: #8ba022;
        }

        @media (max-width: 1024px) {
            .news-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }

        @media (max-width: 768px) {
            .news-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        @media (max-width: 480px) {
            .news-grid {
                grid-template-columns: 1fr;
            }

            .news-card-image {
                height: 180px;
            }
        }

        [dir="rtl"] .news-card-content {
            text-align: right;
        }

        [dir="rtl"] .read-more-btn {
            align-self: flex-start;
        }
    </style>

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
            margin-top: -8px;
            z-index: 99999;
        }

        @media (max-width: 768px) {
            .news-section {
                margin-bottom: 181px;
            }
        }

        .news-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 20px;
            padding: 0 20px;
        }

        .news-card {
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
            color: rgb(0, 0, 0);
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
