@extends('student.layout')

@section('title','Thời khóa biểu')

@section('content')
<div class="card">
    <div style="display:flex;justify-content:space-between;align-items:center;">
        <div>
            <h3 style="margin:0;">Thời khóa biểu hiện tại</h3>
            <div class="muted">Năm học {{ $year }} - {{ $term }}</div>
        </div>
        <div>
            <a class="btn" href="{{ route('student.exportIcs') }}">Tải về ICS</a>
        </div>
    </div>
</div>

<div class="card">
    <table>
        <thead>
            <tr>
                <th>Thứ</th>
                <th>Mã lớp</th>
                <th>Môn học</th>
                <th>Tiết</th>
                <th>Phòng</th>
                <th>GV</th>
            </tr>
        </thead>
        <tbody>
            @forelse($regs as $r)
            @php($sec = $r->classSection)
            <tr>
                <td>{{ $sec->shift->day_name ?? 'Thứ '.$sec->day_of_week }}</td>
                <td><code>{{ $sec->section_code }}</code></td>
                <td>{{ $sec->course->code }} - {{ $sec->course->name }}</td>
                <td>{{ $sec->shift->start_period }}-{{ $sec->shift->end_period }}</td>
                <td>{{ $sec->room->code }}</td>
                <td>{{ $sec->lecturer_name }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="muted">Chưa có lớp trong thời khóa biểu.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
    <div class="muted" style="margin-top:8px;">Tổng số lớp: {{ $regs->count() }}</div>
</div>
@endsection