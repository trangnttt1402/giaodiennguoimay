@extends('student.layout')

@section('title','Đăng ký học phần')

@section('content')
<style>
    * {
        box-sizing: border-box;
    }

    .container-main {
        display: grid;
        grid-template-columns: 310px 1fr;
        gap: 16px;
        margin-top: 16px;
    }

    /* ===== SIDEBAR ===== */
    .sidebar {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .user-card {
        background: white;
        border-radius: 8px;
        padding: 16px;
        text-align: center;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }

    .user-avatar {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        background: #e0e0e0;
        margin: 0 auto 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 36px;
        color: #999;
    }

    .user-name {
        font-weight: 700;
        color: #333;
        font-size: 14px;
        margin-bottom: 4px;
    }

    .user-id {
        font-size: 12px;
        color: #999;
    }

    .menu-box {
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        overflow: hidden;
    }

    .menu-item {
        padding: 12px 14px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #333;
        transition: background 0.2s;
    }

    .menu-item.clickable {
        cursor: pointer;
    }

    .menu-item.clickable:hover {
        background: #f8f9ff;
    }

    .menu-item:last-child {
        border-bottom: none;
    }

    .menu-item:hover {
        background: #f8f9ff;
    }

    .menu-icon {
        font-size: 16px;
    }

    .menu-text {
        flex: 1;
    }

    .menu-count {
        background: #f0f0f0;
        color: #666;
        padding: 2px 8px;
        border-radius: 12px;
        font-size: 11px;
        font-weight: 600;
    }

    .filter-section {
        background: white;
        border-radius: 8px;
        padding: 12px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
    }

    .filter-title {
        font-weight: 600;
        font-size: 13px;
        color: #333;
        margin-bottom: 10px;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .filter-group {
        margin-bottom: 10px;
        display: flex;
        flex-direction: column;
        gap: 6px;
    }

    .filter-group:last-child {
        margin-bottom: 0;
    }

    .filter-label {
        font-size: 11px;
        color: #666;
        font-weight: 500;
    }

    .filter-input {
        padding: 8px 10px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 12px;
        font-family: inherit;
    }

    .filter-input:focus {
        outline: none;
        border-color: #6B4B9D;
        box-shadow: 0 0 0 2px rgba(107, 75, 157, 0.1);
    }

    .search-btn {
        width: 100%;
        padding: 10px;
        background: #6B4B9D;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        font-size: 13px;
        margin-top: 8px;
    }

    .search-btn:hover {
        background: #5a3d7e;
    }

    /* ===== MAIN CONTENT ===== */
    .main-content {
        display: flex;
        flex-direction: column;
        gap: 12px;
    }

    .breadcrumb {
        padding: 8px 0;
        font-size: 12px;
        color: #666;
    }

    .breadcrumb a {
        color: #6B4B9D;
        text-decoration: none;
    }

    .breadcrumb a:hover {
        text-decoration: underline;
    }

    .info-row-box {
        background: white;
        border-radius: 6px;
        padding: 12px 16px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        display: flex;
        gap: 16px;
        align-items: flex-start;
    }

    .info-row-label {
        width: 160px;
        font-weight: 600;
        color: #333;
        font-size: 13px;
        padding-top: 8px;
        flex-shrink: 0;
    }

    .info-row-content {
        flex: 1;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
    }

    .info-badge-blue {
        background: #3a5a9c;
        color: white;
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 500;
    }

    .info-badge-orange {
        background: #ff8c42;
        color: white;
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 500;
    }

    .info-text-right {
        font-size: 12px;
        color: #666;
        white-space: nowrap;
    }

    .info-text-right strong {
        color: #333;
    }

    .search-input {
        width: 100%;
        padding: 10px 14px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 13px;
        font-family: inherit;
    }

    .search-input::placeholder {
        color: #bbb;
    }

    .search-input:focus {
        outline: none;
        border-color: #6B4B9D;
        box-shadow: 0 0 0 2px rgba(107, 75, 157, 0.1);
    }

    .search-input {
        width: 100%;
        padding: 8px 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 13px;
        font-family: inherit;
    }

    .search-input::placeholder {
        color: #bbb;
    }

    .search-input:focus {
        outline: none;
        border-color: #6B4B9D;
        box-shadow: 0 0 0 2px rgba(107, 75, 157, 0.1);
    }

    .courses-list {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
        margin-top: 8px;
        width: 100%;
    }

    .course-badge-green {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 6px 12px;
        background: #4caf50;
        color: white;
        border-radius: 4px;
        font-size: 12px;
        cursor: pointer;
        transition: all 0.2s;
    }

    .course-badge-green:hover {
        background: #43a047;
    }

    .course-arrow {
        font-size: 16px;
        font-weight: bold;
    }

    .sections-title {
        font-size: 15px;
        font-weight: 700;
        color: #333;
        margin: 12px 0 12px 0;
    }

    .sections-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
        gap: 12px;
        margin-bottom: 16px;
    }

    .section-card {
        background: white;
        border-radius: 6px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0,0,0,0.08);
        transition: all 0.2s;
    }

    .section-card:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.12);
    }

    .card-header {
        background: linear-gradient(135deg, #6B4B9D 0%, #5a3d7e 100%);
        color: white;
        padding: 12px;
    }

    .card-title {
        font-weight: 700;
        font-size: 13px;
        margin-bottom: 2px;
    }

    .card-subtitle {
        font-size: 12px;
        color: rgba(255,255,255,0.8);
        line-height: 1.3;
    }

    .card-body {
        padding: 12px;
    }

    .card-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 8px;
        font-size: 12px;
    }

    .card-row:last-child {
        margin-bottom: 0;
    }

    .card-label {
        color: #666;
        font-weight: 500;
    }

    .card-value {
        font-weight: 600;
        color: #333;
        text-align: right;
    }

    .card-price {
        font-size: 14px;
        color: #f57c00;
        font-weight: 700;
    }

    .card-capacity {
        background: #f8f9ff;
        padding: 8px;
        border-radius: 4px;
        margin: 8px 0;
        font-size: 12px;
    }

    .capacity-bar {
        background: #e0e0e0;
        height: 6px;
        border-radius: 3px;
        overflow: hidden;
        margin-bottom: 4px;
    }

    .capacity-fill {
        height: 100%;
        background: #6B4B9D;
        transition: width 0.3s;
    }

    .capacity-text {
        display: flex;
        justify-content: space-between;
        color: #666;
        font-weight: 500;
    }

    .card-actions {
        display: flex;
        gap: 6px;
    }

    .card-btn {
        flex: 1;
        padding: 8px 12px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-weight: 600;
        font-size: 12px;
        transition: all 0.2s;
    }

    .btn-info {
        background: #f0f0f0;
        color: #333;
    }

    .btn-info:hover {
        background: #e0e0e0;
    }

    .btn-register {
        background: #4caf50;
        color: white;
    }

    .btn-register:hover {
        background: #43a047;
    }

    .btn-registered {
        background: #d4edda;
        color: #155724;
        cursor: default;
    }

    .btn-full {
        background: #f8d7da;
        color: #721c24;
        cursor: default;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #999;
        background: white;
        border-radius: 6px;
    }

    /* Modal */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        align-items: center;
        justify-content: center;
    }

    .modal.active {
        display: flex;
    }

    .modal-content {
        background: white;
        border-radius: 8px;
        width: 90%;
        max-width: 600px;
        max-height: 80vh;
        overflow-y: auto;
        box-shadow: 0 4px 20px rgba(0,0,0,0.2);
    }

    .modal-header {
        padding: 16px 20px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .modal-title {
        font-weight: 700;
        font-size: 16px;
        color: #333;
    }

    .modal-close {
        background: none;
        border: none;
        font-size: 24px;
        color: #999;
        cursor: pointer;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 4px;
    }

    .modal-close:hover {
        background: #f0f0f0;
        color: #333;
    }

    .modal-body {
        padding: 16px 20px;
    }

    .registered-class {
        background: #f8f9ff;
        border-radius: 6px;
        padding: 12px;
        margin-bottom: 12px;
        border: 1px solid #e0e0e0;
    }

    .registered-class:last-child {
        margin-bottom: 0;
    }

    .registered-class-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 8px;
    }

    .registered-class-code {
        font-weight: 700;
        color: #6B4B9D;
        font-size: 14px;
    }

    .registered-class-name {
        font-size: 12px;
        color: #666;
        margin-top: 2px;
    }

    .registered-class-details {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        font-size: 12px;
        color: #666;
    }

    .registered-class-detail {
        display: flex;
        gap: 4px;
    }

    .detail-label-small {
        color: #999;
    }

    .btn-cancel-class {
        background: #dc3545;
        color: white;
        border: none;
        padding: 6px 12px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 11px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-cancel-class:hover {
        background: #c82333;
    }

    @media (max-width: 1200px) {
        .container-main {
            grid-template-columns: 1fr;
        }

        .info-row-box {
            flex-direction: column;
            gap: 8px;
        }

        .info-row-label {
            width: 100%;
            padding-top: 0;
        }

        .info-row-content {
            flex-direction: column;
        }

        .info-text-right {
            white-space: normal;
        }

        .sections-grid {
            grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        }
    }

    @media (max-width: 768px) {
        .container-main {
            grid-template-columns: 1fr;
        }

        .info-row-box {
            flex-direction: column;
        }

        .info-row-label {
            width: 100%;
        }

        .sections-grid {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="container-main">
    <!-- SIDEBAR -->
    <div class="sidebar">
        <!-- User Card -->
        <div class="user-card">
            <div class="user-avatar">👤</div>
            <div class="user-name">{{ Auth::user()->name }}</div>
            <div class="user-id">Mã SV: {{ Auth::user()->student_code ?? Auth::user()->id }}</div>
        </div>

        <!-- Menu Items -->
        <div class="menu-box">
            <div class="menu-item">
                <span class="menu-icon">💳</span>
                <span class="menu-text">Số dự tài khoản hiện tại:</span>
            </div>
            <div class="menu-item">
                <span class="menu-icon">➕</span>
                <span class="menu-text">Số phát sinh thêm trong đợt</span>
            </div>
            <div class="menu-item clickable" onclick="openRegisteredModal()" style="cursor: pointer;">
                <span class="menu-icon">📋</span>
                <span class="menu-text">Tổng lớp đã đăng ký:</span>
                <span class="menu-count">{{ count($currentRegs) }}</span>
            </div>
            <div class="menu-item">
                <span class="menu-icon">💰</span>
                <span class="menu-text">Số tín chỉ đã đăng ký:</span>
                <span class="menu-count">{{ $currentRegs->sum(fn($r) => $r->classSection->course->credits ?? 0) }}</span>
            </div>
            <div class="menu-item">
                <span class="menu-icon">📚</span>
                <span class="menu-text">Số tín chỉ tối thiểu</span>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="filter-section">
            <div class="filter-title">🔍 Bộ lọc tìm kiếm</div>
            <form method="GET" action="{{ route('student.offerings') }}" style="display: flex; flex-direction: column; gap: 10px;">
                <div class="filter-group">
                    <label class="filter-label">Giảng viên</label>
                    <input type="text" name="lecturer" class="filter-input" placeholder="Tên giảng viên" value="{{ request('lecturer') }}">
                </div>
                <div class="filter-group">
                    <label class="filter-label">Thứ học</label>
                    <select name="day" class="filter-input">
                        <option value="">-- Chọn thứ --</option>
                        <option value="2" {{ request('day')=='2'?'selected':'' }}>Thứ 2</option>
                        <option value="3" {{ request('day')=='3'?'selected':'' }}>Thứ 3</option>
                        <option value="4" {{ request('day')=='4'?'selected':'' }}>Thứ 4</option>
                        <option value="5" {{ request('day')=='5'?'selected':'' }}>Thứ 5</option>
                        <option value="6" {{ request('day')=='6'?'selected':'' }}>Thứ 6</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label class="filter-label">Phương án đăng ký</label>
                    <select name="plan" class="filter-input">
                        <option value="">-- Tất cả --</option>
                        @foreach(['K17_CNTT 01' => 'K17_CNTT 01', 'K17_CNTT 02' => 'K17_CNTT 02', 'K17_CNTT 03' => 'K17_CNTT 03'] as $k => $v)
                        <option value="{{ $k }}" {{ request('plan')==$k?'selected':'' }}>{{ $v }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="search-btn">Tìm kiếm</button>
            </form>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="main-content">
        <!-- Breadcrumb -->
        {{-- <div class="breadcrumb">
            <a href="{{ route('student.dashboard') }}">Đăng ký trực tuyến</a> > <strong>Đăng ký học</strong>
        </div> --}}

        <!-- Header Info -->
        <div class="info-row-box">
            <div class="info-row-label">Chương trình đào tạo</div>
            <div class="info-row-content">
                <div class="info-badge-blue">
                    Đại học Chính quy Khoá 17_4 năm - Công nghệ thông tin
                </div>
            </div>
        </div>

        <!-- Kế hoạch info -->
        <div class="info-row-box">
            <div class="info-row-label">Kế hoạch</div>
            <div class="info-row-content" style="flex: 1; display: flex; justify-content: space-between; align-items: center;">
                @if($wave)
                <div class="info-badge-orange">
                    {{ $year }}, {{ $term }} | Đợt đăng ký: {{ $wave->name ?? 'Chưa đặt tên' }}
                </div>
                <div class="info-text-right">
                    Thời gian đăng ký học phần: <strong>{{ \Carbon\Carbon::parse($wave->starts_at)->format('Y-m-d H:i') }} - {{ \Carbon\Carbon::parse($wave->ends_at)->format('Y-m-d H:i') }}</strong>
                </div>
                @else
                <div class="info-badge-orange" style="background: #999;">
                    {{ $year }}, {{ $term }} | Chưa có đợt đăng ký
                </div>
                <div class="info-text-right" style="color: #999;">
                    Chưa mở đăng ký cho kỳ này
                </div>
                @endif
            </div>
        </div>

        <!-- Học phần -->
        <div class="info-row-box">
            <div class="info-row-label">Học phần</div>
            <div class="info-row-content" style="flex-direction: column; align-items: stretch;">
                <input type="text" class="search-input" id="courseSearch" placeholder="Tìm kiếm học phần">
                <div class="courses-list">
                    @forelse($courses ?? [] as $course)
                    <div class="course-badge-green" data-course-id="{{ $course->id }}">
                        <span class="course-arrow">›</span>
                        <span>{{ $course->code }} - {{ $course->name }}</span>
                    </div>
                    @empty
                    <span style="color: #999; font-size: 12px;">Không có học phần</span>
                    @endforelse
                </div>
            </div>
        </div>

        <!-- Sections -->
        <div class="sections-title">Lớp học phần</div>

        @if(!$openForUser || !$wave)
        <div class="empty-state" style="background: #fff3cd; color: #856404; border: 1px solid #ffeaa7;">
            <div style="font-size: 48px; margin-bottom: 12px;">⏰</div>
            <div style="font-weight: 600; margin-bottom: 8px;">Chưa đến thời gian đăng ký</div>
            <div style="font-size: 13px;">Hiện tại không trong đợt đăng ký học phần. Vui lòng quay lại khi đợt đăng ký được mở.</div>
        </div>
        @elseif($sections->count() > 0)
        <div class="sections-grid">
            @foreach($sections as $s)
            @php
            $enrolled = $s->registrations()->count();
            $full = $enrolled >= $s->max_capacity;
            $registered = in_array($s->id, $registeredSectionIds);
            $percentage = ($enrolled / $s->max_capacity) * 100;
            @endphp
            <div class="section-card">
                <div class="card-header">
                    <div class="card-title">{{ $s->course->code }}</div>
                    <div class="card-subtitle">{{ $s->course->name }}</div>
                </div>

                <div class="card-body">
                    <div class="card-row">
                        <span class="card-label">Lý thuyết</span>
                        <span class="card-value">{{ $s->section_code }}</span>
                    </div>

                    <div class="card-row">
                        <span class="card-label">Thứ</span>
                        <span class="card-value">{{ $s->shift->day_name ?? 'Thứ '.$s->shift->day_of_week }}</span>
                    </div>

                    <div class="card-row">
                        <span class="card-label">Đã đăng ký</span>
                        <span class="card-value">{{ $enrolled }}/{{ $s->max_capacity }}</span>
                    </div>

                    <div class="card-capacity">
                        <div class="capacity-bar">
                            <div class="capacity-fill" style="width: {{ min($percentage, 100) }}%"></div>
                        </div>
                        <div class="capacity-text">
                            <span>{{ $enrolled }} chỗ</span>
                            <span>{{ number_format($percentage, 0) }}%</span>
                        </div>
                    </div>

                    <div class="card-row">
                        <span class="card-label">Tín chỉ</span>
                        <span class="card-value">{{ $s->course->credits }} TC</span>
                    </div>

                    <div class="card-actions">
                        <button class="card-btn btn-info">Xem chi tiết</button>
                        @if($registered)
                        <button class="card-btn btn-registered" disabled>Đã đăng ký</button>
                        @elseif($full)
                        <button class="card-btn btn-full" disabled>Hết chỗ</button>
                        @elseif(!$openForUser)
                        <button class="card-btn btn-full" disabled>Chưa mở</button>
                        @else
                        <form action="{{ route('student.register', $s) }}" method="POST" style="flex: 1;">
                            @csrf
                            <button type="submit" class="card-btn btn-register" style="width: 100%;">Đăng ký</button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div style="margin-top: 16px;">
            {{ $sections->links() }}
        </div>
        @else
        <div class="empty-state">
            <div style="font-size: 48px; margin-bottom: 12px;">📭</div>
            <div>Không tìm thấy lớp học phần</div>
        </div>
        @endif
    </div>
</div>

<!-- Modal Lớp đã đăng ký -->
<div id="registeredModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">📚 Lớp đã đăng ký ({{ count($currentRegs) }})</div>
            <button class="modal-close" onclick="closeRegisteredModal()">&times;</button>
        </div>
        <div class="modal-body">
            @if(count($currentRegs) > 0)
                @foreach($currentRegs as $reg)
                @php($cs = $reg->classSection)
                <div class="registered-class">
                    <div class="registered-class-header">
                        <div>
                            <div class="registered-class-code">{{ $cs->course->code }} - {{ $cs->section_code }}</div>
                            <div class="registered-class-name">{{ $cs->course->name }}</div>
                        </div>
                        <form action="{{ route('student.cancel', $reg) }}" method="POST" onsubmit="return confirm('Bạn có chắc muốn hủy lớp này?')" style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-cancel-class">Hủy lớp</button>
                        </form>
                    </div>
                    <div class="registered-class-details">
                        <div class="registered-class-detail">
                            <span class="detail-label-small">Tín chỉ:</span>
                            <span>{{ $cs->course->credits }} TC</span>
                        </div>
                        <div class="registered-class-detail">
                            <span class="detail-label-small">Thứ/Tiết:</span>
                            <span>{{ $cs->shift->day_name ?? 'Thứ '.$cs->shift->day_of_week ?? 'TBD' }} / T{{ $cs->shift->start_period ?? '?' }}-{{ $cs->shift->end_period ?? '?' }}</span>
                        </div>
                        <div class="registered-class-detail">
                            <span class="detail-label-small">Phòng:</span>
                            <span>{{ $cs->room->code ?? 'TBD' }}</span>
                        </div>
                        <div class="registered-class-detail">
                            <span class="detail-label-small">GV:</span>
                            <span>{{ $cs->lecturer->name ?? 'TBD' }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
                <div style="text-align: center; padding: 40px 20px; color: #999;">
                    <div style="font-size: 48px; margin-bottom: 12px;">📭</div>
                    <div>Chưa đăng ký lớp nào</div>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
function openRegisteredModal() {
    document.getElementById('registeredModal').classList.add('active');
}

function closeRegisteredModal() {
    document.getElementById('registeredModal').classList.remove('active');
}

// Close modal when clicking outside
document.getElementById('registeredModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeRegisteredModal();
    }
});
</script>

@endsection
