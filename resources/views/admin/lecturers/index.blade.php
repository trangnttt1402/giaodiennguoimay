@extends('admin.layout')

@section('title', 'Quản lý Giảng viên')

@section('styles')
<style>
    .filter-section {
        background: white;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 20px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    }

    .filter-row {
        display: grid;
        grid-template-columns: 2fr 1fr auto auto;
        gap: 12px;
        align-items: end;
    }

    .filter-group {
        display: flex;
        flex-direction: column;
    }

    .filter-label {
        font-size: 13px;
        font-weight: 500;
        color: #475569;
        margin-bottom: 6px;
    }

    .filter-input {
        padding: 8px 12px;
        border: 1px solid #cbd5e0;
        border-radius: 6px;
        font-size: 14px;
    }

    .filter-input:focus {
        outline: none;
        border-color: #6B4B9D;
        box-shadow: 0 0 0 3px rgba(107, 75, 157, 0.1);
    }

    .lecturers-table-container {
        background: white;
        border-radius: 8px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        overflow: hidden;
    }

    .lecturers-table {
        width: 100%;
        border-collapse: collapse;
    }

    .lecturers-table thead {
        background: #f8fafc;
        border-bottom: 2px solid #e2e8f0;
    }

    .lecturers-table th {
        padding: 12px 16px;
        text-align: left;
        font-weight: 600;
        color: #475569;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .lecturers-table tbody tr {
        border-bottom: 1px solid #e2e8f0;
        transition: background 0.2s;
    }

    .lecturers-table tbody tr:hover {
        background: rgba(107, 75, 157, 0.04);
    }

    .lecturers-table td {
        padding: 14px 16px;
        color: #1e293b;
        font-size: 14px;
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
        border: none;
        cursor: pointer;
        text-decoration: none;
    }

    .action-btn:hover {
        transform: translateY(-2px);
    }

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

    .faculty-default {
        background: #f3e8ff;
        color: #6b21a8;
    }

    /* Detail Modal */
    .detail-modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.5);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }

    .detail-modal-content {
        background: white;
        border-radius: 12px;
        width: 90%;
        max-width: 560px;
        max-height: 85vh;
        overflow: auto;
        box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        animation: modalSlideIn 0.25s ease;
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: translateY(20px) scale(0.97);
        }

        to {
            opacity: 1;
            transform: translateY(0) scale(1);
        }
    }

    .modal-header {
        padding: 24px;
        border-bottom: 2px solid #6B4B9D;
        background: linear-gradient(135deg, #6B7BD9 0%, #6B4B9D 100%);
        text-align: center;
    }

    .modal-header h3 {
        margin: 0;
        font-size: 20px;
        font-weight: 600;
        color: white;
    }

    .modal-avatar {
        width: 64px;
        height: 64px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.2);
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px;
        font-size: 24px;
        font-weight: 700;
        color: white;
        border: 3px solid rgba(255, 255, 255, 0.3);
    }

    .modal-body {
        padding: 24px;
    }

    .detail-row {
        display: flex;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f1f5f9;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        width: 110px;
        font-size: 13px;
        color: #64748b;
        font-weight: 500;
        flex-shrink: 0;
    }

    .detail-value {
        flex: 1;
        font-size: 14px;
        color: #1e293b;
        font-weight: 500;
    }

    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #e2e8f0;
        display: flex;
        gap: 12px;
        justify-content: flex-end;
        background: #f8fafc;
    }

    @media (max-width: 768px) {
        .filter-row {
            grid-template-columns: 1fr;
        }
    }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <div>
            <h2 style="margin:0; font-size:24px; font-weight:600; color:#1e293b;">👨‍🏫 Quản lý Giảng viên</h2>
            <p style="margin:4px 0 0 0; color:#64748b; font-size:14px;">Tìm kiếm, xem, thêm, sửa, xóa giảng viên</p>
        </div>
        <a href="{{ route('lecturers.create') }}" style="background:#10b981; color:white; padding:12px 20px; border-radius:8px; text-decoration:none; font-weight:500; display:inline-flex; align-items:center; gap:8px;">
            <svg width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z" />
            </svg>
            Thêm Giảng viên
        </a>
    </div>

    {{-- Alerts --}}
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

    <!-- Filter Section -->
    <div class="filter-section">
        <form action="{{ route('lecturers.index') }}" method="GET">
            <div class="filter-row">
                <div class="filter-group">
                    <label class="filter-label">🔍 Tìm kiếm (Mã / Tên / Email)</label>
                    <input type="text" name="search" class="filter-input" placeholder="Nhập mã, tên hoặc email giảng viên..." value="{{ request('search') }}">
                </div>
                <div class="filter-group">
                    <label class="filter-label">🏢 Khoa</label>
                    <select name="faculty_id" class="filter-input">
                        <option value="">-- Tất cả Khoa --</option>
                        @foreach($faculties as $faculty)
                        <option value="{{ $faculty->id }}" {{ request('faculty_id') == $faculty->id ? 'selected' : '' }}>
                            {{ $faculty->name }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex; gap:8px;">
                    <button type="submit" style="background:#1976d2; color:white; padding:8px 16px; border:none; border-radius:6px; cursor:pointer; font-weight:500; display:flex; align-items:center; gap:6px;">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z" />
                        </svg>
                        Lọc
                    </button>
                    @if(request('search') || request('faculty_id'))
                    <a href="{{ route('lecturers.index') }}" style="background:#f1f5f9; color:#64748b; padding:8px 16px; border-radius:6px; text-decoration:none; font-weight:500;">Xóa bộ lọc</a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="lecturers-table-container">
        <table class="lecturers-table">
            <thead>
                <tr>
                    <th>MÃ GV</th>
                    <th>GIẢNG VIÊN</th>
                    <th>EMAIL</th>
                    <th>KHOA</th>
                    <th>HỌC VỊ</th>
                    <th>SĐT</th>
                    <th style="text-align:center;">THAO TÁC</th>
                </tr>
            </thead>
            <tbody>
                @forelse($lecturers as $lecturer)
                <tr>
                    <td>
                        <code style="background:#fef3c7; color:#92400e; padding:4px 8px; border-radius:4px; font-size:13px; font-weight:700;">{{ $lecturer->code ?? '—' }}</code>
                    </td>
                    <td style="font-weight:600;">{{ $lecturer->name }}</td>
                    <td style="color:#64748b; font-size:13px;">{{ $lecturer->email }}</td>
                    <td>
                        @php
                        $fClass = 'faculty-default';
                        if($lecturer->faculty && (stripos($lecturer->faculty->name, 'Công nghệ') !== false || stripos($lecturer->faculty->name, 'CNTT') !== false)) {
                        $fClass = 'faculty-cntt';
                        } elseif($lecturer->faculty && stripos($lecturer->faculty->name, 'Kinh tế') !== false) {
                        $fClass = 'faculty-kt';
                        }
                        @endphp
                        <span class="faculty-badge {{ $fClass }}">{{ $lecturer->faculty->name ?? '—' }}</span>
                    </td>
                    <td>
                        @if($lecturer->degree)
                        <span style="background:#fef3c7; color:#92400e; padding:3px 10px; border-radius:6px; font-size:12px; font-weight:600;">{{ $lecturer->degree }}</span>
                        @else
                        <span style="color:#cbd5e1; font-size:13px;">—</span>
                        @endif
                    </td>
                    <td>
                        @if($lecturer->phone)
                        <span style="color:#64748b; font-size:13px;">{{ $lecturer->phone }}</span>
                        @else
                        <span style="color:#cbd5e1; font-size:13px;">—</span>
                        @endif
                    </td>
                    <td style="text-align:center;">
                        <div style="display:inline-flex; gap:6px;">
                            {{-- Xem chi tiết --}}
                            <button type="button" class="action-btn" style="background:#10b981; color:white;" title="Xem chi tiết"
                                onclick="showLecturerDetail({{ json_encode([
                                    'id' => $lecturer->id,
                                    'code' => $lecturer->code,
                                    'name' => $lecturer->name,
                                    'email' => $lecturer->email,
                                    'faculty' => $lecturer->faculty->name ?? '—',
                                    'degree' => $lecturer->degree ?? '—',
                                    'phone' => $lecturer->phone ?? '—',
                                ]) }})">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z" />
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z" />
                                </svg>
                            </button>
                            {{-- Sửa --}}
                            <a href="{{ route('lecturers.edit', $lecturer) }}" class="action-btn" style="background:#1976d2; color:white;" title="Chỉnh sửa giảng viên">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z" />
                                </svg>
                            </a>
                            {{-- Xóa --}}
                            <form action="{{ route('lecturers.destroy', $lecturer) }}" method="POST" style="display:inline;"
                                onsubmit="return confirm('⚠️ Xác nhận xóa giảng viên {{ $lecturer->name }}?\n\nHành động này không thể hoàn tác!');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn" style="background:#dc2626; color:white;" title="Xóa">
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
                    <td colspan="7" style="padding:60px 20px; text-align:center; color:#94a3b8;">
                        <svg width="64" height="64" fill="currentColor" viewBox="0 0 16 16" style="opacity:0.3; margin-bottom:16px;">
                            <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm2-3a2 2 0 1 1-4 0 2 2 0 0 1 4 0zm4 8c0 1-1 1-1 1H3s-1 0-1-1 1-4 6-4 6 3 6 4zm-1-.004c-.001-.246-.154-.986-.832-1.664C11.516 10.68 10.289 10 8 10c-2.29 0-3.516.68-4.168 1.332-.678.678-.83 1.418-.832 1.664h10z" />
                        </svg>
                        <div style="font-size:16px; font-weight:500;">Không tìm thấy giảng viên nào</div>
                        <div style="font-size:14px; margin-top:4px;">Thử thay đổi bộ lọc hoặc thêm giảng viên mới</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

        @if($lecturers->hasPages())
        <div style="padding:20px; border-top:1px solid #e2e8f0;">{{ $lecturers->links() }}</div>
        @endif
    </div>
