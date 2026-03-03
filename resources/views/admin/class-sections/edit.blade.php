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