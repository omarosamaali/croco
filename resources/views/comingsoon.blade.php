@extends('layouts.app')
<!-- افترض أن لديك layout عام للصفحات العامة -->

@section('title', $lang == 'ar' ? 'قريباً' : 'Coming Soon')

@section('content')
<div class="content-container">
    <div class="container">
        <h1 class="title" style="text-align:center; color:white; margin: auto; display: flex;     justify-content: center;
">{{ $lang == 'ar' ? 'قريباً' : 'Coming Soon' }}</h1>
    </div>
</div>
@endsection

@section('styles')
<style>
    .content-container {
        padding: 1rem;
        min-height: 100vh;
        padding-top: 5rem;
    }

    .container {
        max-width: 72rem;
        margin-left: auto;
        margin-right: auto;
        padding: 1.5rem;
        background-color: #fff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        border-radius: 0.5rem;
    }

    .title {
        font-size: 2.25rem;
        font-weight: bold;
        text-align: center;
        color: white;
        margin-bottom: 1.5rem;
    }

</style>
@endsection

