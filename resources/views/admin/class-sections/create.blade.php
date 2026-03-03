@extends('admin.layout')

@section('content')
<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <h2>🎓 Thêm Lớp học phần mới</h2>
            <p>Tạo lớp học phần mới cho học kỳ</p>
        </div>

        <form action="{{ route('class-sections.store') }}" method="POST">
            @csrf

            <div class="form-row cols-2">
                <div class="form-group">
                    <label class="form-label">Năm học <span class="required">*</span></label>
                    <input type="text" name="academic_year" value="{{ old('academic_year', '2024-2025') }}" required class="form-input" placeholder="VD: 2024-2025">
                    @error('academic_year')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Học kỳ <span class="required">*</span></label>
                    <select name="term" required class="form-select">
                        <option value="">-- Chọn học kỳ --</option>
                        <option value="HK1" {{ old('term') == 'HK1' ? 'selected' : '' }}>Học kỳ 1</option>
                        <option value="HK2" {{ old('term') == 'HK2' ? 'selected' : '' }}>Học kỳ 2</option>
                        <option value="HK3" {{ old('term') == 'HK3' ? 'selected' : '' }}>Học kỳ 3 (Hè)</option>
                    </select>
                    @error('term')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-row cols-2">
                <div class="form-group">
                    <label class="form-label">Môn học <span class="required">*</span></label>
                    <select name="course_id" required class="form-select">
                        <option value="">-- Chọn môn học --</option>
                        @foreach($courses as $course)
                        <option value="{{ $course->id }}" {{ old('course_id') == $course->id ? 'selected' : '' }}>
                            {{ $course->code }} - {{ $course->name }} ({{ $course->credits }} TC)
                        </option>
                        @endforeach
                    </select>
                    @error('course_id')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Mã lớp <span class="required">*</span></label>
                    <input type="text" name="section_code" value="{{ old('section_code') }}" required class="form-input" placeholder="VD: IT001.01">
                    @error('section_code')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Giảng viên <span class="required">*</span></label>
                <select name="lecturer_id" required class="form-select">
                    <option value="">-- Chọn giảng viên --</option>
                    @foreach($lecturers as $lecturer)
                    <option value="{{ $lecturer->id }}" {{ old('lecturer_id') == $lecturer->id ? 'selected' : '' }}>
                        {{ $lecturer->code }} - {{ $lecturer->name }}
                    </option>
                    @endforeach
                </select>
                @error('lecturer_id')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-row cols-3">
                <div class="form-group">
                    <label class="form-label">Thứ <span class="required">*</span></label>
                    <select name="day_of_week" required class="form-select">
                        <option value="">-- Chọn thứ --</option>
                        <option value="2" {{ old('day_of_week') == 2 ? 'selected' : '' }}>Thứ 2</option>
                        <option value="3" {{ old('day_of_week') == 3 ? 'selected' : '' }}>Thứ 3</option>
                        <option value="4" {{ old('day_of_week') == 4 ? 'selected' : '' }}>Thứ 4</option>
                        <option value="5" {{ old('day_of_week') == 5 ? 'selected' : '' }}>Thứ 5</option>
                        <option value="6" {{ old('day_of_week') == 6 ? 'selected' : '' }}>Thứ 6</option>
                        <option value="7" {{ old('day_of_week') == 7 ? 'selected' : '' }}>Thứ 7</option>
                    </select>
                    @error('day_of_week')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Ca học <span class="required">*</span></label>
                    <select name="shift_id" required class="form-select">
                        <option value="">-- Chọn ca học --</option>
                        @foreach($shifts as $shift)
                        <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : '' }}>
                            {{ strtoupper(['', 'Th2', 'Th3', 'Th4', 'Th5', 'Th6', 'Th7', 'CN'][($shift->day_of_week ?? 1)]) }} - Tiết {{ $shift->start_period }}-{{ $shift->end_period }} ({{ $shift->start_time ? $shift->start_time.' - '.$shift->end_time : 'TBD' }})
                        </option>
                        @endforeach
                    </select>
                    @error('shift_id')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Phòng học <span class="required">*</span></label>
                    <select name="room_id" required class="form-select">
                        <option value="">-- Chọn phòng --</option>
                        @foreach($rooms as $room)
                        <option value="{{ $room->id }}" {{ old('room_id') == $room->id ? 'selected' : '' }}>
                            {{ $room->code }} ({{ $room->building }}{{ $room->floor ? ', Tầng '.$room->floor : '' }}) - {{ $room->capacity }} chỗ
                        </option>
                        @endforeach
                    </select>
                    @error('room_id')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-row cols-2">
                <div class="form-group">
                    <label class="form-label">Sĩ số tối đa <span class="required">*</span></label>
                    <input type="number" name="max_capacity" value="{{ old('max_capacity') }}" min="1" required class="form-input">
                    @error('max_capacity')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>✓ Hoạt động</option>
                        <option value="locked" {{ old('status') == 'locked' ? 'selected' : '' }}>🔒 Tạm khóa</option>
                    </select>
                    <span class="form-hint">Mặc định: Hoạt động</span>
                    @error('status')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div style="background:#FFF4E6;border-left:4px solid #FF9800;padding:16px;border-radius:8px;margin-bottom:24px;">
                <div style="display:flex;gap:12px;">
                    <span style="color:#FF9800;font-size:20px;">⚠️</span>
                    <div style="flex:1;">
                        <strong style="color:#E65100;display:block;margin-bottom:8px;">Kiểm tra xung đột:</strong>
                        <ul style="margin:0;padding-left:20px;color:#663C00;">
                            <li>Mã lớp không trùng trong cùng năm học/học kỳ</li>
                            <li>Giảng viên không dạy 2 lớp cùng thứ/ca</li>
                            <li>Phòng học không bị trùng cùng thứ/ca</li>
                            <li><strong>Sĩ số tối đa ≤ Sức chứa phòng</strong></li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">✓ Lưu</button>
                <a href="{{ route('class-sections.index') }}" class="btn-cancel">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection
