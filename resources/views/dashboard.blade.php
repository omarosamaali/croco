@extends('layouts.adminLayout')

@section('title', 'مرحبًا بك يا أدمن')

@section('content')
<div class="container">
    <div class="welcome-box">
        <h2 class="welcome-title">🎬 مرحبًا بك يا أسطورة كروكو! 🎬</h2>
        <p class="welcome-text">
            حان الوقت لنضيف المزيد من الإثارة والتشويق! 🚀<br>
            يلا ننشئ أفلام ومسلسلات جديدة، نضيف تفاصيل مبهرة، ونخلي المشاهدين يعيشون تجربة سينمائية لا تُنسى! 🎥
        </p>
        <p class="welcome-subtext">
            كل فيلم أو مسلسل هيكون قصة جديدة، فخليك جاهز لإضافة كل الإبداع والشغف اللي عندك! ✨
        </p>
        <a href="{{ route('games.create', ['lang' => $lang]) }}" class="start-button">
            ابدأ الآن وأضف فيلم أو مسلسل جديد! 📽️
        </a>
    </div>
</div>

<style>
    .container {
        padding: 1rem;
        padding-top: 5rem;
    }

    .welcome-box {
        max-width: 48rem;
        margin-left: auto;
        margin-right: auto;
        background-color: #2c2c38;
        padding: 1.5rem;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        border-radius: 0.5rem;
        text-align: center;
    }

    .welcome-title {
        font-size: 1.875rem;
        font-weight: 700;
        color: #c9c8dc;
        margin-bottom: 1.5rem;
    }

    .welcome-text {
        font-size: 1.125rem;
        color: #c9c8dc;
        margin-bottom: 1rem;
    }

    .welcome-subtext {
        font-size: 1rem;
        color: #c9c8dc;
        margin-bottom: 1.5rem;
    }

    .start-button {
        display: inline-block;
        padding-left: 1.5rem;
        padding-right: 1.5rem;
        padding-top: 0.75rem;
        padding-bottom: 0.75rem;
        background-color: #16a34a;
        color: #ffffff;
        border-radius: 0.5rem;
        transition: all 0.15s ease-in-out;
    }

    .start-button:hover {
        background-color: #15803d;
    }
</style>
@endsection