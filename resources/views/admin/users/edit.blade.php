@extends('admin.layout')

@section('title', 'Chỉnh sửa người dùng')

@section('content')
<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <h2>👤 Chỉnh sửa người dùng</h2>
            <p>Cập nhật thông tin tài khoản: <strong>{{ $user->name }}</strong></p>
        </div>

        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')

            <div class="form-row cols-2">
                <div class="form-group">
                    <label class="form-label">Họ tên <span class="required">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="form-input">
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Email <span class="required">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}" required class="form-input">
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-row cols-2">
                <div class="form-group">
                    <label class="form-label">Mã người dùng</label>
                    <input type="text" name="code" value="{{ old('code', $user->code) }}" class="form-input">
                    @error('code')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Vai trò <span class="required">*</span></label>
                    <select name="role" required class="form-select">
                        <option value="student" {{ old('role', $user->role) === 'student' ? 'selected' : '' }}>Sinh viên</option>
                        <option value="lecturer" {{ old('role', $user->role) === 'lecturer' ? 'selected' : '' }}>Giảng viên</option>
                        <option value="faculty_admin" {{ old('role', $user->role) === 'faculty_admin' ? 'selected' : '' }}>Quản trị khoa</option>
                        <option value="super_admin" {{ old('role', $user->role) === 'super_admin' ? 'selected' : '' }}>Quản trị hệ thống</option>
                    </select>
                    @error('role')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-row cols-2">
                <div class="form-group">
                    <label class="form-label">Khoa</label>
                    <select name="faculty_id" class="form-select">
                        <option value="">-- Không thuộc khoa nào --</option>
                        @foreach($faculties as $faculty)
                        <option value="{{ $faculty->id }}" {{ old('faculty_id', $user->faculty_id) == $faculty->id ? 'selected' : '' }}>
                            {{ $faculty->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('faculty_id')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Khóa học</label>
                    <input type="text" name="class_cohort" value="{{ old('class_cohort', $user->class_cohort) }}" class="form-input" placeholder="VD: K17">
                    @error('class_cohort')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">💾 Cập nhật người dùng</button>
                <a href="{{ route('admin.users') }}" class="btn-cancel">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection
