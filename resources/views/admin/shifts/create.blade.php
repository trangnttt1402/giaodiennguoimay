@extends('admin.layout')

@section('title', 'Thêm Ca học')

@section('content')
<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <h2>📅 Thêm Ca học</h2>
            <p>Tạo mới ca học theo ngày và khung giờ</p>
        </div>

        <form action="{{ route('shifts.store') }}" method="POST">
            @csrf

            <div class="form-row cols-2">
                <div class="form-group">
                    <label class="form-label">Mã ca</label>
                    <input type="text" name="code" class="form-input" value="{{ old('code') }}" maxlength="20" placeholder="VD: CA1">
                    @error('code')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Tên ca <span class="required">*</span></label>
                    <input type="text" name="name" class="form-input" value="{{ old('name') }}" required placeholder="VD: Ca sáng thứ 2">
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Thứ <span class="required">*</span></label>
                <select name="day_of_week" class="form-select" required>
                    <option value="">-- Chọn thứ --</option>
                    <option value="1" {{ old('day_of_week') == 1 ? 'selected' : '' }}>Thứ 2</option>
                    <option value="2" {{ old('day_of_week') == 2 ? 'selected' : '' }}>Thứ 3</option>
                    <option value="3" {{ old('day_of_week') == 3 ? 'selected' : '' }}>Thứ 4</option>
                    <option value="4" {{ old('day_of_week') == 4 ? 'selected' : '' }}>Thứ 5</option>
                    <option value="5" {{ old('day_of_week') == 5 ? 'selected' : '' }}>Thứ 6</option>
                    <option value="6" {{ old('day_of_week') == 6 ? 'selected' : '' }}>Thứ 7</option>
                    <option value="7" {{ old('day_of_week') == 7 ? 'selected' : '' }}>CN</option>
                </select>
                @error('day_of_week')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-row cols-2">
                <div class="form-group">
                    <label class="form-label">Giờ bắt đầu <span class="required">*</span></label>
                    <input type="time" name="start_time" class="form-input" value="{{ old('start_time') }}" required>
                    @error('start_time')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Giờ kết thúc <span class="required">*</span></label>
                    <input type="time" name="end_time" class="form-input" value="{{ old('end_time') }}" required>
                    @error('end_time')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="active" {{ old('status','active')==='active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="inactive" {{ old('status')==='inactive' ? 'selected' : '' }}>Tạm ngưng</option>
                </select>
            </div>

            <div class="form-hint">
                <strong>Lưu ý:</strong> Hệ thống sẽ tự quy đổi tiết học và kiểm tra trùng ca cùng thứ.
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">💾 Lưu</button>
                <a href="{{ route('shifts.index') }}" class="btn-cancel">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection
