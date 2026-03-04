@extends('student.layout')

@section('title', $announcement->title)

@section('content')
<style>
    .detail-page {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 24px;
        max-width: 1200px;
    }

    .detail-back {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        color: #6B4B9D;
        text-decoration: none;
        font-size: 14px;
        font-weight: 500;
        margin-bottom: 20px;
        transition: all 0.2s;
    }
    .detail-back:hover {
        color: #5a3d85;
        gap: 8px;
    }

    .detail-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #e2e8f0;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }

    .detail-card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 28px 32px;
        color: white;
    }

    .detail-card-header h1 {
        margin: 0 0 12px;
        font-size: 22px;
        font-weight: 700;
        line-height: 1.4;
    }

    .detail-meta {
        display: flex;
        align-items: center;
        gap: 16px;
        font-size: 13px;
        opacity: 0.9;
    }

    .detail-meta-item {
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .detail-card-body {
        padding: 32px;
    }

    .detail-content {
        color: #334155;
        font-size: 15px;
        line-height: 1.8;
        white-space: pre-line;
    }

    /* Sidebar */
    .sidebar-section {
        background: white;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 20px;
        margin-bottom: 16px;
    }

    .sidebar-title {
        margin: 0 0 14px;
        font-size: 14px;
        font-weight: 600;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 6px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f1f5f9;
    }

    /* Wave timeline */
    .wave-timeline-item {
        display: flex;
        gap: 12px;
        padding: 12px 0;
        border-bottom: 1px solid #f8fafc;
    }
    .wave-timeline-item:last-child {
        border-bottom: none;
        padding-bottom: 0;
    }
    .wave-timeline-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-top: 5px;
        flex-shrink: 0;
    }
    .wave-timeline-dot.open { background: #16a34a; box-shadow: 0 0 0 3px rgba(22,163,74,0.2); }
    .wave-timeline-dot.upcoming { background: #2563eb; box-shadow: 0 0 0 3px rgba(37,99,235,0.2); }
    .wave-timeline-dot.closed { background: #cbd5e1; }

    .wave-timeline-name {
        font-size: 13px;
        font-weight: 600;
        color: #1e293b;
        margin: 0 0 2px;
    }
    .wave-timeline-date {
        font-size: 12px;
        color: #64748b;
        margin: 0;
    }
    .wave-timeline-status {
        font-size: 10px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-top: 4px;
    }
    .wave-timeline-status.open { color: #16a34a; }
    .wave-timeline-status.upcoming { color: #2563eb; }
    .wave-timeline-status.closed { color: #94a3b8; }

    /* Registered classes */
    .reg-class-item {
        display: flex;
        gap: 10px;
        padding: 10px 0;
        border-bottom: 1px solid #f8fafc;
        align-items: flex-start;
    }
    .reg-class-item:last-child { border-bottom: none; }
    .reg-class-color {
        width: 4px;
        min-height: 36px;
        border-radius: 2px;
        flex-shrink: 0;
        margin-top: 2px;
    }
    .reg-class-info {
        flex: 1;
        min-width: 0;
    }
    .reg-class-code {
        font-size: 13px;
        font-weight: 600;
        color: #1e293b;
        margin: 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .reg-class-name {
        font-size: 11px;
        color: #64748b;
        margin: 2px 0 0;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .reg-class-detail {
        display: flex;
        gap: 8px;
        margin-top: 4px;
        flex-wrap: wrap;
    }
    .reg-class-detail span {
        font-size: 10px;
        color: #94a3b8;
        display: flex;
        align-items: center;
        gap: 3px;
    }

    @media (max-width: 900px) {
        .detail-page {
            grid-template-columns: 1fr;
        }
    }
</style>

<a href="{{ route('student.notifications') }}" class="detail-back">
    ← Quay lại danh sách thông báo
</a>

<div class="detail-page">
    <!-- Main Content -->
    <div>
        <div class="detail-card">
            <div class="detail-card-header">
                <h1>{{ $announcement->title }}</h1>
                <div class="detail-meta">
                    <div class="detail-meta-item">
                        🕒 {{ optional($announcement->published_at)->format('d/m/Y H:i') }}
                    </div>
                    @php
                        $daysSince = $announcement->published_at ? $announcement->published_at->diffInDays(now()) : null;
                    @endphp
                    @if($daysSince !== null && $daysSince <= 7)
                        <div class="detail-meta-item" style="background: rgba(255,255,255,0.2); padding: 2px 10px; border-radius: 10px; font-weight: 600;">
                            🆕 Mới
                        </div>
                    @endif
                </div>
            </div>
            <div class="detail-card-body">
                <div class="detail-content">{{ $announcement->content }}</div>
            </div>
        </div>

        <!-- Navigation -->
        <div style="display: flex; justify-content: space-between; margin-top: 20px;">
            <a href="{{ route('student.notifications') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; background: #f1f5f9; border-radius: 8px; text-decoration: none; color: #475569; font-size: 13px; font-weight: 500; transition: all 0.2s;">
                ← Tất cả thông báo
            </a>
            <a href="{{ route('student.offerings') }}" style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; background: linear-gradient(135deg, #6B7BD9, #6B4B9D); border-radius: 8px; text-decoration: none; color: white; font-size: 13px; font-weight: 600; transition: all 0.2s; box-shadow: 0 2px 8px rgba(107,75,157,0.2);">
                📝 Đăng ký học phần →
            </a>
        </div>
    </div>

    <!-- Sidebar -->
    <div>
        <!-- Registration Waves -->
        <div class="sidebar-section">
            <h3 class="sidebar-title">📅 Đợt đăng ký học phần</h3>
            @forelse($waves as $wave)
                <div class="wave-timeline-item">
                    <div class="wave-timeline-dot {{ $wave->status }}"></div>
                    <div>
                        <p class="wave-timeline-name">{{ $wave->name }}</p>
                        <p class="wave-timeline-date">
                            {{ $wave->starts_at->format('d/m/Y') }} – {{ $wave->ends_at->format('d/m/Y') }}
                        </p>
                        <p class="wave-timeline-date" style="font-size: 11px; color: #94a3b8;">
                            {{ $wave->academic_year }} - {{ $wave->term === 'HK1' ? 'HK1' : ($wave->term === 'HK2' ? 'HK2' : 'Hè') }}
                        </p>
                        <div class="wave-timeline-status {{ $wave->status }}">
                            @if($wave->status === 'open')
                                ● Đang mở đăng ký
                            @elseif($wave->status === 'upcoming')
                                ○ Sắp mở (còn {{ now()->diffInDays($wave->starts_at) }} ngày)
                            @else
                                ○ Đã kết thúc
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <p style="text-align: center; color: #94a3b8; font-size: 13px; padding: 12px 0;">Chưa có đợt nào</p>
            @endforelse
        </div>

        <!-- Registered Classes -->
        @if($regs->count() > 0)
        <div class="sidebar-section">
            <h3 class="sidebar-title">📚 Lớp đã đăng ký ({{ $regs->count() }})</h3>
            @php
                $colors = ['#3B82F6', '#EF4444', '#10B981', '#F59E0B', '#8B5CF6', '#EC4899', '#06B6D4', '#F97316'];
                $colorMap = [];
                $cIdx = 0;
            @endphp
            @foreach($regs as $reg)
                @php
                    $cs = $reg->classSection;
                    $course = $cs->course;
                    $shift = $cs->shift;
                    if (!isset($colorMap[$cs->id])) {
                        $colorMap[$cs->id] = $colors[$cIdx % count($colors)];
                        $cIdx++;
                    }
                    $color = $colorMap[$cs->id];
                    $dayMap = [2=>'Thứ 2', 3=>'Thứ 3', 4=>'Thứ 4', 5=>'Thứ 5', 6=>'Thứ 6', 7=>'Thứ 7', 1=>'CN'];
                    $dayName = $dayMap[$cs->day_of_week] ?? '?';
                @endphp
                <div class="reg-class-item">
                    <div class="reg-class-color" style="background: {{ $color }};"></div>
                    <div class="reg-class-info">
                        <p class="reg-class-code">{{ $course->code }} – {{ $cs->section_code }}</p>
                        <p class="reg-class-name">{{ $course->name }}</p>
                        <div class="reg-class-detail">
                            <span>📅 {{ $dayName }}</span>
                            <span>⏰ T{{ $shift->start_period ?? '?' }}-{{ $shift->end_period ?? '?' }}</span>
                            <span>🏫 {{ $cs->room->code ?? 'TBD' }}</span>
                        </div>
                    </div>
                </div>
            @endforeach

            <a href="{{ route('student.timetable') }}" style="display: block; text-align: center; margin-top: 12px; padding: 8px; background: #f8fafc; border-radius: 6px; color: #6B4B9D; font-size: 12px; font-weight: 600; text-decoration: none; transition: all 0.2s;">
                📅 Xem thời khóa biểu →
            </a>
        </div>
        @else
        <div class="sidebar-section">
            <h3 class="sidebar-title">📚 Lớp đã đăng ký</h3>
            <div style="text-align: center; padding: 20px 0;">
                <div style="font-size: 36px; margin-bottom: 8px; opacity: 0.4;">📭</div>
                <p style="margin: 0; color: #94a3b8; font-size: 13px;">Chưa đăng ký lớp nào</p>
                <a href="{{ route('student.offerings') }}" style="display: inline-block; margin-top: 10px; padding: 6px 16px; background: #6B4B9D; color: white; border-radius: 6px; font-size: 12px; font-weight: 600; text-decoration: none;">
                    Đăng ký ngay
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
