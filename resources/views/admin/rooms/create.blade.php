@extends('admin.layout')

@section('title', 'Tạo phòng học mới')

@section('content')
<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <h2>🏫 Tạo phòng học mới</h2>
            <p>Điền đầy đủ thông tin phòng học theo yêu cầu</p>
        </div>

        <form method="POST" action="{{ route('rooms.store') }}">
            @csrf

            <div class="form-row cols-2">
                <div class="form-group">
                    <label class="form-label">Mã phòng <span class="required">*</span></label>
                    <input type="text" name="code" value="{{ old('code') }}" required class="form-input" placeholder="VD: A101">
                    @error('code')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tên phòng <span class="required">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" required class="form-input" placeholder="VD: Phòng thí nghiệm A1">
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-row cols-2">
                <div class="form-group">
                    <label class="form-label">Tòa nhà</label>
                    <input type="text" name="building" value="{{ old('building') }}" class="form-input" placeholder="VD: Nhà A">
                    @error('building')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tầng</label>
                    <input type="text" name="floor" value="{{ old('floor') }}" class="form-input" placeholder="VD: 1, 2, 3...">
                    @error('floor')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Sức chứa (số người) <span class="required">*</span></label>
                <input type="number" name="capacity" value="{{ old('capacity') }}" required min="1" class="form-input" placeholder="VD: 50">
                @error('capacity')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Trang thiết bị</label>
                <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:12px;">
                    @php
                    $equipmentOptions = ['Máy chiếu', 'Bảng thông minh', 'Điều hòa', 'Micro', 'Loa', 'Máy tính', 'Bảng viết'];
                    $oldEquipment = old('equipment', []);
                    @endphp
                    @foreach($equipmentOptions as $eq)
                    <label style="display:flex; align-items:center; gap:8px; padding:10px; background:#F6F3FF; border-radius:8px; cursor:pointer; border:1.5px solid #e2e8f0;">
                        <input type="checkbox" name="equipment[]" value="{{ $eq }}"
                            {{ in_array($eq, $oldEquipment) ? 'checked' : '' }}
                            style="width:18px; height:18px; cursor:pointer; accent-color:#6B4B9D;">
                        <span style="font-size:13px; color:#1e293b;">{{ $eq }}</span>
                    </label>
                    @endforeach
                </div>
                @error('equipment')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="active" {{ old('status', 'active') == 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Tạm ngưng</option>
                </select>
                @error('status')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">✓ Tạo phòng học</button>
                <a href="{{ route('rooms.index') }}" class="btn-cancel">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection