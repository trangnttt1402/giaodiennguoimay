@extends('student.layout')

@section('title','Thông báo hệ thống')

@section('content')
<style>
    .notifications-container {
        max-width: 900px;
        margin: 0 auto;
    }

    .notification-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
        padding-bottom: 16px;
        border-bottom: 2px solid #e2e8f0;
    }

    .notification-header h2 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .notification-icon {
        width: 48px;
        height: 48px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .notifications-list {
        display: flex;
        flex-direction: column;
        gap: 16px;
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .notification-item {
        background: white;
        border-radius: 12px;
        padding: 20px;
        border: 1px solid #e2e8f0;
        transition: all 0.3s ease;
        box-shadow: 0 2px 4px rgba(0,0,0,0.04);
    }

    .notification-item:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        border-color: #cbd5e1;
    }

    .notification-title-row {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
        margin-bottom: 12px;
    }

    .notification-title {
        font-size: 17px;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
        flex: 1;
        line-height: 1.4;
    }

    .notification-date {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #64748b;
        white-space: nowrap;
        background: #f1f5f9;
        padding: 6px 12px;
        border-radius: 6px;
    }

    .notification-content {
        color: #475569;
        line-height: 1.6;
        font-size: 15px;
    }

    .empty-state {
        text-align: center;
        padding: 80px 20px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 16px;
        border: 2px dashed #cbd5e1;
    }

    .empty-state-icon {
        font-size: 72px;
        margin-bottom: 20px;
        opacity: 0.5;
    }

    .empty-state-title {
        font-size: 20px;
        font-weight: 600;
        color: #475569;
        margin: 0 0 8px 0;
    }

    .empty-state-text {
        font-size: 15px;
        color: #94a3b8;
        margin: 0;
    }

    .pagination-wrapper {
        margin-top: 24px;
        display: flex;
        justify-content: center;
    }
</style>

<div class="notifications-container">
    <div class="notification-header">
        <div class="notification-icon">📣</div>
        <h2>Thông báo hệ thống</h2>
    </div>

    <ul class="notifications-list">
        @forelse($announcements as $a)
        <li class="notification-item">
            <div class="notification-title-row">
                <h3 class="notification-title">{{ $a->title }}</h3>
                <span class="notification-date">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10"></circle>
                        <polyline points="12 6 12 12 16 14"></polyline>
                    </svg>
                    {{ optional($a->published_at)->format('d/m/Y H:i') }}
                </span>
            </div>
            <div class="notification-content">{!! nl2br(e($a->content)) !!}</div>
        </li>
        @empty
        <li>
            <div class="empty-state">
                <div class="empty-state-icon">🔔</div>
                <h3 class="empty-state-title">Chưa có thông báo nào</h3>
                <p class="empty-state-text">Các thông báo mới sẽ xuất hiện tại đây</p>
            </div>
        </li>
        @endforelse
    </ul>

    @if($announcements->hasPages())
    <div class="pagination-wrapper">
        {{ $announcements->links() }}
    </div>
    @endif
</div>
@endsection
