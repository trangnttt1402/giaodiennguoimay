@extends('admin.layout')

@section('title', 'Tạo khoa mới')

@section('content')
<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <h2>🏛️ Tạo khoa mới</h2>
            <p>Thêm khoa mới vào hệ thống</p>
        </div>

        <form method="POST" action="{{ route('faculties.store') }}">
            @csrf

            <div class="form-row cols-2">
                <div class="form-group">
                    <label class="form-label">Mã khoa <span class="required">*</span></label>
                    <input type="text" name="code" value="{{ old('code') }}" required class="form-input" placeholder="VD: CNTT">
                    @error('code')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tên khoa <span class="required">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="form-input" placeholder="VD: Công Nghệ Thông Tin">
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Trưởng khoa</label>
                <select name="dean_id" class="form-select">
                    <option value="">-- Chọn trưởng khoa --</option>
                    @foreach($lecturers as $lecturer)
                    <option value="{{ $lecturer->id }}" {{ old('dean_id') == $lecturer->id ? 'selected' : '' }}>
                        {{ $lecturer->name }} ({{ $lecturer->code ?? $lecturer->email }})
                    </option>
                    @endforeach
                </select>
                @error('dean_id')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Ngày thành lập</label>
                <input type="date" name="founding_date" value="{{ old('founding_date') }}" class="form-input">
                @error('founding_date')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Mô tả</label>
                <textarea name="description" rows="4" class="form-textarea" placeholder="Mô tả về khoa...">{{ old('description') }}</textarea>
                @error('description')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label style="display:flex;align-items:center;gap:8px;cursor:pointer;">
                    <input type="checkbox" name="is_active" value="1" {{ old('is_active', true) ? 'checked' : '' }} style="width:18px; height:18px; accent-color:#6B4B9D;">
                    <span style="color:#475569;font-size:14px;">Hoạt động</span>
                </label>
                @error('is_active')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">✓ Tạo khoa</button>
                <a href="{{ route('faculties.index') }}" class="btn-cancel">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection