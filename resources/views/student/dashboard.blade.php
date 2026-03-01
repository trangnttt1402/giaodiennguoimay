@extends('student.layout')

@section('title','Tổng quan')

@section('content')
<div class="grid grid-2">
    <div class="card">
        <h3 style="margin:0 0 6px 0;font-size:20px;">Xin chào, {{ Auth::user()->name }}</h3>
        <div class="muted">Năm học {{ $year }} / {{ $term }}</div>
        <div style="display:flex;gap:12px;margin-top:12px;">
            <div class="card" style="flex:1;margin:0;padding:12px;">
                <div class="muted">Tổng tín chỉ đã đăng ký</div>
                <div style="font-size:28px;font-weight:700;">{{ $totalCredits }}</div>
            </div>
            <div class="card" style="flex:1;margin:0;padding:12px;">
                <div class="muted">Số lớp đã đăng ký</div>
                <div style="font-size:28px;font-weight:700;">{{ $currentRegs->count() }}</div>
            </div>
        </div>
        <div style="margin-top:14px;display:flex;gap:8px;flex-wrap:wrap;">
            <a class="btn" href="{{ route('student.offerings') }}">Đăng kí môn học</a>
            <a class="btn" href="{{ route('student.timetable') }}">Xem thời khóa biểu</a>
        </div>
        <div class="card" style="margin-top:16px;">
            <div class="muted" style="margin-bottom:6px;">Tuần này</div>
            <ul style="margin:0;padding:0;list-style:none;display:grid;gap:6px;">
                @forelse($currentRegs as $r)
                @php($s = $r->classSection)
                <li style="display:flex;justify-content:space-between;border-bottom:1px solid rgba(148,163,184,.12);padding:6px 0;">
                    <div>
                        <strong>{{ $s->course->code }}</strong> - {{ $s->course->name }}
                        <div class="muted">{{ $s->shift->day_name ?? ('Thứ '.$s->day_of_week) }} | Tiết {{ $s->shift->start_period }}-{{ $s->shift->end_period }}</div>
                    </div>
                    <div class="muted">Phòng {{ $s->room->code }}</div>
                </li>
                @empty
                <li class="muted">Chưa có lớp nào.</li>
                @endforelse
            </ul>
        </div>
    </div>
    <div class="card">
        <h3 style="margin:0 0 8px 0;">Đợt đăng kí tín chỉ</h3>
        <ul style="margin:0;padding:0;list-style:none;">
            @forelse($waves as $w)
            <li style="padding:8px 0;border-bottom:1px solid rgba(148,163,184,.12)">
                <strong>{{ $w->name }}</strong>
                <div class="muted">{{ $w->academic_year }} - {{ $w->term }} | {{ \Carbon\Carbon::parse($w->starts_at)->format('d/m H:i') }} → {{ \Carbon\Carbon::parse($w->ends_at)->format('d/m H:i') }}</div>
            </li>
            @empty
            <li class="muted">Chưa có đợt đăng kí.</li>
            @endforelse
        </ul>
    </div>
</div>

<div class="card">
    <h3 style="margin:0 0 8px 0;">Thông báo mới</h3>
    <ul style="margin:0;padding:0;list-style:none;">
        @forelse($announcements as $a)
        <li style="padding:8px 0;border-bottom:1px solid rgba(148,163,184,.12)">
            <strong>{{ $a->title }}</strong>
            <div class="muted">{{ optional($a->published_at)->format('d/m/Y H:i') }}</div>
            <div style="margin-top:6px;">{!! nl2br(e(Str::limit($a->content, 160))) !!}</div>
        </li>
        @empty
        <li class="muted">Chưa có thông báo.</li>
        @endforelse
    </ul>
</div>
@endsection