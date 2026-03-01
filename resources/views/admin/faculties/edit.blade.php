@extends('admin.layout')

@section('title', 'Chỉnh sửa khoa')

@section('content')
<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <h2>🏛️ Chỉnh sửa khoa</h2>
            <p>Cập nhật thông tin khoa: {{ $faculty->name }}</p>
        </div>

        <form method="POST" action="{{ route('faculties.update', $faculty) }}">
            @csrf
            @method('PUT')

            <div class="form-row cols-2">
                <div class="form-group">
                    <label class="form-label">Mã khoa <span class="required">*</span></label>
                    <input type="text" name="code" value="{{ old('code', $faculty->code) }}" required class="form-input" placeholder="VD: CNTT">
                    @error('code')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tên khoa <span class="required">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $faculty->name) }}" required class="form-input" placeholder="VD: Công Nghệ Thông Tin">
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Trưởng khoa</label>
                <select name="dean_id" class="form-select">
                    <option value="">-- Chọn trưởng khoa --</option>
                    @foreach($lecturers as $lecturer)
                    <option value="{{ $lecturer->id }}" {{ old('dean_id', $faculty->dean_id) == $lecturer->id ? 'selected' : '' }}>
                        {{ $lecturer->name }} ({{ $lecturer->code ?? $lecturer->email }})
                    </option>
                    @endforeach
                </select>
                @error('dean_id')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Ngày thành lập</label>
                <input type="date" name="founding_date" value="{{ old('founding_date', $faculty->founding_date?->format('Y-m-d')) }}" class="form-input">
                @error('founding_date')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Mô tả</label>
                <textarea name="description" rows="4" class="form-textarea" placeholder="Mô tả về khoa...">{{ old('description', $faculty->description) }}</textarea>
                @error('description')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', $faculty->is_active) ? 'checked' : '' }} style="width:18px; height:18px; accent-color:#6B4B9D;">
                    <span style="color:#475569;font-size:14px;">Hoạt động</span>
                </label>
                @error('is_active')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">✓ Cập nhật</button>
                <a href="{{ route('faculties.index') }}" class="btn-cancel">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection