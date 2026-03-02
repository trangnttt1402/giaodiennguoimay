@extends('admin.layout')

@section('title', 'Quản lý Học phần')

@section('content')
<style>
    .faculty-badge {
        padding: 4px 12px;
        border-radius: 12px;
        font-size: 12px;
        font-weight: 500;
    }

    .faculty-cntt {
        background: #dbeafe;
        color: #1e40af;
    }

    .faculty-kt {
        background: #dcfce7;
        color: #166534;
    }

    .faculty-nn {
        background: #fef3c7;
        color: #92400e;
    }

    .faculty-default {
        background: #f3e8ff;
        color: #6b21a8;
    }

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

    .prereq-badge {
        background: #374151;
        color: #9ca3af;
        padding: 2px 8px;
        border-radius: 4px;
        font-size: 11px;
        margin-right: 4px;
        display: inline-block;
        margin-bottom: 2px;
    }
</style>

<div style="background:white; padding:24px; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
    <!-- Header -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <h2 style="margin:0; font-size:20px; font-weight:600; color:#1e293b;">
            📚 Quản lý Học phần
        </h2>
        <a href="{{ route('courses.create') }}" style="background:#16a34a; color:white; padding:10px 20px; border-radius:6px; text-decoration:none; font-weight:500; display:inline-flex; align-items:center; gap:8px;">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
            </svg>
            Thêm Học phần
        </a>
    </div>

    @if(session('success'))
    <div style="background:#d1fae5; border-left:4px solid #10b981; padding:12px 16px; border-radius:6px; margin-bottom:16px; color:#065f46;">
        ✓ {{ session('success') }}
    </div>
    @endif

    @if(session('error'))
    <div style="background:#fee2e2; border-left:4px solid #ef4444; padding:12px 16px; border-radius:6px; margin-bottom:16px; color:#991b1b;">
        ✗ {{ session('error') }}
    </div>
    @endif

    <!-- Filters - Single Row -->
    <form action="{{ route('courses.index') }}" method="GET" style="display:flex; gap:12px; margin-bottom:20px; align-items:end;">
        <div style="flex:1;">
            <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Khoa</label>
            <select name="faculty_id" style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                <option value="">-- Tất cả Khoa --</option>
                @foreach($faculties as $faculty)
                <option value="{{ $faculty->id }}" {{ request('faculty_id') == $faculty->id ? 'selected' : '' }}>
                    {{ $faculty->name }}
                </option>
                @endforeach
            </select>
        </div>

        <div style="flex:2;">
            <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Tìm kiếm</label>
            <input
                type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Nhập mã môn học hoặc tên môn học..."
                style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
        </div>

        <button type="submit" style="background:#1976d2; color:white; padding:10px 24px; border:none; border-radius:6px; cursor:pointer; font-weight:500; display:flex; align-items:center; gap:8px;">
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
            </svg>
            Lọc
        </button>

        @if(request('search') || request('faculty_id'))
        <a href="{{ route('courses.index') }}" style="padding:10px 20px; border:1px solid #cbd5e0; border-radius:6px; text-decoration:none; color:#475569; font-weight:500;">
            Xóa bộ lọc
        </a>
        @endif
    </form>

    <!-- Table with Zebra Striping -->
    <div style="overflow-x:auto;">
        <table class="table-zebra" style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f8fafc; border-bottom:2px solid #e2e8f0;">
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">MÃ MH</th>
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">TÊN MÔN HỌC</th>
                    <th style="padding:12px; text-align:center; font-weight:600; color:#475569; font-size:13px;">TÍN CHỈ</th>
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">KHOA</th>
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">MÔN TIÊN QUYẾT</th>
                    <th style="padding:12px; text-align:center; font-weight:600; color:#475569; font-size:13px;">THAO TÁC</th>
                </tr>
            </thead>
            <tbody>
                @forelse($courses as $course)
                <tr style="border-bottom:1px solid #e2e8f0;">
                    <td style="padding:12px;">
                        <code style="background:#fef3c7; color:#92400e; padding:4px 8px; border-radius:4px; font-size:13px; font-weight:600;">
                            {{ $course->code }}
                        </code>
                    </td>
                    <td style="padding:12px; font-weight:500; color:#1e293b;">{{ $course->name }}</td>
                    <td style="padding:12px; text-align:center;">
                        <span style="background:#dbeafe; color:#1e40af; padding:4px 12px; border-radius:12px; font-size:12px; font-weight:600;">
                            {{ $course->credits }} TC
                        </span>
                    </td>
                    <td style="padding:12px;">
                        @php
                        $facultyClass = 'faculty-default';
                        if(stripos($course->faculty->name, 'Công nghệ') !== false || stripos($course->faculty->name, 'CNTT') !== false) {
                        $facultyClass = 'faculty-cntt';
                        } elseif(stripos($course->faculty->name, 'Kinh tế') !== false) {
                        $facultyClass = 'faculty-kt';
                        } elseif(stripos($course->faculty->name, 'Ngoại ngữ') !== false) {
                        $facultyClass = 'faculty-nn';
                        }
                        @endphp
                        <span class="faculty-badge {{ $facultyClass }}">
                            {{ $course->faculty->code }}
                        </span>
                    </td>
                    <td style="padding:12px;">
                        @if($course->prerequisites->count() > 0)
                        @foreach($course->prerequisites as $prereq)
                        <span class="prereq-badge">{{ $prereq->code }}</span>
                        @endforeach
                        @else
                        <span style="color:#94a3b8; font-size:13px;">--</span>
                        @endif
                    </td>
                    <td style="padding:12px; text-align:center;">
                        <div style="display:inline-flex; gap:6px;">
                            <!-- Nút Xem chi tiết -->
                            <button
                                onclick="viewCourseDetail({{ $course->id }})"
                                class="action-btn"
                                style="background:#10b981; color:white; border:none; cursor:pointer;"
                                title="Xem chi tiết">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                                </svg>
                            </button>

                            <!-- Nút Sửa -->
                            <a
                                href="{{ route('courses.edit', $course) }}"
                                class="action-btn"
                                style="background:#1976d2; color:white; text-decoration:none;"
                                title="Sửa">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z" />
                                </svg>
                            </a>

                            <!-- Nút Sửa điều kiện tiên quyết -->
                            <button
                                onclick="openPrerequisitesModal({{ $course->id }}, '{{ $course->code }}', '{{ $course->name }}')"
                                class="action-btn"
                                style="background:#8b5cf6; color:white; border:none; cursor:pointer;"
                                title="Điều kiện tiên quyết">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M1 11a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3zm5-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v7a1 1 0 0 1-1 1H7a1 1 0 0 1-1-1V7zm5-5a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1h-2a1 1 0 0 1-1-1V2z" />
                                </svg>
                            </button>

                            <!-- Nút Xóa -->
                            <form action="{{ route('courses.destroy', $course) }}" method="POST" style="display:inline;" onsubmit="return confirm('⚠️ Xác nhận xóa môn học {{ $course->code }}?\n\nLưu ý: Nếu có lớp học phần liên quan sẽ bị xóa theo!');">
                                @csrf
                                @method('DELETE')
                                <button
                                    type="submit"
                                    class="action-btn"
                                    style="background:#dc2626; color:white; border:none; cursor:pointer;"
                                    title="Xóa">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" />
                                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z" />
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:60px 20px; text-align:center; color:#94a3b8;">
                        <svg width="64" height="64" fill="currentColor" viewBox="0 0 16 16" style="opacity:0.3; margin-bottom:16px;">
                            <path d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z" />
                        </svg>
                        <div style="font-size:16px; font-weight:500;">Không tìm thấy học phần nào</div>
                        <div style="font-size:14px; margin-top:4px;">Thử thay đổi bộ lọc hoặc thêm học phần mới</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    @if($courses->hasPages())
    <div style="margin-top:24px; display:flex; justify-content:center;">
        {{ $courses->links() }}
    </div>
    @endif
