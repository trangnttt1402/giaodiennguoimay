@extends('admin.layout')

@section('title', 'Quản lý Ca học')

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
        <h2 style="margin:0; font-size:20px; font-weight:600; color:#1e293b;">📅 Quản lý Ca học</h2>
        <a href="{{ route('shifts.create') }}" style="background:#16a34a; color:white; padding:10px 20px; border-radius:6px; text-decoration:none; font-weight:500; display:inline-flex; align-items:center; gap:8px;">Thêm Ca học</a>
    </div>

    @if(session('success'))
    <div style="background:#d1fae5; border-left:4px solid #10b981; padding:12px 16px; border-radius:6px; margin-bottom:16px; color:#065f46;">✓ {{ session('success') }}</div>
    @endif
    @if(session('error'))
    <div style="background:#fee2e2; border-left:4px solid #ef4444; padding:12px 16px; border-radius:6px; margin-bottom:16px; color:#991b1b;">✗ {{ session('error') }}</div>
    @endif

    <form method="GET" action="{{ route('shifts.index') }}" style="display:flex; gap:12px; margin-bottom:20px; align-items:end;">
        <div style="flex:2;">
            <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Tìm kiếm</label>
            <input type="text" name="q" value="{{ $filters['q'] ?? '' }}" placeholder="Mã ca / Tên ca" style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
        </div>
        <div style="flex:1;">
            <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Thứ</label>
            <select name="day" style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                <option value="">-- Tất cả --</option>
                @for($i=1;$i<=7;$i++)
                <option value="{{ $i }}" {{ ($filters['day'] ?? '') == $i ? 'selected' : '' }}>{{ [1=>'Thứ 2',2=>'Thứ 3',3=>'Thứ 4',4=>'Thứ 5',5=>'Thứ 6',6=>'Thứ 7',7=>'CN'][$i] }}</option>
                @endfor
            </select>
        </div>
        <div style="flex:1;">
            <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Khung</label>
            <select name="frame" style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                <option value="">-- Tất cả --</option>
                <option value="morning" {{ ($filters['frame'] ?? '')=='morning' ? 'selected' : '' }}>Sáng</option>
                <option value="afternoon" {{ ($filters['frame'] ?? '')=='afternoon' ? 'selected' : '' }}>Chiều</option>
                <option value="evening" {{ ($filters['frame'] ?? '')=='evening' ? 'selected' : '' }}>Tối</option>
            </select>
        </div>
        <div style="flex:1;">
            <label style="display:block; margin-bottom:6px; font-weight:500; color:#475569; font-size:14px;">Trạng thái</label>
            <select name="status" style="width:100%; padding:10px; border:1px solid #cbd5e0; border-radius:6px; font-size:14px;">
                <option value="">-- Tất cả --</option>
                <option value="active" {{ ($filters['status'] ?? '')=='active' ? 'selected' : '' }}>Hoạt động</option>
                <option value="inactive" {{ ($filters['status'] ?? '')=='inactive' ? 'selected' : '' }}>Tạm ngưng</option>
            </select>
        </div>
        <button type="submit" style="background:#1976d2; color:white; padding:10px 24px; border:none; border-radius:6px; cursor:pointer; font-weight:500;">Lọc</button>
    </form>

    <div style="overflow-x:auto;">
        <table class="table-zebra" style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f8fafc; border-bottom:2px solid #e2e8f0;">
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">MÃ CA</th>
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">TÊN CA</th>
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">THỨ</th>
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">THỜI GIAN</th>
                    <th style="padding:12px; text-align:left; font-weight:600; color:#475569; font-size:13px;">KHUNG</th>
                    <th style="padding:12px; text-align:center; font-weight:600; color:#475569; font-size:13px;">TRẠNG THÁI</th>
                    <th style="padding:12px; text-align:center; font-weight:600; color:#475569; font-size:13px;">THAO TÁC</th>
                </tr>
            </thead>
            <tbody>
                @forelse($shifts as $shift)
                @php($times = explode(' - ', $shift->time_range))
                <tr style="border-bottom:1px solid #e2e8f0;">
                    <td style="padding:12px;"><code style="background:#eef2ff; color:#3730a3; padding:4px 8px; border-radius:4px; font-size:13px; font-weight:600;">{{ $shift->code ?? ('C'.$shift->day_of_week.$shift->start_period.'-'.$shift->end_period) }}</code></td>
                    <td style="padding:12px; font-weight:500; color:#1e293b;">{{ $shift->name ?? ('Ca tiết '.$shift->start_period.'-'.$shift->end_period) }}</td>
                    <td style="padding:12px;"><span style="background:#dbeafe; color:#1e40af; padding:4px 12px; border-radius:12px; font-size:12px; font-weight:500;">{{ $shift->day_name }}</span></td>
                    <td style="padding:12px;">{{ ($times[0] ?? '') . ' - ' . ($times[1] ?? '') }}</td>
                    <td style="padding:12px;">{{ $shift->frame }}</td>
                    <td style="padding:12px; text-align:center;">
                        @if(($shift->status ?? 'active') === 'active')
                        <span style="background:#dcfce7; color:#166534; padding:4px 12px; border-radius:12px; font-size:12px; font-weight:600;">Hoạt động</span>
                        @else
                        <span style="background:#e5e7eb; color:#374151; padding:4px 12px; border-radius:12px; font-size:12px; font-weight:600;">Tạm ngưng</span>
                        @endif
                    </td>
                    <td style="padding:12px; text-align:center;">
                        <div style="display:inline-flex; gap:6px;">
                            <button onclick="openDetailFromEl(this)" class="action-btn" data-code="{{ $shift->code }}" data-name="{{ $shift->name }}" data-day="{{ $shift->day_name }}" data-time="{{ ($times[0] ?? '') . ' - ' . ($times[1] ?? '') }}" data-periods="{{ $shift->start_period }}-{{ $shift->end_period }}" data-frame="{{ $shift->frame }}" data-status="{{ $shift->status_label }}" style="background:#10b981; color:white; border:none; cursor:pointer;" title="Xem chi tiết">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8z" /><path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5z" /></svg>
                            </button>
                            <a href="{{ route('shifts.edit', $shift) }}" class="action-btn" style="background:#1976d2; color:white; text-decoration:none;" title="Sửa">
                                <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M12.854.146a.5.5 0 0 0-.707 0L10.5 1.793 14.207 5.5l1.647-1.646a.5.5 0 0 0 0-.708l-3-3z" /><path d="M.146 14.146a.5.5 0 0 0 .168.11l5 2a.5.5 0 0 0 .65-.65l-2-5a.5.5 0 0 0-.11-.168L.146 14.146z" /></svg>
                            </a>
                            <form action="{{ route('shifts.destroy', $shift) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn có chắc muốn xóa ca học này?');">
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
                    <td colspan="7" style="padding:60px 20px; text-align:center; color:#94a3b8;">
                        <div style="font-size:16px; font-weight:500;">Không tìm thấy ca học nào</div>
                        <div style="font-size:14px; margin-top:4px;">Thử thay đổi bộ lọc hoặc thêm ca học mới</div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($shifts->hasPages())
    <div style="margin-top:24px; display:flex; justify-content:center;">{{ $shifts->links() }}</div>
    @endif
</div>

<div id="detailModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.5); z-index:9999; align-items:center; justify-content:center;">
    <div style="background:white; border-radius:12px; width:90%; max-width:560px; max-height:85vh; overflow:auto; box-shadow:0 20px 25px -5px rgba(0,0,0,0.1);">
        <div style="padding:20px; border-bottom:2px solid #10b981; background:linear-gradient(135deg,#10b981 0%, #059669 100%); color:white; display:flex; justify-content:space-between; align-items:center;">
            <h5 style="margin:0; font-size:18px;">Chi tiết Ca học</h5>
            <button onclick="closeDetail()" style="background:transparent; border:none; color:white; font-size:20px; cursor:pointer;">×</button>
        </div>
        <div id="detailBody" style="padding:20px;"></div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function openDetailFromEl(el) {
        const data = {
            code: el.dataset.code || '—', name: el.dataset.name || '—', day: el.dataset.day || '—',
            time: el.dataset.time || '—', periods: el.dataset.periods || '—', frame: el.dataset.frame || '—',
            status: el.dataset.status || '—'
        };
        document.getElementById('detailBody').innerHTML = `
            <div style="display:grid; grid-template-columns:1fr 1fr; gap:12px;">
                <div><label style="font-size:12px; color:#64748b;">Mã ca</label><div style="margin-top:4px; font-weight:600;">${data.code}</div></div>
                <div><label style="font-size:12px; color:#64748b;">Tên ca</label><div style="margin-top:4px; font-weight:600;">${data.name}</div></div>
                <div><label style="font-size:12px; color:#64748b;">Thứ</label><div style="margin-top:4px;">${data.day}</div></div>
                <div><label style="font-size:12px; color:#64748b;">Thời gian</label><div style="margin-top:4px;">${data.time}</div></div>
                <div><label style="font-size:12px; color:#64748b;">Khoảng tiết</label><div style="margin-top:4px;">${data.periods}</div></div>
                <div><label style="font-size:12px; color:#64748b;">Khung</label><div style="margin-top:4px;">${data.frame}</div></div>
                <div style="grid-column:1 / -1;"><label style="font-size:12px; color:#64748b;">Trạng thái</label><div style="margin-top:4px;">${data.status}</div></div>
            </div>`;
        document.getElementById('detailModal').style.display = 'flex';
    }
    function closeDetail() { document.getElementById('detailModal').style.display = 'none'; }
    document.getElementById('detailModal').addEventListener('click', function(e) { if (e.target === this) closeDetail(); });
</script>
@endsection
