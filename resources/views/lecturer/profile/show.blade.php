@extends('lecturer.layout')

@section('title', 'Hồ sơ cá nhân')

@section('content')
<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <h2>👤 Hồ sơ cá nhân</h2>
            <p>Xem và cập nhật thông tin cá nhân của bạn</p>
        </div>

        <form action="{{ route('lecturer.profile.update') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-row cols-2">
                <div class="form-group">
                    <label class="form-label">Mã Giảng viên</label>
                    <input type="text" class="form-input" value="{{ $lecturer->code }}" readonly>
                    <small style="color: #94a3b8; display: block; margin-top: 4px;">Không thể thay đổi</small>
                </div>
                <div class="form-group">
                    <label class="form-label">Họ và tên</label>
                    <input type="text" class="form-input" value="{{ $lecturer->name }}" readonly>
                    <small style="color: #94a3b8; display: block; margin-top: 4px;">Không thể thay đổi</small>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Khoa</label>
                <input type="text" class="form-input" value="{{ $lecturer->faculty ? $lecturer->faculty->name : 'Chưa phân công' }}" readonly>
            </div>

            <div class="form-row cols-2">
                <div class="form-group">
                    <label class="form-label">Email <span class="required">*</span></label>
                    <input type="email" class="form-input" name="email" value="{{ old('email', $lecturer->email) }}" required>
                    @error('email')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Số điện thoại</label>
                    <input type="text" class="form-input" name="phone" value="{{ old('phone', $lecturer->phone) }}" placeholder="VD: 0901234567">
                    @error('phone')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Ảnh đại diện</label>
                <input type="file" class="form-input" name="avatar" accept="image/*">
                <small style="color: #94a3b8; display: block; margin-top: 4px;">Kích thước tối đa: 2MB. Định dạng: JPG, PNG</small>
                @error('avatar')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-hint">
                <strong>Lưu ý:</strong> Một số thông tin như mã giảng viên, họ tên không thể thay đổi.
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">💾 Lưu thay đổi</button>
                <a href="{{ route('lecturer.dashboard') }}" class="btn-cancel">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection