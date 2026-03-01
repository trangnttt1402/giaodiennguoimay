@extends('admin.layout')

@section('title', 'Quản lý Giảng viên')

@section('content')
<div style="background:white; padding:24px; border-radius:8px; box-shadow:0 1px 3px rgba(0,0,0,0.1);">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:24px;">
        <h2 style="margin:0; font-size:20px; font-weight:600; color:#1e293b;">Danh sách Giảng viên</h2>
        <a href="{{ route('lecturers.create') }}" style="background:#1976d2; color:white; padding:10px 20px; border-radius:6px; text-decoration:none; font-weight:500;">
            + Thêm Giảng viên
        </a>
    </div>

    <!-- Search & Filter -->
    <form method="GET" style="display:flex; gap:12px; margin-bottom:20px;">
        <input
            type="text"
            name="search"
            value="{{ request('search') }}"
            placeholder="Tìm theo mã, tên, email..."
            style="flex:1; padding:10px; border:1px solid #cbd5e0; border-radius:6px;">
        <select name="faculty_id" style="padding:10px; border:1px solid #cbd5e0; border-radius:6px; min-width:200px;">
            <option value="">-- Tất cả Khoa --</option>
            @foreach($faculties as $faculty)
            <option value="{{ $faculty->id }}" {{ request('faculty_id') == $faculty->id ? 'selected' : '' }}>
                {{ $faculty->name }}
            </option>
            @endforeach
        </select>
        <button type="submit" style="background:#1976d2; color:white; padding:10px 20px; border:none; border-radius:6px; cursor:pointer;">
            Tìm kiếm
        </button>
        @if(request('search') || request('faculty_id'))
        <a href="{{ route('lecturers.index') }}" style="padding:10px 20px; border:1px solid #cbd5e0; border-radius:6px; text-decoration:none; color:#475569;">
            Xóa bộ lọc
        </a>
        @endif
    </form>

    @if($lecturers->isEmpty())
    <p style="text-align:center; color:#64748b; padding:40px;">Không có giảng viên nào.</p>
    @else
    <table style="width:100%; border-collapse:collapse;">
        <thead>
            <tr style="background:#f8fafc; border-bottom:2px solid #e2e8f0;">
                <th style="padding:12px; text-align:left; font-weight:600; color:#475569;">Mã GV</th>
                <th style="padding:12px; text-align:left; font-weight:600; color:#475569;">Họ tên</th>
                <th style="padding:12px; text-align:left; font-weight:600; color:#475569;">Email</th>
                <th style="padding:12px; text-align:left; font-weight:600; color:#475569;">Khoa</th>
                <th style="padding:12px; text-align:left; font-weight:600; color:#475569;">Học vị</th>
                <th style="padding:12px; text-align:left; font-weight:600; color:#475569;">SĐT</th>
                <th style="padding:12px; text-align:center; font-weight:600; color:#475569;">Thao tác</th>
            </tr>
        </thead>
        <tbody>
            @foreach($lecturers as $lecturer)
            <tr style="border-bottom:1px solid #e2e8f0;">
                <td style="padding:12px;">{{ $lecturer->code }}</td>
                <td style="padding:12px; font-weight:500;">{{ $lecturer->name }}</td>
                <td style="padding:12px; color:#64748b;">{{ $lecturer->email }}</td>
                <td style="padding:12px;">{{ $lecturer->faculty->name ?? '-' }}</td>
                <td style="padding:12px;">{{ $lecturer->degree ?? '-' }}</td>
                <td style="padding:12px;">{{ $lecturer->phone ?? '-' }}</td>
                <td style="padding:12px; text-align:center;">
                    <div style="display:flex; gap:8px; justify-content:center;">
                        <a href="{{ route('lecturers.edit', $lecturer) }}" style="color:#1976d2; text-decoration:none; font-size:14px;">
                            Sửa
                        </a>
                        <form action="{{ route('lecturers.destroy', $lecturer) }}" method="POST" style="display:inline;" onsubmit="return confirm('Xác nhận xóa giảng viên này?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" style="color:#dc2626; background:none; border:none; cursor:pointer; font-size:14px; text-decoration:underline;">
                                Xóa
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top:20px;">
        {{ $lecturers->links() }}
    </div>
    @endif
</div>
@endsection
