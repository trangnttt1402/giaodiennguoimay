@extends('lecturer.layout')

@section('title', 'Thời khóa biểu Giảng dạy')

@section('content')
<div class="row mb-4">
    <div class="col-md-8">
        <h3 class="mb-0">
            <i class="fas fa-calendar-week me-2" style="color: #1976d2;"></i>
            Thời khóa biểu Giảng dạy
        </h3>
        <small class="text-muted">Năm học: {{ $academicYear }} - {{ $term === 'HK1' ? 'Học kỳ 1' : ($term === 'HK2' ? 'Học kỳ 2' : 'Học kỳ Hè') }}</small>
    </div>
    <div class="col-md-4 text-end">
        <div class="badge bg-primary fs-6">
            <i class="fas fa-chalkboard me-1"></i>
            {{ $totalClasses }} lớp giảng dạy
        </div>
    </div>
</div>

@if($totalClasses == 0)
<div class="card">
    <div class="card-body text-center py-5">
        <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
        <h5 class="text-muted">Chưa có lớp học phần được phân công</h5>
        <p class="text-muted">Liên hệ với bộ phận đào tạo để biết thêm thông tin</p>
    </div>
</div>
@else
<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered mb-0">
                <thead class="table-light">
                    <tr>
                        <th style="width: 100px;">Thứ / Ca</th>
                        @foreach($days as $day)
                        <th class="text-center">{{ $day }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @php
                    $maxSlots = 0;
                    foreach ($schedule as $dayClasses) {
                    $maxSlots = max($maxSlots, count($dayClasses));
                    }
                    @endphp

                    @for($slot = 0; $slot < max(1, $maxSlots); $slot++)
                        <tr>
                        <td class="text-center align-middle fw-bold bg-light">
                            Ca {{ $slot + 1 }}
                        </td>
                        @foreach($schedule as $dayIndex => $dayClasses)
                        <td class="p-2" style="vertical-align: top;">
                            @if(isset($dayClasses[$slot]))
                            @php $class = $dayClasses[$slot]; @endphp
                            <div class="class-box p-3" style="background: linear-gradient(135deg, #e3f2fd, #bbdefb); border-left: 4px solid #1976d2; border-radius: 6px; cursor: pointer;"
                                onclick="window.location='{{ route('lecturer.classes.detail', $class['id']) }}'">
                                <div class="fw-bold text-primary mb-1" style="font-size: 14px;">
                                    {{ $class['course_code'] }}
                                </div>
                                <div class="mb-2" style="font-size: 13px;">
                                    {{ $class['course_name'] }}
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="badge bg-success">{{ $class['section_code'] }}</span>
                                    <small class="text-muted">
                                        <i class="fas fa-door-open me-1"></i>{{ $class['room'] }}
                                    </small>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-clock me-1"></i>{{ $class['shift'] }}
                                    </small>
                                    <span class="badge bg-info ms-2">
                                        <i class="fas fa-users me-1"></i>{{ $class['enrollment'] }}
                                    </span>
                                </div>
                            </div>
                            @endif
                        </td>
                        @endforeach
                        </tr>
                        @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="alert alert-info mt-3">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Hướng dẫn:</strong> Nhấp vào ô lớp học để xem danh sách sinh viên và thông tin chi tiết.
</div>
@endif
@endsection

@section('styles')
<style>
    .class-box {
        transition: all 0.3s;
        min-height: 120px;
    }

    .class-box:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(25, 118, 210, 0.2);
    }

    .table td,
    .table th {
        border-color: #e0e0e0;
    }

    .table thead th {
        font-weight: 600;
        color: #424242;
    }
</style>
@endsection