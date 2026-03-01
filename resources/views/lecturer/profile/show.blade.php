@extends('lecturer.layout')

@section('title', 'Hồ sơ cá nhân')

@section('content')
<h3 class="mb-4">
    <i class="fas fa-user-edit me-2" style="color: #1976d2;"></i>
    Hồ sơ cá nhân
</h3>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-info-circle me-2"></i>
                    Thông tin cá nhân
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('lecturer.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- Mã Giảng viên (readonly) -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-id-card me-1"></i>Mã Giảng viên
                        </label>
                        <input type="text" class="form-control bg-light" value="{{ $lecturer->code }}" readonly>
                        <small class="text-muted">Thông tin này không thể thay đổi</small>
                    </div>

                    <!-- Họ tên (readonly) -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-user me-1"></i>Họ và tên
                        </label>
                        <input type="text" class="form-control bg-light" value="{{ $lecturer->name }}" readonly>
                        <small class="text-muted">Thông tin này không thể thay đổi</small>
                    </div>

                    <!-- Khoa (readonly) -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-building me-1"></i>Khoa
                        </label>
                        <input type="text" class="form-control bg-light"
                            value="{{ $lecturer->faculty ? $lecturer->faculty->name : 'Chưa phân công' }}" readonly>
                        <small class="text-muted">Thông tin này không thể thay đổi</small>
                    </div>

                    <!-- Email (editable) -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-envelope me-1"></i>Email <span class="text-danger">*</span>
                        </label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email', $lecturer->email) }}" required>
                        @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Số điện thoại (editable) -->
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-phone me-1"></i>Số điện thoại
                        </label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" name="phone"
                            value="{{ old('phone', $lecturer->phone) }}" placeholder="VD: 0901234567">
                        @error('phone')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Avatar (editable) -->
                    <div class="mb-4">
                        <label class="form-label">
                            <i class="fas fa-image me-1"></i>Ảnh đại diện
                        </label>
                        <input type="file" class="form-control @error('avatar') is-invalid @enderror" name="avatar"
                            accept="image/*">
                        @error('avatar')
                        <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Kích thước tối đa: 2MB. Định dạng: JPG, PNG</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Lưu thay đổi
                        </button>
                        <a href="{{ route('lecturer.dashboard') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-1"></i>Hủy
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Avatar Preview -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-user-circle me-2"></i>
                    Ảnh đại diện
                </h6>
            </div>
            <div class="card-body text-center">
                @if($lecturer->avatar_url)
                <img src="{{ asset('storage/' . $lecturer->avatar_url) }}" alt="Avatar"
                    class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">
                @else
                <div class="mb-3">
                    <i class="fas fa-user-circle fa-8x text-muted"></i>
                </div>
                @endif
                <p class="text-muted mb-0">{{ $lecturer->name }}</p>
                <small class="text-muted">{{ $lecturer->code }}</small>
            </div>
        </div>

        <!-- Quick Links -->
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="fas fa-link me-2"></i>
                    Liên kết nhanh
                </h6>
            </div>
            <div class="card-body">
                <a href="{{ route('lecturer.password.change') }}" class="btn btn-outline-primary btn-sm w-100 mb-2">
                    <i class="fas fa-key me-1"></i>Đổi mật khẩu
                </a>
                <a href="{{ route('lecturer.dashboard') }}" class="btn btn-outline-secondary btn-sm w-100">
                    <i class="fas fa-calendar me-1"></i>Thời khóa biểu
                </a>
            </div>
        </div>
    </div>
</div>
@endsection