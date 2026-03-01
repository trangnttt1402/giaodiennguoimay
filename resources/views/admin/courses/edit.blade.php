@extends('admin.layout')

@section('title', 'Sửa Môn học')

@section('content')
<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <h2>📚 Chỉnh sửa Môn học</h2>
            <p>Cập nhật thông tin môn: {{ $course->code }} - {{ $course->name }}</p>
        </div>

        <form action="{{ route('courses.update', $course) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-row cols-2">
                <div class="form-group">
                    <label class="form-label">Mã môn học <span class="required">*</span></label>
                    <input type="text" name="code" value="{{ old('code', $course->code) }}" required class="form-input" placeholder="VD: IT001">
                    @error('code')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Số tín chỉ <span class="required">*</span></label>
                    <input type="number" name="credits" value="{{ old('credits', $course->credits) }}" min="1" max="10" required class="form-input">
                    @error('credits')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Tên môn học <span class="required">*</span></label>
                <input type="text" name="name" value="{{ old('name', $course->name) }}" required class="form-input" placeholder="VD: Nhập môn Lập trình">
                @error('name')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-row cols-2">
                <div class="form-group">
                    <label class="form-label">Khoa <span class="required">*</span></label>
                    <select name="faculty_id" required class="form-select">
                        <option value="">-- Chọn khoa --</option>
                        @foreach($faculties as $faculty)
                        <option value="{{ $faculty->id }}" {{ old('faculty_id', $course->faculty_id) == $faculty->id ? 'selected' : '' }}>
                            {{ $faculty->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('faculty_id')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Loại học phần</label>
                    <select name="type" class="form-select">
                        <option value="">-- Không chọn --</option>
                        <option value="Bắt buộc" {{ old('type', $course->type) == 'Bắt buộc' ? 'selected' : '' }}>Bắt buộc</option>
                        <option value="Tự chọn" {{ old('type', $course->type) == 'Tự chọn' ? 'selected' : '' }}>Tự chọn</option>
                        <option value="Đại cương" {{ old('type', $course->type) == 'Đại cương' ? 'selected' : '' }}>Đại cương</option>
                        <option value="Chuyên ngành" {{ old('type', $course->type) == 'Chuyên ngành' ? 'selected' : '' }}>Chuyên ngành</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Môn tiên quyết</label>
                <select name="prerequisites[]" multiple size="5" class="form-select" style="height:120px;">
                    @foreach($allCourses as $c)
                    @if($c->id !== $course->id)
                    <option value="{{ $c->id }}" {{ in_array($c->id, old('prerequisites', $course->prerequisites->pluck('id')->toArray())) ? 'selected' : '' }}>
                        {{ $c->code }} - {{ $c->name }}
                    </option>
                    @endif
                    @endforeach
                </select>
                <span class="form-hint">Giữ Ctrl (hoặc Cmd) để chọn nhiều môn</span>
            </div>

            <div class="form-group">
                <label class="form-label">Trạng thái</label>
                <select name="is_active" class="form-select">
                    <option value="1" {{ old('is_active', $course->is_active) == '1' ? 'selected' : '' }}>✓ Hoạt động</option>
                    <option value="0" {{ old('is_active', $course->is_active) == '0' ? 'selected' : '' }}>✗ Ngưng hoạt động</option>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Mô tả</label>
                <textarea name="description" rows="4" class="form-textarea" placeholder="Nhập mô tả ngắn về môn học...">{{ old('description', $course->description) }}</textarea>
            </div>

            <div style="background:#E3F2FD;border-left:4px solid #2196F3;padding:16px;border-radius:8px;margin-bottom:24px;">
                <div style="display:flex;gap:12px;align-items:flex-start;">
                    <span style="color:#1976D2;font-size:20px;">ℹ️</span>
                    <div style="flex:1;">
                        <strong style="color:#0D47A1;display:block;margin-bottom:8px;">Lưu ý:</strong>
                        <ul style="margin:0;padding-left:20px;color:#01579B;">
                            <li>Mã môn học phải duy nhất</li>
                            <li>Số tín chỉ từ 1 đến 10</li>
                            <li>Môn tiên quyết là các môn sinh viên cần học trước môn này</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">✓ Cập nhật</button>
                <a href="{{ route('courses.index') }}" class="btn-cancel">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection
