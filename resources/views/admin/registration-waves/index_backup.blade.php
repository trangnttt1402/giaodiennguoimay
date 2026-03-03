@extends('admin.layout')

@section('title', 'Quản lý Đợt đăng ký')

@section('content')
<style>
    .waves-container {
        max-width: 1400px;
        margin: 0 auto;
        padding: 24px;
    }
    .waves-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 32px;
        margin-bottom: 24px;
        color: white;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.2);
        display: flex;
        justify-content: space-between;
        align-items: center;
        animation: slideDown 0.5s ease-out;
    }
    @keyframes slideDown {
        from { opacity: 0; transform: translateY(-20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .waves-header h2 {
        margin: 0;
        font-size: 28px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .btn-add {
        background: white;
        color: #667eea;
        padding: 12px 24px;
        border-radius: 12px;
        text-decoration: none;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 10px;
        transition: all 0.3s;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }
    .btn-add:hover {
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(0,0,0,0.15);
        color: #667eea;
    }
    .alert-success {
        background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
        border-left: 4px solid #10b981;
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        color: #065f46;
        font-weight: 500;
        animation: slideIn 0.3s ease-out;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .alert-error {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        border-left: 4px solid #ef4444;
        padding: 16px 20px;
        border-radius: 12px;
        margin-bottom: 20px;
        color: #991b1b;
        font-weight: 500;
        animation: slideIn 0.3s ease-out;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    @keyframes slideIn {
        from { opacity: 0; transform: translateX(-20px); }
        to { opacity: 1; transform: translateX(0); }
    }
    .waves-table-container {
        background: white;
        border-radius: 20px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        overflow: hidden;
    }
    .waves-table {
        width: 100%;
        border-collapse: collapse;
    }
    .waves-table thead {
        background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 100%);
    }
    .waves-table th {
        padding: 18px 16px;
        text-align: left;
        font-weight: 700;
        color: #475569;
        font-size: 13px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 2px solid #e2e8f0;
    }
    .waves-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: all 0.2s;
    }
    .waves-table tbody tr:hover {
        background: linear-gradient(135deg, #faf5ff 0%, #f3f4f6 100%);
        transform: scale(1.01);
        box-shadow: 0 2px 8px rgba(102, 126, 234, 0.08);
    }
    .waves-table td {
        padding: 18px 16px;
        color: #334155;
        font-size: 14px;
    }
    .wave-name {
        font-weight: 600;
        color: #1e293b;
        font-size: 15px;
    }
    .badge {
        padding: 6px 14px;
        border-radius: 20px;
        font-size: 12px;
        font-weight: 600;
        display: inline-block;
        margin-right: 6px;
    }
    .badge-year {
        background: linear-gradient(135deg, #dbeafe 0%, #bfdbfe 100%);
        color: #1e40af;
    }
    .badge-term {
        background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
        color: #075985;
    }
    .badge-active {
        background: linear-gradient(135deg, #dcfce7 0%, #bbf7d0 100%);
        color: #166534;
        animation: pulse 2s infinite;
    }
    .badge-upcoming {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        color: #92400e;
    }
    .badge-ended {
        background: linear-gradient(135deg, #f3f4f6 0%, #e5e7eb 100%);
        color: #374151;
    }
    @keyframes pulse {
        0%, 100% { opacity: 1; }
        50% { opacity: 0.8; }
    }
    .action-btns {
        display: flex;
        gap: 8px;
    }
    .action-btn {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: all 0.2s;
        text-decoration: none;
        border: none;
        cursor: pointer;
    }
    .action-btn:hover {
        transform: translateY(-3px) scale(1.1);
    }
    .btn-view {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
    }
    .btn-view:hover {
        box-shadow: 0 6px 15px rgba(16, 185, 129, 0.4);
    }
    .btn-edit {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
    }
    .btn-edit:hover {
        box-shadow: 0 6px 15px rgba(59, 130, 246, 0.4);
    }
    .btn-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
    }
    .btn-delete:hover {
        box-shadow: 0 6px 15px rgba(239, 68, 68, 0.4);
    }
    .empty-state {
        padding: 80px 20px;
        text-align: center;
        color: #94a3b8;
    }
    .empty-state i {
        font-size: 64px;
        margin-bottom: 20px;
        opacity: 0.3;
    }
    @media (max-width: 768px) {
        .waves-header {
            flex-direction: column;
            gap: 16px;
            text-align: center;
        }
    }
</style>

<div class="waves-container">
    <div class="waves-header">
        <h2><i class="fas fa-calendar-week"></i> Quản lý Đợt đăng ký</h2>
        <a href="{{ route('registration-waves.create') }}" class="btn-add">
            <i class="fas fa-plus-circle"></i>
            Thêm Đợt mới
        </a>
    </div>

    @if(session('success'))
    <div class="alert-success">
        <i class="fas fa-check-circle" style="font-size: 20px;"></i>
        <span>{{ session('success') }}</span>
    </div>
    @endif
    
    @if(session('error'))
    <div class="alert-error">
        <i class="fas fa-exclamation-circle" style="font-size: 20px;"></i>
        <span>{{ session('error') }}</span>
    </div>
    @endif

    <div class="waves-table-container">
        <table class="table-zebra" style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f8fafc; border-bottom:2px solid #e2e8f0;">
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">TÊN ĐỢT</th>
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">NĂM HỌC/HỌC KỲ</th>
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">THỜI GIAN</th>
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">ĐỐI TƯỢNG</th>
                    <th style="padding:12px; text-align:center; font-weight:600; color:#475569; font-size:13px;">TRẠNG THÁI</th>
                    <th style="padding:12px; text-align:center; font-weight:600; color:#475569; font-size:13px;">THAO TÁC</th>
                </tr>
            </thead>
            <tbody>
                @forelse($waves as $wave)
                @php
                $audience = json_decode($wave->audience, true);
                $facultyCodes = collect($audience['faculties'] ?? [])->map(function($id) use ($faculties) {
                    return optional($faculties->firstWhere('id', $id))->code;
                })->filter()->values()->all();
                $cohorts = $audience['cohorts'] ?? [];
                $now = now();
                $starts = \Carbon\Carbon::parse($wave->starts_at);
                $ends = \Carbon\Carbon::parse($wave->ends_at);
                @endphp
                <tr style="border-bottom:1px solid #e2e8f0;">
                    <td style="padding:12px; font-weight:500; color:#1e293b;">{{ $wave->name }}</td>
                    <td style="padding:12px;">
                        <span style="background:#dbeafe; color:#1e40af; padding:4px 12px; border-radius:12px; font-size:12px; font-weight:600;">{{ $wave->academic_year }}</span>
                        <span style="background:#e0f2fe; color:#075985; padding:4px 12px; border-radius:12px; font-size:12px; font-weight:600;">{{ $wave->term }}</span>
                    </td>
                    <td style="padding:12px; color:#334155;">
                        <div>{{ $starts->format('d/m/Y H:i') }}</div>
                        <div style="font-size:12px; color:#64748b;">đến {{ $ends->format('d/m/Y H:i') }}</div>
                    </td>
                    <td style="padding:12px; color:#334155; font-size:13px;">
                        <div>Khoa: {{ count($facultyCodes) ? implode(', ', $facultyCodes) : 'Tất cả' }}</div>
                        <div>Khóa: {{ count($cohorts) ? implode(', ', $cohorts) : 'Tất cả' }}</div>
                    </td>
                    <td style="padding:12px; text-align:center;">
                        @if($now < $starts)
                        <span style="background:#fef3c7; color:#92400e; padding:4px 12px; border-radius:12px; font-size:12px; font-weight:600;">Sắp diễn ra</span>
                        @elseif($now >= $starts && $now <= $ends)
                        <span style="background:#dcfce7; color:#166534; padding:4px 12px; border-radius:12px; font-size:12px; font-weight:600;">Đang mở</span>
                        @else
                        <span style="background:#e5e7eb; color:#374151; padding:4px 12px; border-radius:12px; font-size:12px; font-weight:600;">Đã kết thúc</span>
                        @endif
                    </td>
                    <td style="padding:12px; text-align:center;">
                        <div style="display:inline-flex; gap:6px;">
                            <a href="{{ route('registration-waves.show', $wave) }}" class="action-btn" style="background:#10b981; color:white; text-decoration:none;" title="Xem chi tiết">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8zM1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0z"/></svg>
                            </a>
                            <a href="{{ route('registration-waves.edit', $wave) }}" class="action-btn" style="background:#1976d2; color:white; text-decoration:none;" title="Sửa">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3zm.646 6.061L9.793 2.5 3.293 9H3.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.5h.5a.5.5 0 0 1 .5.5v.207l6.5-6.5zm-7.468 7.468A.5.5 0 0 1 6 13.5V13h-.5a.5.5 0 0 1-.5-.5V12h-.5a.5.5 0 0 1-.5-.5V11h-.5a.5.5 0 0 1-.5-.5V10h-.5a.499.499 0 0 1-.175-.032l-.179.178a.5.5 0 0 0-.11.168l-2 5a.5.5 0 0 0 .65.65l5-2a.5.5 0 0 0 .168-.11l.178-.178z"/></svg>
                            </a>
                            <form action="{{ route('registration-waves.destroy', $wave) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn xóa đợt đăng ký này?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="action-btn" style="background:#dc2626; color:white; border:none; cursor:pointer;" title="Xóa">
                                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zM8 5.5a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z" /><path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1z" /></svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:60px 20px; text-align:center; color:#94a3b8;">
                        <div style="font-size:16px; font-weight:500;">Không có đợt đăng ký nào</div>
                        <div style="font-size:14px; margin-top:4px;">Nhấn “Thêm Đợt đăng ký” để tạo mới</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($waves->hasPages())
    <div style="margin-top:24px; display:flex; justify-content:center;">{{ $waves->links() }}</div>
    @endif
</div>
@endsection
