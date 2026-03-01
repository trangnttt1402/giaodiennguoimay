@extends('admin.layout')

@section('title', 'Quản lý Lớp học phần')

@section('content')
<style>
    .table-zebra tbody tr:nth-child(even) {
        background-color: rgba(255, 255, 255, 0.02);
    }

    .table-zebra tbody tr:hover {
        background-color: rgba(59, 130, 246, 0.1);
    }

    .action-btn {
        width: 32px;
        height: 32px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        transition: all 0.2s;
    }

    .action-btn:hover {
        transform: translateY(-2px);
    }
</style>

<div style="background:white; padding:24px; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
    <!-- Header -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <h2 style="margin:0; font-size:20px; font-weight:600; color:#1e293b;"> Quản lý Lớp học phần</h2>
        <a href="{{ route('class-sections.create') }}"
            style="background:#16a34a; color:white; padding:10px 20px; border-radius:6px; text-decoration:none; font-weight:500; display:inline-flex; align-items:center; gap:8px;">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path
                    d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
            </svg>
            Thêm Lớp học phần
        </a>
    </div>

    @if(session('success'))
    <div
        style="background:#d1fae5; border-left:4px solid #10b981; padding:12px 16px; border-radius:6px; margin-bottom:16px; color:#065f46;">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div
        style="background:#fee2e2; border-left:4px solid #ef4444; padding:12px 16px; border-radius:6px; margin-bottom:16px; color:#991b1b;">
        {{ session('error') }}
    </div>
    @endif
    @if(session('warning'))
    <div
        style="background:#fef3c7; border-left:4px solid #f59e0b; padding:12px 16px; border-radius:6px; margin-bottom:16px; color:#92400e;">
        {{ session('warning') }}
    </div>
    @endif

    <!-- Filters -->
    <form action="{{ route('class-sections.index') }}" method="GET" style="margin-bottom:20px;">
        <!-- Row 1 -->
        <div style="display:flex; gap:12px; margin-bottom:12px; align-items:end;">
            <div style="flex:1;">
                <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Năm
                    học</label>
                <input type="text" name="academic_year" value="{{ $filters['academic_year'] ?? $academicYear }}"
                    placeholder="VD: 2024-2025"
                    style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
            </div>
            <div style="flex:1;">
                <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Học
                    kỳ</label>
                <select name="term"
                    style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                    <option value="">-- Tất cả --</option>
                    <option value="HK1" {{ ($filters['term'] ?? $term) == 'HK1' ? 'selected' : '' }}>Học kỳ 1</option>
                    <option value="HK2" {{ ($filters['term'] ?? $term) == 'HK2' ? 'selected' : '' }}>Học kỳ 2</option>
                    <option value="HE" {{ ($filters['term'] ?? $term) == 'HE' ? 'selected' : '' }}>Học kỳ Hè</option>
                </select>
            </div>
            <div style="flex:1.5;">
                <label
                    style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Khoa</label>
                <select name="faculty_id"
                    style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                    <option value="">-- Tất cả --</option>
                    @foreach($faculties as $faculty)
                    <option value="{{ $faculty->id }}"
                        {{ ($filters['faculty_id'] ?? '') == $faculty->id ? 'selected' : '' }}>{{ $faculty->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div style="flex:2;">
                <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Tìm
                    kiếm</label>
                <input type="text" name="search" value="{{ $filters['search'] ?? '' }}" placeholder="Mã lớp, môn học..."
                    style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
            </div>
        </div>

        <!-- Row 2 -->
        <div style="display:flex; gap:12px; align-items:end;">
            <div style="flex:1;">
                <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Trạng
                    thái</label>
                <select name="status"
                    style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                    <option value="">-- Tất cả --</option>
                    <option value="active" {{ ($filters['status'] ?? '') == 'active' ? 'selected' : '' }}>Hoạt động
                    </option>
                    <option value="locked" {{ ($filters['status'] ?? '') == 'locked' ? 'selected' : '' }}>Tạm khóa
                    </option>
                </select>
            </div>
            <div style="flex:1.5;">
                <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Phòng
                    học</label>
                <select name="room_id"
                    style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                    <option value="">-- Tất cả --</option>
                    @foreach($rooms as $room)
                    <option value="{{ $room->id }}" {{ ($filters['room_id'] ?? '') == $room->id ? 'selected' : '' }}>
                        {{ $room->code }}
                    </option>
                    @endforeach
                </select>
            </div>
            <div style="flex:1;">
                <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Ca
                    học</label>
                <select name="shift_id"
                    style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                    <option value="">-- Tất cả --</option>
                    @foreach($shifts as $shift)
                    <option value="{{ $shift->id }}" {{ ($filters['shift_id'] ?? '') == $shift->id ? 'selected' : '' }}>
                        Ca {{ $shift->start_period }}-{{ $shift->end_period }}</option>
                    @endforeach
                </select>
            </div>
            <div style="flex:1.5;">
                <div style="padding-top:26px;">
                    <label style="display:inline-flex; align-items:center; gap:6px; cursor:pointer;">
                        <input type="checkbox" name="unassigned_lecturer" value="1"
                            {{ ($filters['unassigned_lecturer'] ?? '') == '1' ? 'checked' : '' }}
                            style="width:18px; height:18px;">
                        <span style="font-size:14px; color:#475569;">Chưa phân công GV</span>
                    </label>
                </div>
            </div>
            <button type="submit"
                style="background:#1976d2; color:white; padding:10px 24px; border:none; border-radius:6px; cursor:pointer; font-weight:500; display:flex; align-items:center; gap:8px;">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path
                        d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                </svg>
                Lọc
            </button>
            @if(request()->hasAny(['search','faculty_id','status','room_id','shift_id','unassigned_lecturer']))
            <a href="{{ route('class-sections.index') }}"
                style="padding:10px 20px; border:1px solid #cbd5e0; border-radius:6px; text-decoration:none; color:#475569; font-weight:500;">Xóa
                bộ lọc</a>
            @endif
        </div>
    </form>

    <!-- Table -->
    <div style="overflow-x:auto;">
        <table class="table-zebra" style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f8fafc; border-bottom:2px solid #e2e8f0;">
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">MÃ LHP
                    </th>
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">TÊN MÔN
                        HỌC</th>
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">KHOA</th>
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">GIẢNG
                        VIÊN</th>
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">LỊCH
                        &amp; PHÒNG</th>
                    <th style="padding:12px; text-align:center; font-weight:600; color:#475569; font-size:13px;">SĨ SỐ
                    </th>
                    <th style="padding:12px; text-align:center; font-weight:600; color:#475569; font-size:13px;">TRẠNG
                        THÁI</th>
                    <th style="padding:12px; text-align:center; font-weight:600; color:#475569; font-size:13px;">THAO
                        TÁC</th>
                </tr>
            </thead>
            <tbody>
                @forelse($classSections as $cs)
                @php
                $enrolled = $cs->registrations->count();
                $capacity = $cs->max_capacity;
                $percentage = $capacity > 0 ? ($enrolled / $capacity * 100) : 0;
                $days = ['', 'Thứ 2','Thứ 3','Thứ 4','Thứ 5','Thứ 6','Thứ 7','CN'];
                if ($percentage >= 90) { $bgColor = '#fee2e2'; $textColor = '#991b1b'; }
                elseif ($percentage >= 70) { $bgColor = '#fef3c7'; $textColor = '#92400e'; }
                else { $bgColor = '#dcfce7'; $textColor = '#166534'; }
                @endphp
                <tr style="border-bottom:1px solid #e2e8f0;">
                    <td style="padding:12px;"><code
                            style="background:#fef3c7; color:#92400e; padding:4px 8px; border-radius:4px; font-size:13px; font-weight:600;">{{ $cs->course->code ?? 'N/A' }}-{{ $cs->section_code }}</code>
                    </td>
                    <td style="padding:12px; font-weight:500; color:#1e293b;">{{ $cs->course->name ?? 'N/A' }}</td>
                    <td style="padding:12px;"><span
                            style="background:#dbeafe; color:#1e40af; padding:4px 12px; border-radius:12px; font-size:12px; font-weight:500;">{{ $cs->course->faculty->code ?? 'N/A' }}</span>
                    </td>
                    <td style="padding:12px;">
                        @if($cs->lecturer)
                        <span style="color:#166534;"> {{ $cs->lecturer->name }}</span>
                        @else
                        <span style="color:#94a3b8; font-style:italic;">Chưa phân công</span>
                        @endif
                    </td>
                    <td style="padding:12px; font-size:13px;">
                        <div style="margin-bottom:2px;"><strong>{{ $days[$cs->day_of_week] ?? '' }}</strong>
                            @if($cs->shift)<span style="color:#64748b;">(Ca
                                {{ $cs->shift->start_period }}-{{ $cs->shift->end_period }})</span>@endif</div>
                        @if($cs->room)
                        <span style="color:#64748b;"> {{ $cs->room->code }}</span>
                        @else
                        <span style="color:#94a3b8; font-style:italic;">Chưa xếp phòng</span>
                        @endif
                    </td>
                    <td style="padding:12px; text-align:center;"><span
                            style="background:{{ $bgColor }}; color:{{ $textColor }}; padding:4px 10px; border-radius:8px; font-size:12px; font-weight:600;">{{ $enrolled }}/{{ $capacity }}</span>
                    </td>
                    <td style="padding:12px; text-align:center;">
                        @if(($cs->status ?? 'active') === 'active')
                        <span
                            style="background:#dcfce7; color:#166534; padding:4px 12px; border-radius:12px; font-size:12px; font-weight:600;">
                            Hoạt động</span>
                        @else
                        <span
                            style="background:#e5e7eb; color:#374151; padding:4px 12px; border-radius:12px; font-size:12px; font-weight:600;">
                            Tạm khóa</span>
                        @endif
                    </td>
                    <td style="padding:12px; text-align:center;">
                        <div style="display:inline-flex; gap:6px;">
                            <button onclick="viewDetail({{ $cs->id }})" class="action-btn"
                                style="background:#10b981; color:white; border:none; cursor:pointer;"
                                title="Xem chi tiết">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path
                                        d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                    <path
                                        d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                                </svg>
                            </button>
                            <a href="{{ route('class-sections.edit', $cs) }}" class="action-btn"
                                style="background:#1976d2; color:white; text-decoration:none;" title="Sửa">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path
                                        d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z" />
                                </svg>
                            </a>
                            <form action="{{ route('class-sections.destroy', $cs) }}" method="POST"
                                style="display:inline;"
                                onsubmit="return confirm('Xác nhận xóa lớp {{ $cs->course->code ?? '' }}-{{ $cs->section_code }}?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn"
                                    style="background:#dc2626; color:white; border:none; cursor:pointer;" title="Xóa">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path
                                            d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                        <path fill-rule="evenodd"
                                            d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="8" style="padding:60px 20px; text-align:center; color:#94a3b8;">
                        <svg width="64" height="64" fill="currentColor" viewBox="0 0 16 16"
                            style="opacity:0.3; margin-bottom:16px;">
                            <path
                                d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z" />
                        </svg>
                        <div style="font-size:16px; font-weight:500;">Không tìm thấy lớp học phần nào</div>
                        <div style="font-size:14px; margin-top:4px;">Thử thay đổi bộ lọc hoặc thêm lớp học phần mới
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($classSections->hasPages())
    <div style="margin-top:24px; display:flex; justify-content:center;">{{ $classSections->appends($filters)->links() }}
    </div>
    @endif
</div>

<!-- Modal -->
<div id="detailModal"
    style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div
        style="background:white; border-radius:12px; width:90%; max-width:800px; max-height:85vh; overflow:auto; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1);">
        <div
            style="padding:24px; border-bottom:2px solid #10b981; background:linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <h3 style="margin:0; font-size:20px; font-weight:600; color:white;">Chi tiết Lớp học phần</h3>
        </div>
        <div style="padding:28px;" id="detailBody">Đang tải...</div>
    </div>
</div>

<script>
    function viewDetail(id) {
        const modal = document.getElementById('detailModal');
        const body = document.getElementById('detailBody');
        modal.style.display = 'flex';
        body.innerHTML = 'Đang tải dữ liệu...';
        fetch('/admin/class-sections/' + id + '/detail')
            .then(res => res.json())
            .then(data => {
                const cs = data.class_section;
                const students = data.students || [];
                const days = ['', 'Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'CN'];
                body.innerHTML = `
        <div style="background:#f8fafc; padding:20px; border-radius:8px; margin-bottom:20px;">
          <code style="background:#fef3c7; color:#92400e; padding:6px 12px; border-radius:6px; font-weight:700;">${cs.course?.code||'N/A'}-${cs.section_code}</code>
          <h4 style="margin:12px 0 0 0; font-size:18px;">${cs.course?.name||'N/A'}</h4>
        </div>
        <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
          <div><label style="font-size:13px; color:#64748b;">Năm học - Học kỳ</label><div style="background:#f1f5f9; padding:10px; border-radius:6px; margin-top:6px;">${cs.academic_year} - ${cs.term}</div></div>
          <div><label style="font-size:13px; color:#64748b;">Khoa</label><div style="background:#f1f5f9; padding:10px; border-radius:6px; margin-top:6px;">${cs.course?.faculty?.name||'N/A'}</div></div>
          <div><label style="font-size:13px; color:#64748b;">Giảng viên</label><div style="background:#f1f5f9; padding:10px; border-radius:6px; margin-top:6px;">${cs.lecturer?cs.lecturer.name:'<em>Chưa phân công</em>'}</div></div>
          <div><label style="font-size:13px; color:#64748b;">Lịch học</label><div style="background:#f1f5f9; padding:10px; border-radius:6px; margin-top:6px;">${days[cs.day_of_week]||'N/A'}${cs.shift?` (Ca ${cs.shift.start_period}-${cs.shift.end_period})`:''}</div></div>
          <div><label style="font-size:13px; color:#64748b;">Phòng học</label><div style="background:#f1f5f9; padding:10px; border-radius:6px; margin-top:6px;">${cs.room?cs.room.code:'<em>Chưa xếp phòng</em>'}</div></div>
          <div><label style="font-size:13px; color:#64748b;">Sĩ số</label><div style="background:#f1f5f9; padding:10px; border-radius:6px; margin-top:6px; text-align:center; font-weight:700;">${students.length}/${cs.max_capacity}</div></div>
        </div>
        <div>
          <h5 style="font-size:16px; margin-bottom:12px;">Danh sách Sinh viên (${students.length})</h5>
          ${students.length>0 ? `<div style="background:#f8fafc; border-radius:8px; overflow:hidden;"><table style="width:100%;"><thead style="background:#e2e8f0;"><tr><th style="padding:10px; text-align:left;">STT</th><th style="padding:10px; text-align:left;">MSSV</th><th style="padding:10px; text-align:left;">Họ tên</th></tr></thead><tbody>${students.map((s,i)=>`<tr style=\"border-bottom:1px solid #e2e8f0;\"><td style=\"padding:10px;\">${i+1}</td><td style=\"padding:10px;\">${s.student_code}</td><td style=\"padding:10px;\">${s.name}</td></tr>`).join('')}</tbody></table></div>` : '<p style="text-align:center; color:#94a3b8;">Chưa có sinh viên</p>'}
        </div>
        <div style="margin-top:20px; text-align:right;"><button onclick="closeDetailModal()" style="padding:10px 24px; border:1px solid #cbd5e0; border-radius:6px; background:white; color:#475569; cursor:pointer;">Đóng</button></div>
      `;
            })
            .catch(() => {
                body.innerHTML = '<p style="color:#dc2626;">Không thể tải dữ liệu</p>';
            });
    }

    function closeDetailModal() {
        document.getElementById('detailModal').style.display = 'none';
    }
    // close on backdrop
    (document.getElementById('detailModal') || {}).addEventListener?.('click', function(e) {
        if (e.target === this) closeDetailModal();
    });
</script>
@endsection