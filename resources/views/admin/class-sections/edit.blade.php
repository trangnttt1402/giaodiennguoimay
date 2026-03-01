@extends('admin.layout')

@section('title', 'Sửa Lớp học phần')

@section('content')
<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <h2>🎓 Chỉnh sửa Lớp học phần</h2>
            <p>Cập nhật thông tin lớp: {{ $classSection->section_code }}</p>
        </div>

        <form action="{{ route('class-sections.update', $classSection) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-row cols-2">
                <div class="form-group">
                    <label class="form-label">Năm học <span class="required">*</span></label>
                    <input type="text" name="academic_year" value="{{ old('academic_year', $classSection->academic_year) }}" required class="form-input" placeholder="VD: 2024-2025">
                    @error('academic_year')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Học kỳ <span class="required">*</span></label>
                    <select name="term" required class="form-select">
                        <option value="">-- Chọn học kỳ --</option>
                        <option value="HK1" {{ old('term', $classSection->term) == 'HK1' ? 'selected' : '' }}>Học kỳ 1</option>
                        <option value="HK2" {{ old('term', $classSection->term) == 'HK2' ? 'selected' : '' }}>Học kỳ 2</option>
                        <option value="HK3" {{ old('term', $classSection->term) == 'HK3' ? 'selected' : '' }}>Học kỳ 3 (Hè)</option>
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
                        <option value="{{ $course->id }}" {{ old('course_id', $classSection->course_id) == $course->id ? 'selected' : '' }}>
                            {{ $course->code }} - {{ $course->name }} ({{ $course->credits }} TC)
                        </option>
                        @endforeach
                    </select>
                    @error('course_id')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Mã lớp <span class="required">*</span></label>
                    <input type="text" name="section_code" value="{{ old('section_code', $classSection->section_code) }}" required class="form-input" placeholder="VD: IT001.01">
                    @error('section_code')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Giảng viên <span class="required">*</span></label>
                <select name="lecturer_id" required class="form-select">
                    <option value="">-- Chọn giảng viên --</option>
                    @foreach($lecturers as $lecturer)
                    <option value="{{ $lecturer->id }}" {{ old('lecturer_id', $classSection->lecturer_id) == $lecturer->id ? 'selected' : '' }}>
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
                        <option value="2" {{ old('day_of_week', $classSection->day_of_week) == 2 ? 'selected' : '' }}>Thứ 2</option>
                        <option value="3" {{ old('day_of_week', $classSection->day_of_week) == 3 ? 'selected' : '' }}>Thứ 3</option>
                        <option value="4" {{ old('day_of_week', $classSection->day_of_week) == 4 ? 'selected' : '' }}>Thứ 4</option>
                        <option value="5" {{ old('day_of_week', $classSection->day_of_week) == 5 ? 'selected' : '' }}>Thứ 5</option>
                        <option value="6" {{ old('day_of_week', $classSection->day_of_week) == 6 ? 'selected' : '' }}>Thứ 6</option>
                        <option value="7" {{ old('day_of_week', $classSection->day_of_week) == 7 ? 'selected' : '' }}>Thứ 7</option>
                    </select>
                    @error('day_of_week')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Ca học <span class="required">*</span></label>
                    <select name="shift_id" required class="form-select">
                        <option value="">-- Chọn ca học --</option>
                        @foreach($shifts as $shift)
                        <option value="{{ $shift->id }}" {{ old('shift_id', $classSection->shift_id) == $shift->id ? 'selected' : '' }}>
                            Tiết {{ $shift->start_period }}-{{ $shift->end_period }}
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
                        <option value="{{ $room->id }}" {{ old('room_id', $classSection->room_id) == $room->id ? 'selected' : '' }}>
                            {{ $room->code }} - {{ $room->building }} ({{ $room->capacity }} chỗ)
                        </option>
                        @endforeach
                    </select>
                    @error('room_id')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-row cols-2">
                <div class="form-group">
                    <label class="form-label">Sĩ số tối đa <span class="required">*</span></label>
                    <input type="number" name="max_capacity" value="{{ old('max_capacity', $classSection->max_capacity) }}" min="1" required class="form-input">
                    @error('max_capacity')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Trạng thái</label>
                    <select name="status" class="form-select">
                        <option value="active" {{ old('status', $classSection->status) == 'active' ? 'selected' : '' }}>✓ Hoạt động</option>
                        <option value="locked" {{ old('status', $classSection->status) == 'locked' ? 'selected' : '' }}>🔒 Tạm khóa</option>
                    </select>
                    @error('status')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div style="background:#E3F2FD;border-left:4px solid #2196F3;padding:16px;border-radius:8px;margin-bottom:16px;">
                <div style="display:flex;gap:12px;align-items:center;">
                    <span style="color:#1976D2;font-size:20px;">ℹ️</span>
                    <span style="color:#0D47A1;">Số sinh viên đã đăng ký: <strong>{{ $classSection->registrations->count() }}</strong></span>
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
                <button type="submit" class="btn-submit">✓ Cập nhật</button>
                <a href="{{ route('class-sections.index') }}" class="btn-cancel">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('class-sections.index') }}" class="btn btn-secondary me-3">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <h2 class="text-white mb-0">🎓 Sửa Lớp học phần</h2>
            </div>

            <div class="card bg-dark border-secondary">
                <div class="card-body">
                    <form action="{{ route('class-sections.update', $classSection) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="academic_year" class="form-label text-white">
                                    Năm học <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    name="academic_year"
                                    id="academic_year"
                                    class="form-control bg-dark text-white border-secondary @error('academic_year') is-invalid @enderror"
                                    value="{{ old('academic_year', $classSection->academic_year) }}"
                                    placeholder="VD: 2024-2025"
                                    required>
                                @error('academic_year')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="term" class="form-label text-white">
                                    Học kỳ <span class="text-danger">*</span>
                                </label>
                                <select name="term"
                                    id="term"
                                    class="form-select bg-dark text-white border-secondary @error('term') is-invalid @enderror"
                                    required>
                                    <option value="">-- Chọn học kỳ --</option>
                                    <option value="HK1" {{ old('term', $classSection->term) == 'HK1' ? 'selected' : '' }}>Học kỳ 1</option>
                                    <option value="HK2" {{ old('term', $classSection->term) == 'HK2' ? 'selected' : '' }}>Học kỳ 2</option>
                                    <option value="HK3" {{ old('term', $classSection->term) == 'HK3' ? 'selected' : '' }}>Học kỳ 3 (Hè)</option>
                                </select>
                                @error('term')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="course_id" class="form-label text-white">
                                    Môn học <span class="text-danger">*</span>
                                </label>
                                <select name="course_id"
                                    id="course_id"
                                    class="form-select bg-dark text-white border-secondary @error('course_id') is-invalid @enderror"
                                    required>
                                    <option value="">-- Chọn môn học --</option>
                                    @foreach($courses as $course)
                                    <option value="{{ $course->id }}"
                                        {{ old('course_id', $classSection->course_id) == $course->id ? 'selected' : '' }}>
                                        {{ $course->code }} - {{ $course->name }} ({{ $course->credits }} TC)
                                    </option>
                                    @endforeach
                                </select>
                                @error('course_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="section_code" class="form-label text-white">
                                    Mã lớp <span class="text-danger">*</span>
                                </label>
                                <input type="text"
                                    name="section_code"
                                    id="section_code"
                                    class="form-control bg-dark text-white border-secondary @error('section_code') is-invalid @enderror"
                                    value="{{ old('section_code', $classSection->section_code) }}"
                                    placeholder="VD: IT001.01"
                                    required>
                                @error('section_code')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="lecturer_id" class="form-label text-white">
                                Giảng viên <span class="text-danger">*</span>
                            </label>
                            <select name="lecturer_id"
                                id="lecturer_id"
                                class="form-select bg-dark text-white border-secondary @error('lecturer_id') is-invalid @enderror"
                                required>
                                <option value="">-- Chọn giảng viên --</option>
                                @foreach($lecturers as $lecturer)
                                <option value="{{ $lecturer->id }}"
                                    {{ old('lecturer_id', $classSection->lecturer_id) == $lecturer->id ? 'selected' : '' }}>
                                    {{ $lecturer->code }} - {{ $lecturer->name }}
                                </option>
                                @endforeach
                            </select>
                            @error('lecturer_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="day_of_week" class="form-label text-white">
                                    Thứ <span class="text-danger">*</span>
                                </label>
                                <select name="day_of_week"
                                    id="day_of_week"
                                    class="form-select bg-dark text-white border-secondary @error('day_of_week') is-invalid @enderror"
                                    required>
                                    <option value="">-- Chọn thứ --</option>
                                    <option value="2" {{ old('day_of_week', $classSection->day_of_week) == 2 ? 'selected' : '' }}>Thứ 2</option>
                                    <option value="3" {{ old('day_of_week', $classSection->day_of_week) == 3 ? 'selected' : '' }}>Thứ 3</option>
                                    <option value="4" {{ old('day_of_week', $classSection->day_of_week) == 4 ? 'selected' : '' }}>Thứ 4</option>
                                    <option value="5" {{ old('day_of_week', $classSection->day_of_week) == 5 ? 'selected' : '' }}>Thứ 5</option>
                                    <option value="6" {{ old('day_of_week', $classSection->day_of_week) == 6 ? 'selected' : '' }}>Thứ 6</option>
                                    <option value="7" {{ old('day_of_week', $classSection->day_of_week) == 7 ? 'selected' : '' }}>Thứ 7</option>
                                </select>
                                @error('day_of_week')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="shift_id" class="form-label text-white">
                                    Ca học <span class="text-danger">*</span>
                                </label>
                                <select name="shift_id"
                                    id="shift_id"
                                    class="form-select bg-dark text-white border-secondary @error('shift_id') is-invalid @enderror"
                                    required>
                                    <option value="">-- Chọn ca học --</option>
                                    @foreach($shifts as $shift)
                                    <option value="{{ $shift->id }}"
                                        {{ old('shift_id', $classSection->shift_id) == $shift->id ? 'selected' : '' }}>
                                        Tiết {{ $shift->start_period }}-{{ $shift->end_period }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('shift_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-4 mb-3">
                                <label for="room_id" class="form-label text-white">
                                    Phòng học <span class="text-danger">*</span>
                                </label>
                                <select name="room_id"
                                    id="room_id"
                                    class="form-select bg-dark text-white border-secondary @error('room_id') is-invalid @enderror"
                                    required>
                                    <option value="">-- Chọn phòng --</option>
                                    @foreach($rooms as $room)
                                    <option value="{{ $room->id }}"
                                        {{ old('room_id', $classSection->room_id) == $room->id ? 'selected' : '' }}>
                                        {{ $room->code }} - {{ $room->building }} ({{ $room->capacity }} chỗ)
                                    </option>
                                    @endforeach
                                </select>
                                @error('room_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="max_capacity" class="form-label text-white">
                                Sĩ số tối đa <span class="text-danger">*</span>
                            </label>
                            <input type="number"
                                name="max_capacity"
                                id="max_capacity"
                                class="form-control bg-dark text-white border-secondary @error('max_capacity') is-invalid @enderror"
                                value="{{ old('max_capacity', $classSection->max_capacity) }}"
                                min="1"
                                required>
                            @error('max_capacity')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label text-white">
                                Trạng thái
                            </label>
                            <select name="status"
                                id="status"
                                class="form-select bg-dark text-white border-secondary @error('status') is-invalid @enderror">
                                <option value="active" {{ old('status', $classSection->status) == 'active' ? 'selected' : '' }}>✓ Hoạt động</option>
                                <option value="locked" {{ old('status', $classSection->status) == 'locked' ? 'selected' : '' }}>🔒 Tạm khóa</option>
                            </select>
                            @error('status')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Số sinh viên đã đăng ký: <strong>{{ $classSection->registrations->count() }}</strong>
                        </div>

                        <div class="alert alert-warning">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            <strong>Kiểm tra xung đột (A-4):</strong>
                            <ul class="mb-0 mt-2">
                                <li>Mã lớp không trùng trong cùng năm học/học kỳ</li>
                                <li>Giảng viên không dạy 2 lớp cùng thứ/ca</li>
                                <li>Phòng học không bị trùng cùng thứ/ca</li>
                                <li><strong>Sĩ số tối đa ≤ Sức chứa phòng</strong></li>
                            </ul>
                        </div>

                        <div class="d-flex gap-2 mt-4">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Cập nhật
                            </button>
                            <a href="{{ route('class-sections.index') }}" class="btn btn-secondary">
                                Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection