@extends('layouts.app')

@section('title', 'الأسئلة الشائعة | CROCO')

@section('content')
<style>
    body {
        overflow-y: auto !important;
    }

    .overlay {
        background-image: linear-gradient(to bottom, rgba(253, 205, 11, 1), rgba(253, 205, 11, 0.2));
        top: 0;
        position: fixed;
        width: 100%;
        z-index: 999;
        height: 100%;
    }

    .faqs-section {
        max-width: 1200px;
        margin: 0 auto;
        /* padding: 3rem 1rem; */
        text-align: center;
        position: relative;
        z-index: 99999;
        width: 66%;
    }

    @media (max-width: 1240px) {
        .faqs-section {
            /* margin: 0px 20px; */
        }
    }

    .faqs-title {
        font-size: 1.2rem;
        font-weight: bold;
        /* margin-bottom: 2rem; */
        color: rgb(0, 0, 0);
    }

    .faqs-grid {
        display: grid;
        grid-template-columns: repeat(1fr, 1fr);
        gap: 25px;
    }

    .faq-card {
        background-color: #ffffff;
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        display: flex;
        flex-direction: column;
        cursor: pointer;
    }

    .faq-card:hover {
        background-color: #e6e3e3;
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
    }

    .faq-question {
        padding: 1rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .faq-question-text {
        font-size: 1.25rem;
        font-weight: 700;
        color: rgb(0, 0, 0);
        line-height: 1.4;
        margin: 0;
    }

    @media(max-width: 768px) {
        .faq-question-text {
            font-size: 1rem;
        }
    }
    .faq-arrow {
        width: 1.5rem;
        height: 1.5rem;
        transition: transform 0.3s ease;
    }

    .faq-arrow.rotate-180 {
        transform: rotate(180deg);
    }

    .faq-answer {
        background-color: #f5f5f5;
        color: #303030;
        padding: 1rem;
        font-size: 0.95rem;
        display: none;
        text-align: right;
    }

    .faq-answer:not(.hidden) {
        display: block;
    }

    @media (max-width: 768px) {
        .faqs-grid {
            grid-template-columns: 1fr;
        }
    }

    [dir="rtl"] .faq-question-text,
    [dir="rtl"] .faq-answer {
        text-align: right;
    }
</style>

<div>
    @if (isset($banners) && $banners->where('location')->first())
        @php
            $heroBanner = $banners->where('location')->first();
        @endphp
        <img src="{{ asset('storage/' . $heroBanner->image_path) }}"
            style="top: 0; position: fixed; width: 100%; height: 100%;" alt="Banner">
    @endif
    <div class="overlay"></div>
</div>

<section class="faqs-section">
    <h2 class="faqs-title">
        {{ $lang == 'ar' ? 'الأسئلة الشائعة' : 'Frequently Asked Questions' }}
    </h2>

    <div class="faqs-grid">
        @foreach($faqs as $faq)
        <div class="faq-card">
            <div class="faq-question" onclick="toggleFaq(this)">
                <h3 class="faq-question-text">
                    {{ $lang == 'ar' ? $faq->question_ar : $faq->question_en }}
                </h3>
                <svg xmlns="http://www.w3.org/2000/svg" class="faq-arrow" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>
            <div class="faq-answer hidden">
                <p>{!! $lang == 'ar' ? $faq->answer_ar : $faq->answer_en !!}</p>
            </div>
        </div>
        @endforeach
    </div>
</section>

<script>
function toggleFaq(element) {
    const answer = element.nextElementSibling;
    if (answer) {
        answer.classList.toggle('hidden');
    }

    const arrow = element.querySelector('svg');
    if (arrow) {
        arrow.classList.toggle('rotate-180');
    }
}
</script>
@endsection