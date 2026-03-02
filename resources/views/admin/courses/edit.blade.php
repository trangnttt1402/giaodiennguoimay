@extends('admin.layout')

@section('title', 'Sửa Môn học')

@section('content')
<style>
    /* ===== Course Edit - Enhanced Styles ===== */
    .ce-breadcrumb {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 20px;
        font-size: 13px;
        color: #64748b;
    }

    .ce-breadcrumb a {
        color: #6B4B9D;
        text-decoration: none;
        font-weight: 500;
    }

    .ce-breadcrumb a:hover {
        text-decoration: underline;
    }

    .ce-breadcrumb svg {
        width: 14px;
        height: 14px;
        fill: #94a3b8;
    }

    .ce-section {
        background: #faf9fe;
        border: 1.5px solid #ede9f6;
        border-radius: 10px;
        padding: 20px 24px;
        margin-bottom: 24px;
    }

    .ce-section-title {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 15px;
        font-weight: 600;
        color: #6B4B9D;
        margin-bottom: 16px;
        padding-bottom: 10px;
        border-bottom: 1.5px solid #ede9f6;
    }

    .ce-section-title svg {
        width: 20px;
        height: 20px;
        fill: #6B4B9D;
        flex-shrink: 0;
    }

    .ce-prereq-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: 10px;
    }

    .ce-prereq-item {
        display: flex;
        align-items: center;
        gap: 10px;
        padding: 10px 14px;
        background: white;
        border: 1.5px solid #e2e0ed;
        border-radius: 8px;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 13px;
        color: #1e293b;
    }

    .ce-prereq-item:hover {
        border-color: #6B4B9D;
        background: #f6f3ff;
    }

    .ce-prereq-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #6B4B9D;
        cursor: pointer;
        flex-shrink: 0;
    }

    .ce-prereq-item.checked {
        border-color: #6B4B9D;
        background: #f3eeff;
    }

    .ce-prereq-code {
        font-weight: 600;
        color: #6B4B9D;
        min-width: 52px;
    }

    .ce-status-toggle {
        display: flex;
        gap: 12px;
    }

    .ce-status-option {
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        padding: 12px 16px;
        border: 2px solid #e2e0ed;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 14px;
        font-weight: 500;
        color: #64748b;
        background: white;
    }

    .ce-status-option:hover {
        border-color: #c4b5d9;
    }

    .ce-status-option.active-on {
        border-color: #22c55e;
        background: #f0fdf4;
        color: #15803d;
    }

    .ce-status-option.active-off {
        border-color: #ef4444;
        background: #fef2f2;
        color: #dc2626;
    }

    .ce-status-option input[type="radio"] {
        display: none;
    }

    .ce-status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: #cbd5e1;
    }

    .ce-status-option.active-on .ce-status-dot {
        background: #22c55e;
    }

    .ce-status-option.active-off .ce-status-dot {
        background: #ef4444;
    }

    .ce-info-box {
        background: #f6f3ff;
        border-left: 4px solid #6B4B9D;
        padding: 16px 20px;
        border-radius: 0 10px 10px 0;
        margin-bottom: 24px;
    }

    .ce-info-box strong {
        color: #6B4B9D;
        display: block;
        margin-bottom: 8px;
        font-size: 14px;
    }

    .ce-info-box ul {
        margin: 0;
        padding-left: 20px;
        color: #475569;
        font-size: 13px;
        line-height: 1.8;
    }

    .ce-actions {
        display: flex;
        gap: 12px;
        margin-top: 32px;
        padding-top: 24px;
        border-top: 2px solid #f0ecf7;
    }

    .ce-btn-save {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: linear-gradient(135deg, #6B7BD9 0%, #6B4B9D 100%);
        color: white;
        padding: 12px 28px;
        border: none;
        border-radius: 10px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.25s;
        box-shadow: 0 2px 8px rgba(107, 75, 157, 0.25);
    }

    .ce-btn-save:hover {
        background: linear-gradient(135deg, #5a6ac8 0%, #5a3a8c 100%);
        box-shadow: 0 4px 16px rgba(107, 75, 157, 0.35);
        transform: translateY(-1px);
    }

    .ce-btn-save svg {
        width: 18px;
        height: 18px;
        fill: white;
    }

    .ce-btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: white;
        color: #64748b;
        padding: 12px 24px;
        border: 1.5px solid #e2e0ed;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }

    .ce-btn-back:hover {
        background: #f8f6fc;
        border-color: #c4b5d9;
        color: #6B4B9D;
    }

    .ce-btn-back svg {
        width: 16px;
        height: 16px;
        fill: currentColor;
    }

    .ce-btn-delete {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: white;
        color: #ef4444;
        padding: 12px 24px;
        border: 1.5px solid #fecaca;
        border-radius: 10px;
        font-size: 14px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
        margin-left: auto;
    }

    .ce-btn-delete:hover {
        background: #fef2f2;
        border-color: #ef4444;
    }

    .ce-btn-delete svg {
        width: 16px;
        height: 16px;
        fill: currentColor;
    }
</style>

<div class="form-container">
    {{-- Breadcrumb --}}
    <div class="ce-breadcrumb">
        <a href="{{ route('courses.index') }}">Học phần</a>
        <svg viewBox="0 0 24 24">
            <path d="M8.59 16.59L13.17 12 8.59 7.41 10 6l6 6-6 6z" />
        </svg>
        <span>Chỉnh sửa: {{ $course->code }}</span>
    </div>

    <div class="form-card">
        <div class="form-header">
            <h2>📚 Chỉnh sửa Môn học</h2>
            <p>Cập nhật thông tin môn: <strong>{{ $course->code }}</strong> — {{ $course->name }}</p>
        </div>

        @if(session('success'))
        <div style="background:#f0fdf4;border:1.5px solid #bbf7d0;padding:14px 18px;border-radius:10px;margin-bottom:20px;display:flex;align-items:center;gap:10px;color:#15803d;font-size:14px;">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="#22c55e">
                <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
            </svg>
            {{ session('success') }}
        </div>
        @endif

        <form action="{{ route('courses.update', $course) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Section 1: Thông tin cơ bản --}}
            <div class="ce-section">
                <div class="ce-section-title">
                    <svg viewBox="0 0 24 24">
                        <path d="M14 2H6c-1.1 0-2 .9-2 2v16c0 1.1.9 2 2 2h12c1.1 0 2-.9 2-2V8l-6-6zm-1 2l5 5h-5V4zM6 20V4h6v6h6v10H6z" />
                    </svg>
                    Thông tin cơ bản
                </div>

                <div class="form-row cols-2">
                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Mã môn học <span class="required">*</span></label>
                        <input type="text" name="code" value="{{ old('code', $course->code) }}" required class="form-input" placeholder="VD: IT001">
                        @error('code')<div class="form-error">{{ $message }}</div>@enderror
                    </div>

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Số tín chỉ <span class="required">*</span></label>
                        <input type="number" name="credits" value="{{ old('credits', $course->credits) }}" min="1" max="10" required class="form-input">
                        @error('credits')<div class="form-error">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="form-group" style="margin-top:16px;margin-bottom:0;">
                    <label class="form-label">Tên môn học <span class="required">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $course->name) }}" required class="form-input" placeholder="VD: Nhập môn Lập trình">
                    @error('name')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            {{-- Section 2: Phân loại --}}
            <div class="ce-section">
                <div class="ce-section-title">
                    <svg viewBox="0 0 24 24">
                        <path d="M3 13h8V3H3v10zm0 8h8v-6H3v6zm10 0h8V11h-8v10zm0-18v6h8V3h-8z" />
                    </svg>
                    Phân loại
                </div>

                <div class="form-row cols-2">
                    <div class="form-group" style="margin-bottom:0;">
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

                    <div class="form-group" style="margin-bottom:0;">
                        <label class="form-label">Loại học phần</label>
                        <select name="type" class="form-select">
                            <option value="">-- Không chọn --</option>
                            <option value="Bắt buộc" {{ old('type', $course->type) == 'Bắt buộc' ? 'selected' : '' }}>📌 Bắt buộc</option>
                            <option value="Tự chọn" {{ old('type', $course->type) == 'Tự chọn' ? 'selected' : '' }}>🔄 Tự chọn</option>
                            <option value="Đại cương" {{ old('type', $course->type) == 'Đại cương' ? 'selected' : '' }}>📖 Đại cương</option>
                            <option value="Chuyên ngành" {{ old('type', $course->type) == 'Chuyên ngành' ? 'selected' : '' }}>🎯 Chuyên ngành</option>
                        </select>
                    </div>
                </div>
            </div>

            {{-- Section 3: Môn tiên quyết --}}
            <div class="ce-section">
                <div class="ce-section-title">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z" />
                    </svg>
                    Môn tiên quyết
                    <span style="font-size:12px;font-weight:400;color:#94a3b8;margin-left:4px;">(chọn các môn sinh viên cần hoàn thành trước)</span>
                </div>

                @php $selectedPrereqs = old('prerequisites', $course->prerequisites->pluck('id')->toArray()); @endphp

                @if($allCourses->where('id', '!=', $course->id)->count() > 0)
                <div class="ce-prereq-grid">
                    @foreach($allCourses as $c)
                    @if($c->id !== $course->id)
                    <label class="ce-prereq-item {{ in_array($c->id, $selectedPrereqs) ? 'checked' : '' }}">
                        <input type="checkbox" name="prerequisites[]" value="{{ $c->id }}"
                            {{ in_array($c->id, $selectedPrereqs) ? 'checked' : '' }}
                            onchange="this.closest('.ce-prereq-item').classList.toggle('checked', this.checked)">
                        <span class="ce-prereq-code">{{ $c->code }}</span>
                        <span>{{ $c->name }}</span>
                    </label>
                    @endif
                    @endforeach
                </div>
                @else
                <p style="color:#94a3b8;font-size:13px;text-align:center;padding:16px 0;">Chưa có môn học nào khác trong hệ thống</p>
                @endif
            </div>

            {{-- Section 4: Trạng thái & Mô tả --}}
            <div class="ce-section">
                <div class="ce-section-title">
                    <svg viewBox="0 0 24 24">
                        <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm-2 15l-5-5 1.41-1.41L10 14.17l7.59-7.59L19 8l-9 9z" />
                    </svg>
                    Trạng thái & Mô tả
                </div>

                <div class="form-group">
                    <label class="form-label">Trạng thái</label>
                    <div class="ce-status-toggle">
                        <label class="ce-status-option {{ old('is_active', $course->is_active) == '1' || old('is_active', $course->is_active) === true ? 'active-on' : '' }}" onclick="selectStatus(this, 'on')">
                            <input type="radio" name="is_active" value="1" {{ old('is_active', $course->is_active) == '1' || old('is_active', $course->is_active) === true ? 'checked' : '' }}>
                            <span class="ce-status-dot"></span>
                            Hoạt động
                        </label>
                        <label class="ce-status-option {{ old('is_active', $course->is_active) == '0' || old('is_active', $course->is_active) === false ? 'active-off' : '' }}" onclick="selectStatus(this, 'off')">
                            <input type="radio" name="is_active" value="0" {{ old('is_active', $course->is_active) == '0' || old('is_active', $course->is_active) === false ? 'checked' : '' }}>
                            <span class="ce-status-dot"></span>
                            Ngưng hoạt động
                        </label>
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:0;">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" rows="4" class="form-textarea" placeholder="Nhập mô tả ngắn về môn học, mục tiêu, nội dung chính...">{{ old('description', $course->description) }}</textarea>
                </div>
            </div>

            {{-- Info box --}}
            <div class="ce-info-box">
                <strong>💡 Lưu ý khi chỉnh sửa:</strong>
                <ul>
                    <li>Mã môn học phải duy nhất trong toàn hệ thống</li>
                    <li>Số tín chỉ hợp lệ từ <strong>1</strong> đến <strong>10</strong></li>
                    <li>Môn tiên quyết là các môn sinh viên cần hoàn thành trước khi đăng ký môn này</li>
                    <li>Thay đổi trạng thái sẽ ảnh hưởng đến việc đăng ký của sinh viên</li>
                </ul>
            </div>

            {{-- Actions --}}
            <div class="ce-actions">
                <button type="submit" class="ce-btn-save">
                    <svg viewBox="0 0 24 24">
                        <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" />
                    </svg>
                    Lưu thay đổi
                </button>
                <a href="{{ route('courses.index') }}" class="ce-btn-back">
                    <svg viewBox="0 0 24 24">
                        <path d="M20 11H7.83l5.59-5.59L12 4l-8 8 8 8 1.41-1.41L7.83 13H20v-2z" />
                    </svg>
                    Quay lại
                </a>
            </div>
        </form>
    </div>
</div>

<script>
    function selectStatus(el, type) {
        document.querySelectorAll('.ce-status-option').forEach(o => {
            o.classList.remove('active-on', 'active-off');
        });
        el.classList.add(type === 'on' ? 'active-on' : 'active-off');
    }
</script>
@endsection