</div>

<!-- Modal Chi tiết Giảng viên -->
<div id="lecturerDetailModal" class="detail-modal" onclick="if(event.target===this)closeLecturerDetail()">
    <div class="detail-modal-content">
        <div class="modal-header">
            <div class="modal-avatar" id="lecModalAvatar"></div>
            <h3 id="lecModalName" style="margin:0; color:white;"></h3>
            <div id="lecModalCode" style="display:inline-block; background:rgba(255,255,255,0.2); padding:3px 14px; border-radius:20px; font-size:13px; margin-top:6px; color:white;"></div>
        </div>
        <div class="modal-body">
            <div class="detail-row">
                <div class="detail-label">📧 Email</div>
                <div class="detail-value" id="lecModalEmail"></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">🏢 Khoa</div>
                <div class="detail-value" id="lecModalFaculty"></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">🎓 Học vị</div>
                <div class="detail-value" id="lecModalDegree"></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">📞 SĐT</div>
                <div class="detail-value" id="lecModalPhone"></div>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" onclick="closeLecturerDetail()" style="padding:10px 20px; border:1px solid #e2e8f0; border-radius:6px; background:white; color:#64748b; cursor:pointer; font-weight:500;">Đóng</button>
            <a href="#" id="lecModalEditLink" style="padding:10px 20px; border-radius:6px; background:linear-gradient(135deg, #6B7BD9 0%, #6B4B9D 100%); color:white; text-decoration:none; font-weight:500; display:inline-flex; align-items:center; gap:8px;">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5z" />
                </svg>
                Chỉnh sửa
            </a>
        </div>
    </div>
</div>

<script>
    function showLecturerDetail(data) {
        const initials = data.name.split(' ').map(w => w[0]).slice(-2).join('').toUpperCase();
        document.getElementById('lecModalAvatar').textContent = initials;
        document.getElementById('lecModalName').textContent = data.name;
        document.getElementById('lecModalCode').textContent = data.code || '—';
        document.getElementById('lecModalEmail').textContent = data.email;
        document.getElementById('lecModalFaculty').textContent = data.faculty;
        document.getElementById('lecModalDegree').textContent = data.degree;
        document.getElementById('lecModalPhone').textContent = data.phone;
        document.getElementById('lecModalEditLink').href = '/admin/lecturers/' + data.id + '/edit';
        document.getElementById('lecturerDetailModal').style.display = 'flex';
    }

    function closeLecturerDetail() {
        document.getElementById('lecturerDetailModal').style.display = 'none';
    }
</script>
@endsection