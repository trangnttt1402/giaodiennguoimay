@extends('admin.layout')

@section('title', 'Thêm Môn học')

@section('content')
<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <h2>📚 Thêm Môn học mới</h2>
            <p>Tạo học phần mới trong hệ thống</p>
        </div>

        @if($errors->has('code') && str_contains($errors->first('code'), 'đã tồn tại'))
        <div style="background:#FFEBEE;border-left:4px solid #F44336;padding:16px;border-radius:8px;margin-bottom:16px;">
            <div style="display:flex;gap:12px;">
                <span style="color:#F44336;font-size:20px;">⚠️</span>
                <div style="flex:1;">
                    <strong style="color:#C62828;display:block;margin-bottom:4px;">Lỗi nghiệp vụ</strong>
                    <p style="margin:0;color:#B71C1C;">
                        <strong>Mã môn học '{{ old('code') }}' đã tồn tại trong hệ thống.</strong><br>
                        Vui lòng chọn mã môn học khác. Mỗi môn học phải có mã duy nhất.
                    </p>
                </div>
            </div>
        </div>
        @endif

        <form action="{{ route('courses.store') }}" method="POST">
            @csrf

            <div class="form-row cols-2">
                <div class="form-group">
                    <label class="form-label">Mã môn học <span class="required">*</span></label>
                    <input type="text" name="code" value="{{ old('code') }}" required class="form-input" placeholder="VD: IT001, MATH101">
                    <span class="form-hint">Mã phải duy nhất trong hệ thống</span>
                    @error('code')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Số tín chỉ <span class="required">*</span></label>
                    <input type="number" name="credits" value="{{ old('credits', 3) }}" min="1" max="10" required class="form-input" placeholder="3">
                    <span class="form-hint">Giá trị hợp lệ từ 1 đến 10</span>
                    @error('credits')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Tên môn học <span class="required">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}" required class="form-input" placeholder="VD: Nhập môn Lập trình">
                @error('name')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-row cols-2">
                <div class="form-group">
                    <label class="form-label">Khoa <span class="required">*</span></label>
                    <select name="faculty_id" required class="form-select">
                        <option value="">-- Chọn khoa --</option>
                        @foreach($faculties as $faculty)
                        <option value="{{ $faculty->id }}" {{ old('faculty_id') == $faculty->id ? 'selected' : '' }}>
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
                        <option value="Bắt buộc" {{ old('type') == 'Bắt buộc' ? 'selected' : '' }}>Bắt buộc</option>
                        <option value="Tự chọn" {{ old('type') == 'Tự chọn' ? 'selected' : '' }}>Tự chọn</option>
                        <option value="Đại cương" {{ old('type') == 'Đại cương' ? 'selected' : '' }}>Đại cương</option>
                        <option value="Chuyên ngành" {{ old('type') == 'Chuyên ngành' ? 'selected' : '' }}>Chuyên ngành</option>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Trạng thái</label>
                <select name="is_active" class="form-select">
                    <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>✓ Hoạt động</option>
                    <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>✗ Ngưng hoạt động</option>
                </select>
                <span class="form-hint">Mặc định: Hoạt động</span>
            </div>

            <div class="form-group">
                <label class="form-label">Mô tả</label>
                <textarea name="description" rows="4" class="form-textarea" placeholder="Nhập mô tả ngắn về môn học, mục tiêu, nội dung chính...">{{ old('description') }}</textarea>
                <span class="form-hint">Mô tả giúp sinh viên hiểu rõ hơn về môn học</span>
            </div>

            <div style="background:#E3F2FD;border-left:4px solid #2196F3;padding:16px;border-radius:8px;margin-bottom:24px;">
                <div style="display:flex;gap:12px;align-items:flex-start;">
                    <span style="color:#1976D2;font-size:20px;">ℹ️</span>
                    <div style="flex:1;">
                        <strong style="color:#0D47A1;display:block;margin-bottom:8px;">Lưu ý quan trọng:</strong>
                        <ul style="margin:0;padding-left:20px;color:#01579B;">
                            <li>Mã môn học phải duy nhất - Hệ thống sẽ từ chối nếu mã đã tồn tại</li>
                            <li>Số tín chỉ phải lớn hơn 0 - Giá trị hợp lệ từ 1 đến 10</li>
                            <li>Môn tiên quyết sẽ được thiết lập riêng tại trang danh sách (sau khi tạo môn học)</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">✓ Lưu môn học</button>
                <a href="{{ route('courses.index') }}" class="btn-cancel">Hủy bỏ</a>
            </div>
        </form>
    </div>
</div>
@endsection