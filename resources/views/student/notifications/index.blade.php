@extends('student.layout')

@section('title','Thông báo hệ thống')

@section('content')
<style>
    .notif-page {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 24px;
        max-width: 1200px;
    }

    .notif-header {
        display: flex;
        align-items: center;
        gap: 12px;
        margin-bottom: 24px;
    }

    .notif-header-icon {
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

    .notif-header h2 {
        margin: 0;
        font-size: 24px;
        font-weight: 700;
        color: #1e293b;
    }

    .notif-header p {
        margin: 2px 0 0;
        font-size: 13px;
        color: #94a3b8;
    }

    /* Announcement cards */
    .notif-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 20px 24px;
        transition: all 0.3s ease;
        box-shadow: 0 1px 3px rgba(0,0,0,0.04);
        cursor: pointer;
        text-decoration: none;
        display: block;
        color: inherit;
    }
    .notif-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,0.1);
        border-color: #6B4B9D;
    }
    .notif-card-top {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 10px;
    }
    .notif-card-title {
        font-size: 16px;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
        line-height: 1.4;
        flex: 1;
    }
    .notif-card-badge {
        font-size: 11px;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 20px;
        white-space: nowrap;
        flex-shrink: 0;
    }
    .badge-new {
        background: #fef3c7;
        color: #d97706;
    }
    .badge-old {
        background: #f1f5f9;
        color: #94a3b8;
    }
    .notif-card-excerpt {
        color: #64748b;
        font-size: 14px;
        line-height: 1.5;
        margin: 0 0 12px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .notif-card-footer {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .notif-card-date {
        font-size: 12px;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .notif-card-link {
        font-size: 13px;
        color: #6B4B9D;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Wave cards */
    .wave-card {
        background: white;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        padding: 16px;
        transition: all 0.2s;
    }
    .wave-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.06);
    }
    .wave-card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 10px;
    }
    .wave-card-name {
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
    }
    .wave-status {
        font-size: 11px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .wave-status.open { background: #dcfce7; color: #16a34a; }
    .wave-status.upcoming { background: #dbeafe; color: #2563eb; }
    .wave-status.closed { background: #f1f5f9; color: #94a3b8; }
    .wave-time {
        font-size: 12px;
        color: #64748b;
        display: flex;
        align-items: center;
        gap: 6px;
        margin-top: 6px;
    }
    .wave-audience {
        font-size: 11px;
        color: #94a3b8;
        margin-top: 6px;
    }

    .empty-state {
        text-align: center;
        padding: 60px 20px;
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 16px;
        border: 2px dashed #cbd5e1;
    }
    .empty-state-icon { font-size: 64px; margin-bottom: 16px; opacity: 0.5; }
    .empty-state-title { font-size: 18px; font-weight: 600; color: #475569; margin: 0 0 6px; }
    .empty-state-text { font-size: 14px; color: #94a3b8; margin: 0; }

    .pagination-wrapper {
        margin-top: 20px;
        display: flex;
        justify-content: center;
    }

    @media (max-width: 900px) {
        .notif-page {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="notif-page">
    <!-- Main: Announcements -->
    <div>
        <div class="notif-header">
            <div class="notif-header-icon">📣</div>
            <div>
                <h2>Thông báo hệ thống</h2>
                <p>Cập nhật thông tin đăng ký học phần và tin tức từ nhà trường</p>
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 16px;">
            @forelse($announcements as $a)
                @php
                    $isRecent = $a->published_at && $a->published_at->diffInDays(now()) <= 7;
                @endphp
                <a href="{{ route('student.notifications.show', $a->id) }}" class="notif-card">
                    <div class="notif-card-top">
                        <h3 class="notif-card-title">{{ $a->title }}</h3>
                        @if($isRecent)
                            <span class="notif-card-badge badge-new">Mới</span>
                        @endif
                    </div>
                    <p class="notif-card-excerpt">{{ Str::limit(strip_tags($a->content), 150) }}</p>
                    <div class="notif-card-footer">
                        <span class="notif-card-date">
                            🕒 {{ optional($a->published_at)->format('d/m/Y H:i') }}
                        </span>
                        <span class="notif-card-link">
                            Xem chi tiết →
                        </span>
                    </div>
                </a>
            @empty
                <div class="empty-state">
                    <div class="empty-state-icon">🔔</div>
                    <h3 class="empty-state-title">Chưa có thông báo nào</h3>
                    <p class="empty-state-text">Các thông báo mới sẽ xuất hiện tại đây</p>
                </div>
            @endforelse
        </div>

        @if($announcements->hasPages())
        <div class="pagination-wrapper">
            {{ $announcements->links() }}
        </div>
        @endif
    </div>

    <!-- Sidebar: Registration Waves -->
    <div>
        <!-- Quick Stats -->
        <div style="background: linear-gradient(135deg, #6B7BD9 0%, #6B4B9D 100%); border-radius: 12px; padding: 20px; color: white; margin-bottom: 20px; box-shadow: 0 4px 12px rgba(107, 75, 157, 0.2);">
            <div style="font-size: 13px; font-weight: 500; opacity: 0.9; margin-bottom: 4px;">📊 Lớp đã đăng ký</div>
            <div style="font-size: 32px; font-weight: 700; margin-bottom: 2px;">{{ $totalRegs }}</div>
            <div style="font-size: 12px; opacity: 0.7;">học phần trong kỳ hiện tại</div>
        </div>

        <!-- Registration Waves -->
        <div style="margin-bottom: 16px;">
            <h3 style="margin: 0 0 14px; font-size: 15px; font-weight: 600; color: #1e293b; display: flex; align-items: center; gap: 6px;">
                📅 Đợt đăng ký học phần
            </h3>
            <div style="display: flex; flex-direction: column; gap: 12px;">
                @forelse($waves as $wave)
                    <div class="wave-card">
                        <div class="wave-card-header">
                            <p class="wave-card-name">{{ $wave->name }}</p>
                            <span class="wave-status {{ $wave->status }}">{{ $wave->status_label }}</span>
                        </div>
                        <div style="font-size: 12px; color: #64748b; margin-bottom: 4px;">
                            {{ $wave->academic_year }} - {{ $wave->term === 'HK1' ? 'Học kỳ 1' : ($wave->term === 'HK2' ? 'Học kỳ 2' : 'Học kỳ Hè') }}
                        </div>
                        <div class="wave-time">
                            📅 {{ $wave->starts_at->format('d/m/Y') }} – {{ $wave->ends_at->format('d/m/Y') }}
                        </div>
                        @if($wave->status === 'open')
                            <a href="{{ route('student.offerings') }}" style="display: inline-block; margin-top: 10px; font-size: 12px; color: #6B4B9D; font-weight: 600; text-decoration: none;">
                                → Đăng ký ngay
                            </a>
                        @elseif($wave->status === 'upcoming')
                            <div style="margin-top: 8px; font-size: 11px; color: #2563eb;">
                                ⏳ Còn {{ now()->diffInDays($wave->starts_at) }} ngày nữa
                            </div>
                        @endif
                    </div>
                @empty
                    <div style="text-align: center; padding: 24px; color: #94a3b8; font-size: 13px;">
                        Chưa có đợt đăng ký nào
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Quick Links -->
        <div style="background: white; border: 1px solid #e2e8f0; border-radius: 12px; padding: 16px;">
            <h4 style="margin: 0 0 12px; font-size: 13px; font-weight: 600; color: #1e293b;">🔗 Liên kết nhanh</h4>
            <div style="display: flex; flex-direction: column; gap: 8px;">
                <a href="{{ route('student.offerings') }}" style="display: flex; align-items: center; gap: 8px; padding: 10px 12px; background: #f8fafc; border-radius: 8px; text-decoration: none; color: #475569; font-size: 13px; transition: all 0.2s;">
                    <span style="font-size: 16px;">📝</span> Đăng ký học phần
                </a>
                <a href="{{ route('student.timetable') }}" style="display: flex; align-items: center; gap: 8px; padding: 10px 12px; background: #f8fafc; border-radius: 8px; text-decoration: none; color: #475569; font-size: 13px; transition: all 0.2s;">
                    <span style="font-size: 16px;">📅</span> Xem thời khóa biểu
                </a>
                <a href="{{ route('student.my') }}" style="display: flex; align-items: center; gap: 8px; padding: 10px 12px; background: #f8fafc; border-radius: 8px; text-decoration: none; color: #475569; font-size: 13px; transition: all 0.2s;">
                    <span style="font-size: 16px;">📋</span> Lớp đã đăng ký
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