</div>

<!-- Modal Xem Chi tiết Môn học -->
<div id="detailModal" style="display:none; position:fixed; top:0; left:0; right:0; bottom:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:12px; width:90%; max-width:700px; max-height:85vh; overflow:auto; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="padding:24px; border-bottom:2px solid #10b981; background:linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <h3 style="margin:0; font-size:20px; font-weight:600; color:white; display:flex; align-items:center; gap:10px;">
                <svg width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z" />
                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z" />
                </svg>
                Chi tiết Môn học
            </h3>
        </div>

        <div style="padding:28px;">
            <!-- Mã và Tên môn học -->
            <div style="background:#f8fafc; padding:20px; border-radius:8px; border-left:4px solid #10b981; margin-bottom:20px;">
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:8px;">
                    <code id="detailCode" style="background:#fef3c7; color:#92400e; padding:6px 12px; border-radius:6px; font-size:15px; font-weight:700; letter-spacing:0.5px;"></code>
                    <span id="detailStatus" style="padding:4px 12px; border-radius:12px; font-size:12px; font-weight:600;"></span>
                </div>
                <h4 id="detailName" style="margin:0; font-size:18px; font-weight:600; color:#1e293b;"></h4>
            </div>

            <!-- Grid thông tin -->
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-bottom:20px;">
                <!-- Số tín chỉ -->
                <div>
                    <label style="display:block; font-size:13px; color:#64748b; font-weight:500; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.5px;">
                        📊 Số tín chỉ
                    </label>
                    <div id="detailCredits" style="background:#dbeafe; color:#1e40af; padding:8px 16px; border-radius:8px; font-size:16px; font-weight:700; text-align:center;"></div>
                </div>

                <!-- Khoa -->
                <div>
                    <label style="display:block; font-size:13px; color:#64748b; font-weight:500; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.5px;">
                        🏛️ Khoa quản lý
                    </label>
                    <div id="detailFaculty" style="padding:8px 16px; border-radius:8px; font-size:14px; font-weight:600; text-align:center;"></div>
                </div>
            </div>

            <!-- Loại học phần -->
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:13px; color:#64748b; font-weight:500; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.5px;">
                    🏷️ Loại học phần
                </label>
                <div id="detailType" style="background:#f1f5f9; padding:10px 16px; border-radius:8px; font-size:14px; color:#475569; font-weight:500;"></div>
            </div>

            <!-- Môn tiên quyết -->
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:13px; color:#64748b; font-weight:500; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.5px;">
                    🔗 Điều kiện tiên quyết
                </label>
                <div id="detailPrerequisites" style="background:#f1f5f9; padding:12px 16px; border-radius:8px; min-height:40px;"></div>
            </div>

            <!-- Mô tả -->
            <div style="margin-bottom:20px;">
                <label style="display:block; font-size:13px; color:#64748b; font-weight:500; margin-bottom:6px; text-transform:uppercase; letter-spacing:0.5px;">
                    📝 Mô tả chi tiết
                </label>
                <div id="detailDescription" style="background:#f8fafc; padding:16px; border-radius:8px; border:1px solid #e2e8f0; font-size:14px; color:#475569; line-height:1.6; min-height:60px; white-space:pre-wrap;"></div>
            </div>

            <!-- Nút đóng -->
            <div style="display:flex; justify-content:flex-end; gap:12px; padding-top:16px; border-top:1px solid #e2e8f0;">
                <button onclick="closeDetailModal()" style="padding:10px 24px; border:1px solid #cbd5e0; border-radius:6px; background:white; color:#475569; cursor:pointer; font-weight:500;">
                    Đóng
                </button>
                <button onclick="editFromDetail()" id="editFromDetailBtn" style="padding:10px 24px; border:none; border-radius:6px; background:#1976d2; color:white; cursor:pointer; font-weight:500; display:flex; align-items:center; gap:8px;">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z" />
                    </svg>
                    Chỉnh sửa
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Thiết lập Điều kiện Tiên quyết -->
<style>
    .prereq-modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(30, 20, 60, 0.5);
        backdrop-filter: blur(4px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        animation: prereqFadeIn 0.2s ease;
    }

    @keyframes prereqFadeIn {
        from {
            opacity: 0;
        }

        to {
            opacity: 1;
        }
    }

    @keyframes prereqSlideIn {
        from {
            opacity: 0;
            transform: translateY(20px) scale(0.97);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .prereq-modal-card {
        background: white;
        border-radius: 16px;
        width: 92%;
        max-width: 640px;
        max-height: 85vh;
        display: flex;
        flex-direction: column;
        box-shadow: 0 25px 50px -12px rgba(107, 75, 157, 0.25);
        animation: prereqSlideIn 0.25s ease;
        overflow: hidden;
    }

    .prereq-modal-header {
        padding: 20px 24px;
        background: linear-gradient(135deg, #6B7BD9 0%, #6B4B9D 100%);
        color: white;
        flex-shrink: 0;
    }

    .prereq-modal-header h3 {
        margin: 0;
        font-size: 18px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .prereq-modal-header p {
        margin: 6px 0 0 0;
        font-size: 13px;
        color: rgba(255, 255, 255, 0.85);
    }

    .prereq-modal-header .prereq-course-tag {
        display: inline-block;
        background: rgba(255, 255, 255, 0.2);
        padding: 2px 10px;
        border-radius: 6px;
        font-weight: 600;
        font-size: 13px;
        color: white;
    }

    .prereq-modal-body {
        padding: 20px 24px;
        overflow-y: auto;
        flex: 1;
    }

    .prereq-search-box {
        width: 100%;
        padding: 10px 14px 10px 38px;
        border: 1.5px solid #e2e0ed;
        border-radius: 10px;
        font-size: 14px;
        background: #faf9fe url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%2394a3b8' viewBox='0 0 16 16'%3E%3Cpath d='M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z'/%3E%3C/svg%3E") no-repeat 12px center;
        transition: all 0.2s;
        margin-bottom: 16px;
        color: #1e293b;
    }

    .prereq-search-box:focus {
        outline: none;
        border-color: #6B4B9D;
        box-shadow: 0 0 0 3px rgba(107, 75, 157, 0.1);
        background-color: white;
    }

    .prereq-search-box::placeholder {
        color: #94a3b8;
    }

    .prereq-counter {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #64748b;
        margin-bottom: 12px;
    }

    .prereq-counter-num {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #6B4B9D;
        color: white;
        width: 22px;
        height: 22px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 700;
    }

    .prereq-list {
        display: flex;
        flex-direction: column;
        gap: 8px;
    }

    .prereq-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px 14px;
        background: #faf9fe;
        border: 1.5px solid #ede9f6;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s;
        user-select: none;
    }

    .prereq-item:hover {
        border-color: #c4b5d9;
        background: #f3eeff;
    }

    .prereq-item.selected {
        border-color: #6B4B9D;
        background: #ede5f7;
        box-shadow: 0 0 0 1px rgba(107, 75, 157, 0.1);
    }

    .prereq-item input[type="checkbox"] {
        width: 18px;
        height: 18px;
        accent-color: #6B4B9D;
        cursor: pointer;
        flex-shrink: 0;
    }

    .prereq-item-code {
        font-weight: 700;
        color: #6B4B9D;
        font-size: 13px;
        background: rgba(107, 75, 157, 0.1);
        padding: 3px 10px;
        border-radius: 6px;
        min-width: 60px;
        text-align: center;
        flex-shrink: 0;
    }

    .prereq-item-name {
        font-size: 14px;
        color: #1e293b;
        font-weight: 500;
    }

    .prereq-item-faculty {
        margin-left: auto;
        font-size: 11px;
        color: #94a3b8;
        flex-shrink: 0;
    }

    .prereq-item.hidden {
        display: none;
    }

    .prereq-empty {
        text-align: center;
        padding: 24px;
        color: #94a3b8;
        font-size: 14px;
    }

    .prereq-modal-footer {
        padding: 16px 24px;
        border-top: 1.5px solid #ede9f6;
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        align-items: center;
        background: #faf9fe;
        flex-shrink: 0;
    }

    .prereq-btn-cancel {
        padding: 10px 22px;
        border: 1.5px solid #e2e0ed;
        border-radius: 10px;
        background: white;
        color: #64748b;
        cursor: pointer;
        font-weight: 500;
        font-size: 14px;
        transition: all 0.2s;
    }

    .prereq-btn-cancel:hover {
        background: #f8f6fc;
        border-color: #c4b5d9;
        color: #6B4B9D;
    }

    .prereq-btn-save {
        padding: 10px 24px;
        border: none;
        border-radius: 10px;
        background: linear-gradient(135deg, #6B7BD9 0%, #6B4B9D 100%);
        color: white;
        cursor: pointer;
        font-weight: 600;
        font-size: 14px;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.25s;
        box-shadow: 0 2px 8px rgba(107, 75, 157, 0.25);
    }

    .prereq-btn-save:hover {
        background: linear-gradient(135deg, #5a6ac8 0%, #5a3a8c 100%);
        box-shadow: 0 4px 16px rgba(107, 75, 157, 0.35);
        transform: translateY(-1px);
    }

    .prereq-btn-clear {
        margin-right: auto;
        padding: 8px 16px;
        border: none;
        border-radius: 8px;
        background: transparent;
        color: #94a3b8;
        cursor: pointer;
        font-size: 13px;
        transition: all 0.2s;
    }

    .prereq-btn-clear:hover {
        background: #fee2e2;
        color: #ef4444;
    }
</style>

<div id="prerequisitesModal" class="prereq-modal-overlay" onclick="if(event.target===this)closePrerequisitesModal()">
    <div class="prereq-modal-card" onclick="event.stopPropagation()">
        <div class="prereq-modal-header">
            <h3>
                <svg width="22" height="22" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 17h-2v-2h2v2zm2.07-7.75l-.9.92C13.45 12.9 13 13.5 13 15h-2v-.5c0-1.1.45-2.1 1.17-2.83l1.24-1.26c.37-.36.59-.86.59-1.41 0-1.1-.9-2-2-2s-2 .9-2 2H8c0-2.21 1.79-4 4-4s4 1.79 4 4c0 .88-.36 1.68-.93 2.25z" />
                </svg>
                Điều kiện Tiên quyết
            </h3>
            <p>Môn học: <strong id="modalCourseName"></strong> <span class="prereq-course-tag" id="modalCourseCode"></span></p>
        </div>

        <div class="prereq-modal-body">
            <form id="prerequisitesForm" method="POST">
                @csrf
                @method('PUT')

                <input type="text" class="prereq-search-box" id="prereqSearchInput"
                    placeholder="Tìm kiếm môn học..." oninput="filterPrereqItems(this.value)">

                <div class="prereq-counter">
                    <span>Đã chọn</span>
                    <span class="prereq-counter-num" id="prereqCountNum">0</span>
                    <span>môn tiên quyết</span>
                </div>

                <div class="prereq-list" id="prereqList">
                    @foreach($allCourses ?? [] as $c)
                    <label class="prereq-item" data-code="{{ strtolower($c->code) }}" data-name="{{ strtolower($c->name) }}" data-id="{{ $c->id }}">
                        <input type="checkbox" name="prerequisites[]" value="{{ $c->id }}"
                            onchange="togglePrereqItem(this)">
                        <span class="prereq-item-code">{{ $c->code }}</span>
                        <span class="prereq-item-name">{{ $c->name }}</span>
                        @if($c->faculty)
                        <span class="prereq-item-faculty">{{ $c->faculty->code ?? '' }}</span>
                        @endif
                    </label>
                    @endforeach
                </div>

                <div class="prereq-empty" id="prereqEmpty" style="display:none;">
                    Không tìm thấy môn học phù hợp
                </div>
            </form>
        </div>

        <div class="prereq-modal-footer">
            <button type="button" class="prereq-btn-clear" onclick="clearAllPrereqs()" title="Bỏ chọn tất cả">
                ✕ Bỏ chọn tất cả
            </button>
            <button type="button" class="prereq-btn-cancel" onclick="closePrerequisitesModal()">Hủy</button>
            <button type="button" class="prereq-btn-save" onclick="submitPrerequisites()">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41L9 16.17z" />
                </svg>
                Lưu thay đổi
            </button>
        </div>
    </div>
</div>

<script>
    let currentCourseId = null;

    // Hàm xem chi tiết môn học
    function viewCourseDetail(courseId) {
        currentCourseId = courseId;

        // Hiển thị modal
        document.getElementById('detailModal').style.display = 'flex';

        // Load dữ liệu qua AJAX
        fetch(`/admin/courses/${courseId}/detail`)
            .then(res => res.json())
            .then(data => {
                // Mã môn học
                document.getElementById('detailCode').textContent = data.code;

                // Tên môn học
                document.getElementById('detailName').textContent = data.name;

                // Trạng thái
                const statusElem = document.getElementById('detailStatus');
                if (data.is_active) {
                    statusElem.textContent = '✓ Hoạt động';
                    statusElem.style.background = '#dcfce7';
                    statusElem.style.color = '#166534';
                } else {
                    statusElem.textContent = '✕ Ngưng hoạt động';
                    statusElem.style.background = '#fee2e2';
                    statusElem.style.color = '#991b1b';
                }

                // Số tín chỉ
                document.getElementById('detailCredits').textContent = data.credits + ' Tín chỉ';

                // Khoa
                const facultyElem = document.getElementById('detailFaculty');
                facultyElem.textContent = data.faculty.name;

                // Áp dụng màu sắc cho khoa
                let facultyClass = 'faculty-default';
                if (data.faculty.name.includes('Công nghệ') || data.faculty.name.includes('CNTT')) {
                    facultyElem.style.background = '#dbeafe';
                    facultyElem.style.color = '#1e40af';
                } else if (data.faculty.name.includes('Kinh tế')) {
                    facultyElem.style.background = '#dcfce7';
                    facultyElem.style.color = '#166534';
                } else if (data.faculty.name.includes('Ngoại ngữ')) {
                    facultyElem.style.background = '#fef3c7';
                    facultyElem.style.color = '#92400e';
                } else {
                    facultyElem.style.background = '#f3e8ff';
                    facultyElem.style.color = '#6b21a8';
                }

                // Loại học phần
                const typeElem = document.getElementById('detailType');
                if (data.type) {
                    typeElem.textContent = data.type;
                    typeElem.style.fontWeight = '600';
                } else {
                    typeElem.textContent = 'Chưa phân loại';
                    typeElem.style.color = '#94a3b8';
                    typeElem.style.fontStyle = 'italic';
                }

                // Môn tiên quyết
                const prereqElem = document.getElementById('detailPrerequisites');
                if (data.prerequisites && data.prerequisites.length > 0) {
                    prereqElem.innerHTML = data.prerequisites.map(p =>
                        `<span style="background:#374151; color:#9ca3af; padding:6px 12px; border-radius:6px; font-size:13px; margin-right:8px; margin-bottom:8px; display:inline-block; font-weight:600;">
                            ${p.code} - ${p.name}
                        </span>`
                    ).join('');
                } else {
                    prereqElem.innerHTML = '<span style="color:#94a3b8; font-style:italic;">Không có điều kiện tiên quyết</span>';
                }

                // Mô tả
                const descElem = document.getElementById('detailDescription');
                if (data.description && data.description.trim()) {
                    descElem.textContent = data.description;
                    descElem.style.color = '#475569';
                } else {
                    descElem.innerHTML = '<em style="color:#94a3b8;">Chưa có mô tả chi tiết</em>';
                }
            })
            .catch(err => {
                console.error('Error loading course detail:', err);
                alert('Không thể tải thông tin chi tiết môn học');
                closeDetailModal();
            });
    }

    function closeDetailModal() {
        document.getElementById('detailModal').style.display = 'none';
        currentCourseId = null;
    }

    function editFromDetail() {
        if (currentCourseId) {
            window.location.href = `/admin/courses/${currentCourseId}/edit`;
        }
    }

    // Đóng modal khi click bên ngoài
    document.getElementById('detailModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closeDetailModal();
        }
    });

    function openPrerequisitesModal(courseId, courseCode, courseName) {
        document.getElementById('modalCourseCode').textContent = courseCode;
        document.getElementById('modalCourseName').textContent = courseName;
        document.getElementById('prerequisitesForm').action = `/admin/courses/${courseId}/prerequisites`;
        document.getElementById('prereqSearchInput').value = '';

        // Hide the course's own item and reset all
        const items = document.querySelectorAll('.prereq-item');
        items.forEach(item => {
            item.classList.remove('hidden', 'selected');
            item.querySelector('input[type="checkbox"]').checked = false;
            // Hide the course itself
            if (item.dataset.code === courseCode.toLowerCase()) {
                item.classList.add('hidden');
            }
        });
        document.getElementById('prereqEmpty').style.display = 'none';
        updatePrereqCount();

        document.getElementById('prerequisitesModal').style.display = 'flex';

        // Load current prerequisites via AJAX
        fetch(`/admin/courses/${courseId}/prerequisites`)
            .then(res => res.json())
            .then(data => {
                items.forEach(item => {
                    const cb = item.querySelector('input[type="checkbox"]');
                    if (data.prerequisites.includes(parseInt(cb.value))) {
                        cb.checked = true;
                        item.classList.add('selected');
                    }
                });
                updatePrereqCount();
            })
            .catch(err => console.error('Error loading prerequisites:', err));
    }

    function closePrerequisitesModal() {
        document.getElementById('prerequisitesModal').style.display = 'none';
    }

    function togglePrereqItem(cb) {
        cb.closest('.prereq-item').classList.toggle('selected', cb.checked);
        updatePrereqCount();
    }

    function updatePrereqCount() {
        const count = document.querySelectorAll('.prereq-item:not(.hidden) input[type="checkbox"]:checked').length;
        document.getElementById('prereqCountNum').textContent = count;
    }

    function filterPrereqItems(query) {
        const q = query.toLowerCase().trim();
        const items = document.querySelectorAll('.prereq-item');
        let visible = 0;
        items.forEach(item => {
            if (item.dataset.code === document.getElementById('modalCourseCode').textContent.toLowerCase()) {
                return; // keep hidden
            }
            const match = !q || item.dataset.code.includes(q) || item.dataset.name.includes(q);
            item.classList.toggle('hidden', !match);
            if (match) visible++;
        });
        document.getElementById('prereqEmpty').style.display = visible === 0 ? 'block' : 'none';
    }

    function clearAllPrereqs() {
        document.querySelectorAll('.prereq-item input[type="checkbox"]').forEach(cb => {
            cb.checked = false;
            cb.closest('.prereq-item').classList.remove('selected');
        });
        updatePrereqCount();
    }

    function submitPrerequisites() {
        const form = document.getElementById('prerequisitesForm');
        const formData = new FormData(form);
        const url = form.action;

        fetch(url, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    closePrerequisitesModal();
                    window.location.reload();
                } else {
                    alert(data.message || 'Có lỗi xảy ra');
                }
            })
            .catch(err => {
                console.error('Error updating prerequisites:', err);
                alert('Có lỗi xảy ra khi cập nhật điều kiện tiên quyết');
            });
    }

    // Close modal when clicking outside
    document.getElementById('prerequisitesModal')?.addEventListener('click', function(e) {
        if (e.target === this) {
            closePrerequisitesModal();
        }
    });
</script>
@endsection