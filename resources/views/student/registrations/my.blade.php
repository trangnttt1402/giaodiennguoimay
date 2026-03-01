@extends('student.layout')

@section('title','Học phần đã đăng ký')

@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h3 style="margin:0;">Đã đăng ký</h3>
            <div class="muted">Năm học {{ $year }} - {{ $term }}</div>
        </div>
        <div>
            <span class="badge ok">Tổng tín chỉ: {{ $credits }}</span>
        </div>
    </div>
</div>
<div class="card">
    <table>
        <thead>
            <tr>
                <th>Mã lớp</th>
                <th>Môn học</th>
                <th>Lịch học</th>
                <th>Phòng</th>
                <th class="text-end">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @forelse($regs as $r)
            <tr>
                <td><code>{{ $r->classSection->section_code }}</code></td>
                <td>{{ $r->classSection->course->code }} - {{ $r->classSection->course->name }} ({{ $r->classSection->course->credits }} TC)</td>
                <td>
                    <span class="badge info">{{ $r->classSection->shift->day_name ?? 'Thứ '.$r->classSection->day_of_week }}</span>
                    <div class="muted">Tiết {{ $r->classSection->shift->start_period }}-{{ $r->classSection->shift->end_period }}</div>
                </td>
                <td><span class="badge info">{{ $r->classSection->room->code }}</span></td>
                <td style="text-align:right;">
                    <form action="{{ route('student.cancel', $r) }}" method="POST" style="display:inline;" onsubmit="return confirm('Bạn chắc muốn hủy lớp này?')">
                        @csrf
                        @method('DELETE')
                        <button class="btn" style="background:#ef4444" type="submit">Hủy</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="muted">Bạn chưa đăng ký lớp nào.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection