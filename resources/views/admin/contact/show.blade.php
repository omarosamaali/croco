@extends('layouts.adminLayout')

@section('title', 'تفاصيل الرسالة')

@section('content')
<div class="container">
    <div class="message-details">
        <div class="header">
            <h2>تفاصيل الرسالة ✉️</h2>
            <a href="{{ route('contact.dashboard', ['lang' => $lang]) }}" class="btn-back">
                العودة إلى قائمة الرسائل
            </a>
        </div>

        <div class="message-info">
            <div class="info-item">
                <h3 class="info-title">الاسم</h3>
                <p class="info-content">{{ $message->name }}</p>
            </div>

            <div class="info-item">
                <h3 class="info-title">البريد الإلكتروني</h3>
                <p class="info-content">{{ $message->email }}</p>
            </div>

            <div class="info-item">
                <h3 class="info-title">رقم الهاتف</h3>
                <p class="info-content">{{ $message->phone ?? 'غير متوفر' }}</p>
            </div>

            <div class="info-item">
                <h3 class="info-title">الرسالة</h3>
                <p class="info-content">{{ $message->message }}</p>
            </div>

            <div class="info-item">
                <h3 class="info-title">تاريخ الاستلام</h3>
                <p class="info-content">{{ $message->created_at->format('Y-m-d H:i') }}</p>
            </div>

            <div class="info-item">
                <h3 class="info-title">الحالة</h3>
                <p class="info-content">
                    <span class="status {{ $message->is_read ? 'read' : 'pending' }}">
                        {{ $message->is_read ? 'تم الرد' : 'قيد الانتظار' }}
                    </span>
                </p>
            </div>
        </div>
    </div>
</div>

<style>
    /* General container */
.container {
    padding: 1rem;
    min-height: 100vh;
    padding-top: 5rem;
}

/* Message details wrapper */
.message-details {
    max-width: 96rem;
    margin: 0 auto;
    background-color: #2c2c38;
    padding: 1.5rem;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 0.5rem;
}

/* Header styles */
.header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.header h2 {
    font-size: 1.5rem;
    font-weight: 600;
    color: white;
}

.btn-back {
    padding: 0.5rem 1rem;
    background-color: #4b5563;
    color: white;
    border-radius: 0.375rem;
    text-decoration: none;
}

.btn-back:hover {
    background-color: #374151;
}

/* Message info container */
.message-info {
    background-color: #21212d;
    padding: 1.5rem;
    border-radius: 0.5rem;
    color: white;
}

/* Info item */
.info-item {
    margin-bottom: 1rem;
}

.info-title {
    font-size: 1.125rem;
    font-weight: 600;
    text-align: right;
}

.info-content {
    text-align: right;
}

/* Status badge */
.status {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    color: white;
}

.read {
    background-color: #38a169;
}

.pending {
    background-color: #d69e2e;
}

</style>
@endsection
