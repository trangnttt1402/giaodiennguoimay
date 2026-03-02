@extends('admin.layout')

@section('title', 'Sửa Giảng viên')

@section('content')
<style>
    .le-breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
        font-size: 13px;
        color: #64748b;
    }

    .le-breadcrumb a {
        color: #6B4B9D;
        text-decoration: none;
        font-weight: 500;
    }

    .le-breadcrumb a:hover {
        text-decoration: underline;
    }

    .le-breadcrumb svg {
        width: 14px;
        height: 14px;
        fill: #94a3b8;
    }

    .le-section {
        background: #faf9fe;
        border: 1.5px solid #ede9f6;
        border-radius: 10px;
        padding: 20px 24px;
        margin-bottom: 24px;
    }

    .le-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 15px;
        font-weight: 600;
        color: #6B4B9D;
        margin-bottom: 16px;
        padding-bottom: 10px;
        border-bottom: 1.5px solid #ede9f6;
    }

    .le-section-title svg {
        width: 20px;
        height: 20px;
        fill: #6B4B9D;
        flex-shrink: 0;
    }

    .le-actions {
        display: flex;
        gap: 12px;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 2px solid #f0ecf7;
    }

    .le-btn-save {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #6B7BD9 0%, #6B4B9D 100%);
        color: white;
        padding: 12px 28px;
        border: none;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.25s;
        box-shadow: 0 2px 8px rgba(107, 75, 157, 0.25);
    }

    .le-btn-save:hover {
        background: linear-gradient(135deg, #5a6ac8 0%, #5a3a8c 100%);
        box-shadow: 0 4px 16px rgba(107, 75, 157, 0.35);
        transform: translateY(-1px);
    }

    .le-btn-save svg {
        width: 18px;
        height: 18px;
        fill: white;
    }

    .le-btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: white;
        color: #64748b;
        padding: 12px 24px;
        border: 1.5px solid #e2e0ed;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }

    .le-btn-back:hover {
        background: #f8f6fc;
        border-color: #c4b5d9;
        color: #6B4B9D;
    }

    .le-btn-back svg {
        width: 16px;
        height: 16px;
        fill: currentColor;
    }

    .le-info-box {
        background: #f6f3ff;
        border-left: 4px solid #6B4B9D;
        padding: 16px 20px;
        border-radius: 0 10px 10px 0;
        margin-bottom: 24px;
    }

    .le-info-box strong {
        color: #6B4B9D;
        display: block;
        margin-bottom: 6px;
        font-size: 14px;
    }

    .le-info-box ul {
        margin: 0;
        padding-left: 20px;
        color: #475569;
        font-size: 13px;
        line-height: 1.8;
    }

    .le-pw-hint {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-top: 8px;
        font-size: 13px;
        color: #94a3b8;
    }

    .le-pw-hint svg {
        width: 16px;
        height: 16px;
        fill: #94a3b8;
        flex-shrink: 0;
    }
</style>

<div class="form-container">
    <div class="le-breadcrumb">
        <a href="{{ route('lecturers.index') }}">Giảng viên</a>
        <svg viewBox="0 0 24 24">
            <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z" />
        </svg>
        <span>Chỉnh sửa: {{ $lecturer->name }}</span>
    </div>

    <div class="form-card">
        <div class="form-header">
            <h2>👨‍🏫 Chỉnh sửa Giảng viên</h2>
            <p>Cập nhật thông tin giảng viên: <strong>{{ $lecturer->code }}</strong> — {{ $lecturer->name }}</p>
        </div>

        @if(session('success'))
        <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;padding:14px 18px;border-radius:10px;margin-bottom:20px;display:flex;align-items:center;gap:10px;color:#15803d;font-size:14px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="#22c55e">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
            </svg>
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('lecturers.update', $lecturer) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Section 1: Thông tin cá nhân --}}
            <div class="le-section">
                <div class="le-section-title">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                    </svg>
                    Thông tin cá nhân
                </div>
                <div class="form-row cols-2">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Mã giảng viên <span class="required">*</span></label>
                        <input type="text" name="code" value="{{ old('code', $lecturer->code) }}" required class="form-input" placeholder="VD: GV001">
                        @error('code')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Họ và tên <span class="required">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $lecturer->name) }}" required class="form-input" placeholder="VD: Nguyễn Văn A">
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- Section 2: Liên hệ & Khoa --}}
            <div class="le-section">
                <div class="le-section-title">
                    <svg viewBox="0 0 24 24">
                        <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                    </svg>
                    Liên hệ & Khoa
                </div>
                <div class="form-row cols-2">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Email <span class="required">*</span></label>
                        <input type="email" name="email" value="{{ old('email', $lecturer->email) }}" required class="form-input" placeholder="VD: giangvien@example.com">
                        @error('email')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Khoa <span class="required">*</span></label>
                        <select name="faculty_id" required class="form-select">
                            <option value="">-- Chọn Khoa --</option>
                            @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}" {{ old('faculty_id', $lecturer->faculty_id) == $faculty->id ? 'selected' : '' }}>
                                {{ $faculty->name }}
                            </option>
                            @endforeach
                        </select>
                        @error('faculty_id')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
                <div class="form-row cols-2" style="margin-top:16px;margin-bottom:0;">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Số điện thoại</label>
                        <input type="text" name="phone" value="{{ old('phone', $lecturer->phone) }}" class="form-input" placeholder="VD: 0912345678">
                        @error('phone')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group" style="margin-bottom:0;"></div>
                </div>
            </div>

            {{-- Section 3: Học vị & Bảo mật --}}
            <div class="le-section">
                <div class="le-section-title">
                    <svg viewBox="0 0 24 24">
                        <path d="M5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82zM12 3L1 9l11 6 9-4.91V17h2V9L12 3z" />
                    </svg>
                    Học vị & Bảo mật
                </div>
                <div class="form-group">
                    <label class="form-label">Học vị</label>
                    <select name="degree" class="form-select">
                        <option value="">-- Chọn học vị --</option>
                        @foreach(['CN' => 'Cử nhân (CN)', 'ThS' => 'Thạc sĩ (ThS)', 'TS' => 'Tiến sĩ (TS)', 'PGS.TS' => 'Phó Giáo sư, Tiến sĩ (PGS.TS)', 'GS.TS' => 'Giáo sư, Tiến sĩ (GS.TS)'] as $val => $label)
                        <option value="{{ $val }}" {{ old('degree', $lecturer->degree) == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('degree')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Mật khẩu mới</label>
                    <input type="password" name="password" class="form-input" placeholder="Để trống nếu không đổi" autocomplete="new-password">
                    @error('password')<div class="form-error">{{ $message }}</div>@enderror
                    <div class="le-pw-hint">
                        <svg viewBox="0 0 24 24">
                            <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z" />
                        </svg>
                        Chỉ điền nếu muốn thay đổi mật khẩu — tối thiểu 6 ký tự
                    </div>
                </div>
            </div>

            {{-- Info box --}}
            <div class="le-info-box">
                <strong>💡 Lưu ý khi chỉnh sửa:</strong>
                <ul>
                    <li>Mã giảng viên và email phải duy nhất trong hệ thống</li>
                    <li>Để trống mật khẩu nếu không muốn thay đổi</li>
                    <li>Không thể xóa giảng viên đang phụ trách lớp học phần</li>
                </ul>
            </div>

            {{-- Actions --}}
            <div class="le-actions">
                <button type="submit" class="le-btn-save">
                    <svg viewBox="0 0 24 24">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" />
                    </svg>
                    Lưu thay đổi
                </button>
                <a href="{{ route('lecturers.index') }}" class="le-btn-back">
                    <svg viewBox="0 0 24 24">
                        <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z" />
                    </svg>
                    Quay lại
                </a>
            </div>
        </form>
    </div>
</div>
@endsection