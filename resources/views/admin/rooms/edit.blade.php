@extends('admin.layout')

@section('title', 'Chỉnh sửa phòng học')

@section('content')
<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <h2>✏️ Chỉnh sửa phòng học: {{ $room->code }}</h2>
            <p>Cập nhật thông tin phòng học</p>
        </div>

        <form method="POST" action="{{ route('rooms.update', $room) }}">
            @csrf
            @method('PUT')

            <div class="form-row cols-2">
                <div class="form-group">
                    <label class="form-label">Mã phòng <span class="required">*</span></label>
                    <input type="text" name="code" value="{{ old('code', $room->code) }}" required class="form-input">
                    @error('code')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tên phòng <span class="required">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $room->name) }}" required class="form-input">
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-row cols-2">
                <div class="form-group">
                    <label class="form-label">Tòa nhà</label>
                    <input type="text" name="building" value="{{ old('building', $room->building) }}" class="form-input">
                    @error('building')<div class="form-error">{{ $message }}</div>@enderror
                </div>

                <div class="form-group">
                    <label class="form-label">Tầng</label>
                    <input type="text" name="floor" value="{{ old('floor', $room->floor) }}" class="form-input">
                    @error('floor')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Sức chứa (số người) <span class="required">*</span></label>
                <input type="number" name="capacity" value="{{ old('capacity', $room->capacity) }}" required min="1" class="form-input">
                @error('capacity')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Trang thiết bị</label>
                <div style="display:grid; grid-template-columns:repeat(3, 1fr); gap:12px;">
                    @php
                    $equipmentOptions = ['Máy chiếu', 'Bảng thông minh', 'Điều hòa', 'Micro', 'Loa', 'Máy tính', 'Bảng viết'];
                    $currentEquipment = old('equipment', $room->equipment ?? []);
                    @endphp
                    @foreach($equipmentOptions as $eq)
                    <label style="display:flex; align-items:center; gap:8px; padding:10px; background:#F6F3FF; border-radius:8px; cursor:pointer; border:1.5px solid #e2e8f0;">
                        <input type="checkbox" name="equipment[]" value="{{ $eq }}"
                            {{ in_array($eq, $currentEquipment) ? 'checked' : '' }}
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
                    <option value="active" {{ old('status', $room->status) == 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="inactive" {{ old('status', $room->status) == 'inactive' ? 'selected' : '' }}>Tạm ngưng</option>
                </select>
                @error('status')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">💾 Cập nhật</button>
                <a href="{{ route('rooms.index') }}" class="btn-cancel">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection