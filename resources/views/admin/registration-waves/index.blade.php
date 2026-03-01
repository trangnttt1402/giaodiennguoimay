@extends('admin.layout')

@section('title', 'Quản lý Đợt đăng ký')

@section('content')
<style>
    .table-zebra tbody tr:nth-child(even) { background-color: rgba(0, 0, 0, 0.02); }
    .table-zebra tbody tr:hover { background-color: rgba(59, 130, 246, 0.1); }
    .action-btn {
        width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center;
        justify-content: center; border-radius: 6px; transition: all 0.2s;
    }
    .action-btn:hover { transform: translateY(-2px); }
</style>

<div style="background:white; padding:24px; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <h2 style="margin:0; font-size:20px; font-weight:600; color:#1e293b;">⏰ Quản lý Đợt đăng ký</h2>
        <a href="{{ route('registration-waves.create') }}" style="background:#6B4B9D; color:white; padding:10px 20px; border-radius:6px; text-decoration:none; font-weight:500; display:inline-flex; align-items:center; gap:8px; transition: all 0.2s;" onmouseover="this.style.background='#543876'" onmouseout="this.style.background='#6B4B9D'">
            <i class="fas fa-plus"></i> Thêm Đợt đăng ký
        </a>
    </div>

    @if(session('success'))
    <div style="background:#d1fae5; border-left:4px solid #10b981; padding:12px 16px; border-radius:6px; margin-bottom:16px; color:#065f46;">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div style="background:#fee2e2; border-left:4px solid #ef4444; padding:12px 16px; border-radius:6px; margin-bottom:16px; color:#991b1b;">✗ {{ session('error') }}</div>
    @endif

    <div style="overflow-x:auto;">
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
                            <a href="{{ route('registration-waves.edit', $wave) }}" class="action-btn" style="background:#1976d2; color:white; text-decoration:none;" title="Sửa">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3z" /><path d="M.146 14.146a.5.5 0 0 0 .168.11l5 2a.5.5 0 0 0 .65-.65l-2-5a.5.5 0 0 0-.11-.168L.146 14.146z" /></svg>
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
