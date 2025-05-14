@extends('layouts.adminLayout')

@section('title', 'قائمة رسائل التواصل')

@section('content')
<div class="container">
    <div class="table-wrapper">
        <div class="header">
            <h2>قائمة رسائل التواصل ✉️</h2>
        </div>

        @if (session('success'))
        <div class="alert-success">
            {{ session('success') }}
        </div>
        @endif

        <div class="table-container">
            <table class="messages-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>الاسم</th>
                        <th>البريد الإلكتروني</th>
                        <th>رقم الهاتف</th>
                        <th>الرسالة</th>
                        <th>تاريخ الاستلام</th>
                        <th>الحالة</th>
                        <th>التحكم</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($messages as $index => $message)
                    <tr class="{{ $message->is_read ? 'read' : 'unread' }}">
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $message->name }}</td>
                        <td>{{ $message->email }}</td>
                        <td>{{ $message->phone ?? 'N/A' }}</td>
                        <td>
                            <div class="truncate">{{ $message->message }}</div>
                        </td>
                        <td>{{ $message->created_at->format('Y-m-d H:i') }}</td>
                        <td>
                            <span class="status {{ $message->is_read ? 'read-status' : 'pending-status' }}" style="min-width: 54px;">
                                {{ $message->is_read ? 'تم الرد' : 'قيد الانتظار' }}
                            </span>
                        </td>
                        <td>
                            <div class="actions">
                                @if(!$message->is_read)
                                <form action="{{ route('contact.read', ['lang' => request()->route('lang'), 'id' => $message->id]) }}" style="background-color: green; border-radius:15px;"  method="POST">
                                    @csrf
                                    <button type="submit" class="btn-action btn-read" style="color: white; border: none; background-color: green; border-radius:15px;">
                                        تمت
                                    </button>
                                </form>
                                @endif
                                <a href="{{ route('contact.show', ['lang' => request()->route('lang'), 'id' => $message->id]) }}" style="color: white;" class="btn-action btn-show">
                                    فتح
                                </a>
        <form class="delete-form" style="background-color: red; border-radius:15px;" action="{{ route('contact.destroy', ['lang' => $lang, 'id' => $message->id]) }}" method="POST">
    @csrf
    @method('DELETE')
    <button type="submit" class="btn-action btn-delete" style="color: white; border: none; background-color: red; border-radius:15px;" >
        {{ $lang == 'ar' ? 'حذف' : 'Delete' }}
    </button>
</form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="empty-message">لا توجد رسائل تواصل حتى الآن</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
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

/* Table wrapper */
.table-wrapper {
    max-width: 96rem;
    margin: 0 auto;
    background-color: #2c2c38;
    padding: 1.5rem;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 0.5rem;
}

/* Header */
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

/* Success alert */
.alert-success {
    background-color: #38a169;
    color: white;
    padding: 1rem;
    border-radius: 0.5rem;
    margin-bottom: 1rem;
    text-align: right;
}

/* Table container */
.table-container {
    overflow-x: auto;
}

/* Table styles */
.messages-table {
    min-width: 100%;
    background-color: #21212d;
    border-radius: 0.5rem;
    overflow: hidden;
    border-collapse: collapse;
}

.messages-table th, .messages-table td {
    padding: 1rem;
    text-align: right;
    color: white;
}

.messages-table th {
    background-color: #2c2c38;
}

.messages-table tr.read {
    background-color: #21212d;
}

.messages-table tr.unread {
    background-color: #0f0e1a;
}

.messages-table tr:hover {
    background-color: #2c2c38;
}

.messages-table td .truncate {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

/* Status badge */
.status {
    display: inline-block;
    padding: 0.25rem 0.5rem;
    border-radius: 9999px;
    font-size: 0.75rem;
    color: white;
}

.read-status {
    background-color: #38a169;
}

.pending-status {
    background-color: #d69e2e;
}

/* Actions */
.actions {
    display: flex;
    gap: 0.5rem;
}

.btn-action {
    font-size: 0.875rem;
    padding: 0.25rem 0.75rem;
    border-radius: 0

</style>
@endsection
