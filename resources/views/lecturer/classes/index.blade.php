@extends('lecturer.layout')

@section('title', 'Lớp giảng dạy')

@section('content')
<div class="row mb-4">
    <div class="col-md-6">
        <h3 class="mb-0">
            <i class="fas fa-users-class me-2" style="color: #1976d2;"></i>
            Danh sách Lớp giảng dạy
        </h3>
        <small class="text-muted">Năm học: {{ $academicYear }} - {{ $term === 'HK1' ? 'Học kỳ 1' : ($term === 'HK2' ? 'Học kỳ 2' : 'Học kỳ Hè') }}</small>
    </div>
    <div class="col-md-6 text-end">
        <div class="badge bg-primary fs-6">
            <i class="fas fa-chalkboard me-1"></i>
            {{ $classSections->count() }} lớp
        </div>
    </div>
</div>

@if($classSections->isEmpty())
<div class="card">
    <div class="card-body text-center py-5">
        <i class="fas fa-chalkboard fa-4x text-muted mb-3"></i>
        <h5 class="text-muted">Chưa có lớp học phần được phân công</h5>
        <p class="text-muted">Liên hệ với bộ phận đào tạo để biết thêm thông tin</p>
    </div>
</div>
@else
<div class="row">
    @foreach($classSections as $section)
    <div class="col-md-6 mb-4">
        <div class="card h-100 class-card" onclick="window.location='{{ route('lecturer.classes.detail', $section->id) }}'" style="cursor: pointer;">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $section->course->code }}</h5>
                    <span class="badge bg-light text-dark">{{ $section->section_code }}</span>
                </div>
            </div>
            <div class="card-body">
                <h6 class="text-primary mb-3">{{ $section->course->name }}</h6>

                <div class="row g-2">
                    <div class="col-6">
                        <small class="text-muted d-block">
                            <i class="fas fa-calendar-day me-1"></i>Thứ {{ $section->day_of_week }}
                        </small>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">
                            <i class="fas fa-clock me-1"></i>
                            @if($section->shift)
                            Tiết {{ $section->shift->start_period }}-{{ $section->shift->end_period }}
                            @else
                            TBA
                            @endif
                        </small>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">
                            <i class="fas fa-door-open me-1"></i>
                            {{ $section->room ? $section->room->code : 'Chưa xếp phòng' }}
                        </small>
                    </div>
                    <div class="col-6">
                        <small class="text-muted d-block">
                            <i class="fas fa-users me-1"></i>
                            Sĩ số: {{ $section->current_enrollment }}/{{ $section->max_capacity }}
                        </small>
                    </div>
                </div>

                @php
                $percentage = $section->max_capacity > 0 ? ($section->current_enrollment / $section->max_capacity) * 100 : 0;
                $progressClass = $percentage >= 90 ? 'bg-danger' : ($percentage >= 70 ? 'bg-warning' : 'bg-success');
                @endphp

                <div class="progress mt-3" style="height: 6px;">
                    <div class="progress-bar {{ $progressClass }}" role="progressbar" style="width: {{ $percentage }}%"></div>
                </div>
            </div>
            <div class="card-footer bg-transparent">
                <a href="{{ route('lecturer.classes.detail', $section->id) }}" class="btn btn-sm btn-primary w-100">
                    <i class="fas fa-eye me-1"></i>Xem danh sách sinh viên
                </a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endif
@endsection

@section('styles')
<style>
    .class-card {
        transition: all 0.3s;
        border: 1px solid #e0e0e0;
    }

    .class-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(25, 118, 210, 0.15);
        border-color: #1976d2;
    }

    .card-header {
        background: linear-gradient(135deg, #1976d2, #64b5f6);
        color: white;
    }

    .progress-bar {
        transition: width 0.6s ease;
    }
</style>
@endsection