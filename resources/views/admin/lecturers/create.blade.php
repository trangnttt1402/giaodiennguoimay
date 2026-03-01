@extends('admin.layout')

@section('title', 'Thêm Giảng viên')

@section('content')
<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <h2>👨‍🏫 Thêm Giảng viên mới</h2>
            <p>Thêm giảng viên mới vào hệ thống</p>
        </div>

        <form action="{{ route('lecturers.store') }}" method="POST">
            @csrf

            <div class="form-row cols-2">
                <div class="form-group">
                    <label class="form-label">Mã giảng viên <span class="required">*</span></label>
                    <input type="text" name="code" value="{{ old('code') }}" required class="form-input" placeholder="VD: GV001">
                    @error('code')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Họ và tên <span class="required">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="form-input" placeholder="VD: Nguyễn Văn A">
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-row cols-2">
                <div class="form-group">
                    <label class="form-label">Email <span class="required">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="form-input" placeholder="VD: giangvien@example.com">
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
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

            <div class="form-row cols-2">
                <div class="form-group">
                    <label class="form-label">Học vị</label>
                    <input type="text" name="degree" value="{{ old('degree') }}" class="form-input" placeholder="VD: TS., ThS., PGS.TS.">
                    @error('degree')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="form-input" placeholder="VD: 0912345678">
                    @error('phone')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Mật khẩu <span class="required">*</span></label>
                <input type="password" name="password" required class="form-input" placeholder="Tối thiểu 6 ký tự">
                @error('password')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">✓ Lưu</button>
                <a href="{{ route('lecturers.index') }}" class="btn-cancel">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection
