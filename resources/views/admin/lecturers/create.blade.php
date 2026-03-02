@extends('admin.layout')

@section('title', 'Thêm Giảng viên')

@section('content')
<style>
    .lc-breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
        font-size: 13px;
        color: #64748b;
    }

    .lc-breadcrumb a {
        color: #6B4B9D;
        text-decoration: none;
        font-weight: 500;
    }

    .lc-breadcrumb a:hover {
        text-decoration: underline;
    }

    .lc-breadcrumb svg {
        width: 14px;
        height: 14px;
        fill: #94a3b8;
    }

    .lc-section {
        background: #faf9fe;
        border: 1.5px solid #ede9f6;
        border-radius: 10px;
        padding: 20px 24px;
        margin-bottom: 24px;
    }

    .lc-section-title {
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

    .lc-section-title svg {
        width: 20px;
        height: 20px;
        fill: #6B4B9D;
        flex-shrink: 0;
    }

    .lc-actions {
        display: flex;
        gap: 12px;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 2px solid #f0ecf7;
    }

    .lc-btn-save {
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

    .lc-btn-save:hover {
        background: linear-gradient(135deg, #5a6ac8 0%, #5a3a8c 100%);
        box-shadow: 0 4px 16px rgba(107, 75, 157, 0.35);
        transform: translateY(-1px);
    }

    .lc-btn-save svg {
        width: 18px;
        height: 18px;
        fill: white;
    }

    .lc-btn-back {
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

    .lc-btn-back:hover {
        background: #f8f6fc;
        border-color: #c4b5d9;
        color: #6B4B9D;
    }

    .lc-btn-back svg {
        width: 16px;
        height: 16px;
        fill: currentColor;
    }

    .lc-info-box {
        background: #f6f3ff;
        border-left: 4px solid #6B4B9D;
        padding: 16px 20px;
        border-radius: 0 10px 10px 0;
        margin-bottom: 24px;
    }

    .lc-info-box strong {
        color: #6B4B9D;
        display: block;
        margin-bottom: 6px;
        font-size: 14px;
    }

    .lc-info-box ul {
        margin: 0;
        padding-left: 20px;
        color: #475569;
        font-size: 13px;
        line-height: 1.8;
    }
</style>

<div class="form-container">
    <div class="lc-breadcrumb">
        <a href="{{ route('lecturers.index') }}">Giảng viên</a>
        <svg viewBox="0 0 24 24">
            <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z" />
        </svg>
        <span>Thêm mới</span>
    </div>

    <div class="form-card">
        <div class="form-header">
            <h2>👨‍🏫 Thêm Giảng viên mới</h2>
            <p>Tạo tài khoản giảng viên mới trong hệ thống</p>
        </div>

        <form action="{{ route('lecturers.store') }}" method="POST">
            @csrf

            {{-- Section 1: Thông tin cá nhân --}}
            <div class="lc-section">
                <div class="lc-section-title">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z" />
                    </svg>
                    Thông tin cá nhân
                </div>
                <div class="form-row cols-2">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Mã giảng viên <span class="required">*</span></label>
                        <input type="text" name="code" value="{{ old('code') }}" required class="form-input" placeholder="VD: GV001">
                        @error('code')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Họ và tên <span class="required">*</span></label>
                        <input type="text" name="name" value="{{ old('name') }}" required class="form-input" placeholder="VD: Nguyễn Văn A">
                        @error('name')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- Section 2: Liên hệ & Khoa --}}
            <div class="lc-section">
                <div class="lc-section-title">
                    <svg viewBox="0 0 24 24">
                        <path d="M20 4H4c-1.1 0-1.99.9-1.99 2L2 18c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 4l-8 5-8-5V6l8 5 8-5v2z" />
                    </svg>
                    Liên hệ & Khoa
                </div>
                <div class="form-row cols-2">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Email <span class="required">*</span></label>
                        <input type="email" name="email" value="{{ old('email') }}" required class="form-input" placeholder="VD: giangvien@example.com">
                        @error('email')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Khoa <span class="required">*</span></label>
                        <select name="faculty_id" required class="form-select">
                            <option value="">-- Chọn Khoa --</option>
                            @foreach($faculties as $faculty)
                            <option value="{{ $faculty->id }}" {{ old('faculty_id') == $faculty->id ? 'selected' : '' }}>
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
                        <input type="text" name="phone" value="{{ old('phone') }}" class="form-input" placeholder="VD: 0912345678">
                        @error('phone')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group" style="margin-bottom:0;"></div>
                </div>
            </div>

            {{-- Section 3: Học vị & Bảo mật --}}
            <div class="lc-section">
                <div class="lc-section-title">
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
                        <option value="{{ $val }}" {{ old('degree') == $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    @error('degree')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Mật khẩu <span class="required">*</span></label>
                    <input type="password" name="password" required class="form-input" placeholder="Tối thiểu 6 ký tự" autocomplete="new-password">
                    @error('password')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Info box --}}
            <div class="lc-info-box">
                <strong>💡 Lưu ý:</strong>
                <ul>
                    <li>Mã giảng viên và email phải duy nhất trong toàn hệ thống</li>
                    <li>Mật khẩu tối thiểu 6 ký tự — nên kết hợp chữ hoa, chữ thường và số</li>
                    <li>Giảng viên sẽ dùng email và mật khẩu này để đăng nhập hệ thống</li>
                </ul>
            </div>

            {{-- Actions --}}
            <div class="lc-actions">
                <button type="submit" class="lc-btn-save">
                    <svg viewBox="0 0 24 24">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" />
                    </svg>
                    Lưu giảng viên
                </button>
                <a href="{{ route('lecturers.index') }}" class="lc-btn-back">
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