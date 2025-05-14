@extends('layouts.app')

@section('title', $lang == 'ar' ? $newsItem->title_ar : $newsItem->title_en)

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

    <style>
        body {
            overflow-y: auto !important;
        }

        .overlay {
            background-image: linear-gradient(to bottom, rgba(253, 205, 11, 1), rgba(253, 205, 11, 0.2));
            top: 0;
            position: fixed;
            width: 100%;
            height: 100%;
            z-index: 999;
        }

        .single-news-section {
            max-width: 1200px;
            margin: 50px auto;
                background: white;
    border-radius: 15px;
            padding: 20px;
            position: relative;
            z-index: 9999999999999999;
            display: flex; /* Use flexbox to align image and content side by side */
            gap: 20px; /* Space between image and text */
            direction: {{ $lang == 'ar' ? 'rtl' : 'ltr' }}; /* Support RTL for Arabic */
        }

        @media (max-width: 1240px) {
            .single-news-section {
                margin: 50px 20px;
            }
        }

        .single-news-image {
            width: 40%; /* Image takes 40% of the container width */
            max-height: 300px;
            object-fit: cover;
            border-radius: 12px;
        }

        .single-news-content {
            width: 60%; /* Text content takes 60% of the container width */
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .single-news-title {
            font-size: 2rem;
            font-weight: bold;
            color: #000000;
            margin-bottom: 20px;
            line-height: 1.4;
            text-align: {{ $lang == 'ar' ? 'right' : 'left' }};
        }

        .single-news-meta {
            display: flex;
            justify-content: space-between;
            color: #000000;
            font-size: 0.9rem;
            margin-bottom: 20px;
            direction: {{ $lang == 'ar' ? 'rtl' : 'ltr' }};
        }

        .single-news-description {
            font-size: 1.1rem;
            color: #000000;
            line-height: 1.8;
            margin-bottom: 30px;
            text-align: {{ $lang == 'ar' ? 'right' : 'left' }};
        }

        .back-btn {
            display: inline-block;
            background-color: #a3b740;
            color: rgb(0, 0, 0);
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 500;
            transition: background-color 0.2s ease;
            text-align: center;
            align-self: {{ $lang == 'ar' ? 'flex-end' : 'flex-start' }};
        }

        .back-btn:hover {
            background-color: #8ba022;
        }

        /* Responsive design: stack image and text on smaller screens */
        @media (max-width: 768px) {
            .single-news-section {
                flex-direction: column; /* Stack vertically on small screens */
            }

            .single-news-image {
                width: 100%; /* Full width for image */
                max-height: 300px;
            }

            .single-news-content {
                width: 100%; /* Full width for content */
            }

            .single-news-title {
                font-size: 1.5rem;
            }

            .single-news-description {
                font-size: 1rem;
            }
        }
    </style>

    <div>
        <div class="overlay"></div>
    </div>

    <section class="single-news-section">
        @if ($newsItem->image_path)
            <img src="{{ asset('storage/' . $newsItem->image_path) }}" class="single-news-image"
                alt="{{ $lang == 'ar' ? $newsItem->title_ar : $newsItem->title_en }}">
        @endif
        <div class="single-news-content">
            <h1 class="single-news-title">
                {{ $lang == 'ar' ? $newsItem->title_ar : $newsItem->title_en }}
            </h1>

            <div class="single-news-meta">
                <span>{{ __('Published on') }}: {{ $newsItem->created_at->format('d M Y') }}</span>
                @if ($newsItem->author)
                    <span>{{ __('By') }}: {{ $newsItem->author }}</span>
                @endif
            </div>

            <div class="single-news-description">
                {!! $lang == 'ar' ? $newsItem->description_ar : $newsItem->description_en !!}
            </div>

            <a href="{{ url(app()->getLocale() . '/news') }}" class="back-btn">
                {{ $lang == 'ar' ? 'العودة إلى الأخبار' : 'Back to News' }}
            </a>
        </div>
    </section>
@endsection