@extends('admin.layout')

@section('title', 'Tạo người dùng mới')

@section('content')
<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <h2>👤 Tạo người dùng mới</h2>
            <p>Thêm tài khoản người dùng vào hệ thống</p>
        </div>

        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <div class="form-row cols-2">
                <div class="form-group">
                    <label class="form-label">Họ tên <span class="required">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="form-input" placeholder="VD: Nguyễn Văn A">
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email <span class="required">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" required class="form-input" placeholder="VD: user@example.com">
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-row cols-2">
                <div class="form-group">
                    <label class="form-label">Mã người dùng</label>
                    <input type="text" name="code" value="{{ old('code') }}" class="form-input" placeholder="VD: SV001, GV001">
                    @error('code')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Vai trò <span class="required">*</span></label>
                    <select name="role" required class="form-select">
                        <option value="">-- Chọn vai trò --</option>
                        <option value="student" {{ old('role') === 'student' ? 'selected' : '' }}>Sinh viên</option>
                        <option value="lecturer" {{ old('role') === 'lecturer' ? 'selected' : '' }}>Giảng viên</option>
                        <option value="faculty_admin" {{ old('role') === 'faculty_admin' ? 'selected' : '' }}>Quản trị khoa</option>
                        <option value="super_admin" {{ old('role') === 'super_admin' ? 'selected' : '' }}>Quản trị hệ thống</option>
                    </select>
                    @error('role')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-row cols-2">
                <div class="form-group">
                    <label class="form-label">Khoa</label>
                    <select name="faculty_id" class="form-select">
                        <option value="">-- Chọn khoa --</option>
                        @foreach($faculties as $faculty)
                        <option value="{{ $faculty->id }}" {{ old('faculty_id') == $faculty->id ? 'selected' : '' }}>{{ $faculty->name }}</option>
                        @endforeach
                    </select>
                    @error('faculty_id')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Khóa học (chỉ dành cho sinh viên)</label>
                    <input type="text" name="class_cohort" value="{{ old('class_cohort') }}" class="form-input" placeholder="VD: K17">
                    @error('class_cohort')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Mật khẩu <span class="required">*</span></label>
                <input type="password" name="password" required class="form-input" placeholder="Tối thiểu 8 ký tự">
                <span class="form-hint">Tối thiểu 8 ký tự, bao gồm chữ hoa, chữ thường và số</span>
                @error('password')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">✓ Tạo người dùng</button>
                <a href="{{ route('admin.users') }}" class="btn-cancel">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection
