@extends('student.layout')

@section('title','Tra cứu học phần')

@section('content')
<div class="card">
    @if($wave && $openForUser)
    <div class="status success" style="margin:0 0 8px 0;">Đợt đăng ký đang mở</div>
    @else
    <div class="status error" style="margin:0 0 8px 0;">Hiện không trong thời gian đăng ký của bạn</div>
    @endif
    @if($wave)
    <div class="muted">Hạn đăng ký: Mã từ {{ \Carbon\Carbon::parse($wave->starts_at)->format('d/m/Y') }} / Hạn đăng ký {{ \Carbon\Carbon::parse($wave->ends_at)->format('d/m/Y') }}, Hạn chót: {{ \Carbon\Carbon::parse($wave->ends_at)->format('d/m/Y H:i') }}</div>
    @endif
</div>

<div class="card">
    <h3 style="margin:0 0 12px 0;color:#6B4B9D;font-size:16px;">Đợt đăng ký đang mở</h3>
    <form method="GET" action="{{ route('student.offerings') }}" style="display:grid;gap:8px;grid-template-columns:1fr 1fr 1fr auto;align-items:end;margin-bottom:16px;">
        <div>
            <label class="muted" style="display:block;margin-bottom:4px;">Tìm kiếm</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Mã môn, tên môn, mã lớp" style="width:100%;padding:8px;border-radius:4px;border:1px solid #ddd;">
        </div>
        <div>
            <label class="muted" style="display:block;margin-bottom:4px;">Năm học</label>
            <input type="text" name="academic_year" value="{{ $year }}" style="width:100%;padding:8px;border-radius:4px;border:1px solid #ddd;" placeholder="2024-2025">
        </div>
        <div>
            <label class="muted" style="display:block;margin-bottom:4px;">Học kỳ</label>
            <select name="term" style="width:100%;padding:8px;border-radius:4px;border:1px solid #ddd;">
                <option value="HK1" {{ $term=='HK1'?'selected':'' }}>HK1</option>
                <option value="HK2" {{ $term=='HK2'?'selected':'' }}>HK2</option>
                <option value="HK3" {{ $term=='HK3'?'selected':'' }}>HK3</option>
            </select>
        </div>
        <div>
            <button class="btn" type="submit">Lọc</button>
        </div>
    </form>

    <div class="grid" style="grid-template-columns: 2fr 1fr; gap:16px;margin-top:16px;">
        <div>
            <h4 style="margin:0 0 12px 0;font-size:14px;color:#6B4B9D;">Danh mục học phần mở</h4>
            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Mã</th>
                            <th>Tên học phần</th>
                            <th>TC</th>
                            <th>Lớp</th>
                            <th>GV/Phòng</th>
                            <th>Lịch học</th>
                            <th>Sĩ số</th>
                            <th>Đăng ký</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($sections as $s)
                        @php
                        $enrolled = $s->registrations()->count();
                        $full = $enrolled >= $s->max_capacity;
                        $registered = in_array($s->id, $registeredSectionIds);
                        @endphp
                        <tr>
                            <td><strong>{{ $s->course->code }}</strong></td>
                            <td>{{ $s->course->name }}</td>
                            <td>{{ $s->course->credits }}</td>
                            <td><span class="badge info">{{ $s->section_code }}</span></td>
                            <td>
                                <div>{{ $s->lecturer_name ?? 'GV chưa có' }}</div>
                                <div class="muted">{{ $s->room->code }}</div>
                            </td>
                            <td>
                                <div>{{ $s->shift->day_name ?? ('Thứ '.$s->day_of_week) }}</div>
                                <div class="muted">T{{ $s->shift->start_period }}-{{ $s->shift->end_period }}</div>
                            </td>
                            <td>
                                <span class="badge {{ $full ? 'danger':'ok' }}">{{ $enrolled }}/{{ $s->max_capacity }}</span>
                            </td>
                            <td>
                                @if($registered)
                                <span class="badge ok">Đã đăng ký</span>
                                @elseif($full)
                                <span class="badge danger">Hết chỗ</span>
                                @elseif(!$openForUser)
                                <span class="badge warn">Chưa mở</span>
                                @else
                                <form action="{{ route('student.register', $s) }}" method="POST" style="display:inline;">
                                    @csrf
                                    <button class="btn" type="submit">Đăng ký</button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="muted" style="text-align:center;">Không tìm thấy lớp học phần.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div style="margin-top:12px;">{{ $sections->links() }}</div>
        </div>

        <div>
            <h4 style="margin:0 0 12px 0;font-size:14px;color:#6B4B9D;">Học phần đã đăng ký</h4>
            <div style="overflow-x:auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Môn học</th>
                            <th>Lịch</th>
                            <th>TC</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($currentRegs as $r)
                        @php($cs = $r->classSection)
                        <tr>
                            <td>
                                <div><strong>{{ $cs->course->code }}</strong></div>
                                <div class="muted">{{ $cs->course->name }}</div>
                            </td>
                            <td>
                                <div>{{ $cs->shift->day_name ?? ('Thứ '.$cs->day_of_week) }}</div>
                                <div class="muted">T{{ $cs->shift->start_period }}-{{ $cs->shift->end_period }}</div>
                            </td>
                            <td>{{ $cs->course->credits }}</td>
                            <td>
                                <form action="{{ route('student.cancel', $r) }}" method="POST" onsubmit="return confirm('Hủy lớp này?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn danger" type="submit">Hủy</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="muted" style="text-align:center;">Chưa có đăng ký.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection