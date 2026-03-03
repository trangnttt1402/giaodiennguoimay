@extends('admin.layout')

@section('title', 'Thêm Đợt đăng ký')

@section('content')
<style>
    :root {
        --brand-1: #6B7BD9;
        --brand-2: #6B4B9D;
        --brand-soft: #f3f0ff;
    }
    .wave-container {
        max-width: 1000px;
        margin: 0 auto;
        padding: 20px;
    }
    .wave-card {
        background: white;
        border-radius: 20px;
        box-shadow: 0 10px 30px rgba(107, 75, 157, 0.1);
        overflow: hidden;
        animation: slideUp 0.5s ease-out;
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .wave-header {
        background: linear-gradient(135deg, var(--brand-1) 0%, var(--brand-2) 100%);
        padding: 40px;
        color: white;
        position: relative;
        overflow: hidden;
    }
    .wave-header::before {
        content: '';
        position: absolute;
        top: -50%;
        right: -10%;
        width: 300px;
        height: 300px;
        background: rgba(255,255,255,0.1);
        border-radius: 50%;
    }
    .wave-header h1 {
        font-size: 32px;
        font-weight: 700;
        margin: 0 0 8px 0;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    .wave-header p {
        margin: 0;
        opacity: 0.95;
        font-size: 15px;
    }
    .wave-body {
        padding: 40px;
    }
    .input-group {
        margin-bottom: 28px;
        position: relative;
    }
    .input-label {
        display: flex;
        align-items: center;
        gap: 8px;
        font-weight: 600;
        color: #4a5568;
        margin-bottom: 10px;
        font-size: 14px;
    }
    .input-label i {
        color: var(--brand-2);
        font-size: 16px;
    }
    .required-star {
        color: #ef4444;
        margin-left: 4px;
    }
    .input-field {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 15px;
        transition: all 0.3s ease;
        background: #f8fafc;
    }
    .input-field:focus {
        outline: none;
        border-color: var(--brand-2);
        background: white;
        box-shadow: 0 0 0 4px rgba(107, 75, 157, 0.12);
        transform: translateY(-2px);
    }
    .grid-2 {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 24px;
    }
    .checkbox-group {
        background: linear-gradient(135deg, #f8fafc 0%, var(--brand-soft) 100%);
        border: 2px solid #e2e8f0;
        border-radius: 16px;
        padding: 24px;
    }
    .checkbox-item {
        display: flex;
        align-items: center;
        padding: 12px;
        border-radius: 10px;
        transition: all 0.2s;
        cursor: pointer;
        margin-bottom: 8px;
    }
    .checkbox-item:hover {
        background: rgba(107, 75, 157, 0.06);
        transform: translateX(5px);
    }
    .checkbox-item input[type="checkbox"] {
        width: 20px;
        height: 20px;
        margin-right: 12px;
        cursor: pointer;
        accent-color: var(--brand-2);
    }
    .checkbox-item label {
        cursor: pointer;
        margin: 0;
        flex: 1;
        font-size: 14px;
        color: #334155;
    }
    .cohort-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
        gap: 12px;
    }
    .cohort-item {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 14px;
        background: white;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        transition: all 0.2s;
        cursor: pointer;
    }
    .cohort-item:has(input:checked) {
        background: linear-gradient(135deg, var(--brand-1) 0%, var(--brand-2) 100%);
        border-color: var(--brand-2);
        color: white;
        transform: scale(1.05);
    }
    .cohort-item input {
        margin-right: 8px;
        accent-color: white;
    }
    .btn-group {
        display: flex;
        gap: 16px;
        margin-top: 40px;
        padding-top: 30px;
        border-top: 2px solid #f1f5f9;
    }
    .btn-primary {
        flex: 1;
        padding: 16px 32px;
        background: linear-gradient(135deg, var(--brand-1) 0%, var(--brand-2) 100%);
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }
    .btn-primary:hover {
        transform: translateY(-3px);
        box-shadow: 0 10px 25px rgba(107, 75, 157, 0.35);
    }
    .btn-secondary {
        padding: 16px 32px;
        background: white;
        color: #64748b;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        font-size: 16px;
        font-weight: 600;
        text-decoration: none;
        cursor: pointer;
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }
    .btn-secondary:hover {
        background: #f8fafc;
        border-color: #cbd5e1;
        transform: translateY(-2px);
    }
    .error-message {
        color: #ef4444;
        font-size: 13px;
        margin-top: 6px;
        display: flex;
        align-items: center;
        gap: 6px;
    }
    @media (max-width: 768px) {
        .grid-2 { grid-template-columns: 1fr; }
        .wave-header { padding: 30px 20px; }
        .wave-body { padding: 25px 20px; }
    }
</style>

<div class="wave-container">
    <div class="wave-card">
        <div class="wave-header">
            <a href="{{ route('registration-waves.index') }}" style="display: inline-flex; width: 40px; height: 40px; background: rgba(255,255,255,0.2); border-radius: 10px; align-items: center; justify-content: center; color: white; text-decoration: none; margin-bottom: 20px; transition: all 0.2s; backdrop-filter: blur(10px);" onmouseover="this.style.background='rgba(255,255,255,0.3)'" onmouseout="this.style.background='rgba(255,255,255,0.2)'">
                <i class="fas fa-arrow-left"></i>
            </a>
            <h1><i class="fas fa-calendar-plus"></i> Thêm Đợt đăng ký</h1>
            <p>Thiết lập thời gian và đối tượng được phép đăng ký học phần</p>
        </div>

        <form action="{{ route('registration-waves.store') }}" method="POST">
            <div class="wave-body">
                @csrf

                <!-- Năm học & Học kỳ -->
                <div class="grid-2">
                    <div class="input-group">
                        <label class="input-label">
                            <i class="fas fa-calendar-alt"></i>
                            Năm học
                            <span class="required-star">*</span>
                        </label>
                        <input type="text" name="academic_year" class="input-field @error('academic_year') is-invalid @enderror" value="{{ old('academic_year', '2024-2025') }}" placeholder="VD: 2024-2025" required>
                        @error('academic_year')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>{{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label">
                            <i class="fas fa-graduation-cap"></i>
                            Học kỳ
                            <span class="required-star">*</span>
                        </label>
                        <select name="term" class="input-field @error('term') is-invalid @enderror" required>
                            <option value="">-- Chọn học kỳ --</option>
                            <option value="HK1" {{ old('term') == 'HK1' ? 'selected' : '' }}>🌸 Học kỳ 1</option>
                            <option value="HK2" {{ old('term') == 'HK2' ? 'selected' : '' }}>🍂 Học kỳ 2</option>
                            <option value="HK3" {{ old('term') == 'HK3' ? 'selected' : '' }}>☀️ Học kỳ 3 (Hè)</option>
                        </select>
                        @error('term')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>{{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                <!-- Tên đợt -->
                <div class="input-group">
                    <label class="input-label">
                        <i class="fas fa-tag"></i>
                        Tên đợt
                        <span class="required-star">*</span>
                    </label>
                    <input type="text" name="name" class="input-field @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="VD: Đợt 1 - Ưu tiên" required>
                    @error('name')
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>{{ $message }}
                    </div>
                    @enderror
                </div>

                <!-- Thời gian -->
                <div class="grid-2">
                    <div class="input-group">
                        <label class="input-label">
                            <i class="fas fa-hourglass-start"></i>
                            Thời gian bắt đầu
                            <span class="required-star">*</span>
                        </label>
                        <input type="datetime-local" name="starts_at" class="input-field @error('starts_at') is-invalid @enderror" value="{{ old('starts_at') }}" required>
                        @error('starts_at')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>{{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label">
                            <i class="fas fa-hourglass-end"></i>
                            Thời gian kết thúc
                            <span class="required-star">*</span>
                        </label>
                        <input type="datetime-local" name="ends_at" class="input-field @error('ends_at') is-invalid @enderror" value="{{ old('ends_at') }}" required>
                        @error('ends_at')
                        <div class="error-message">
                            <i class="fas fa-exclamation-circle"></i>{{ $message }}
                        </div>
                        @enderror
                    </div>
                </div>

                <!-- Khoa được phép đăng ký -->
                <div class="input-group">
                    <label class="input-label">
                        <i class="fas fa-university"></i>
                        Khoa được phép đăng ký
                    </label>
                    <div class="checkbox-group">
                        @foreach($faculties as $faculty)
                        <div class="checkbox-item">
                            <input type="checkbox" name="faculties[]" value="{{ $faculty->id }}" id="faculty{{ $faculty->id }}" {{ in_array($faculty->id, old('faculties', [])) ? 'checked' : '' }}>
                            <label for="faculty{{ $faculty->id }}">
                                <i class="fas fa-building" style="color: #6B4B9D; margin-right: 8px;"></i>
                                {{ $faculty->name }}
                            </label>
                        </div>
                        @endforeach
                        <div style="margin-top: 12px; padding: 12px; background: rgba(107, 75, 157, 0.10); border-radius: 8px; font-size: 13px; color: #6B4B9D;">
                            <i class="fas fa-info-circle"></i> Để trống = Tất cả các khoa
                        </div>
                    </div>
                </div>

                <!-- Khóa được phép đăng ký -->
                <div class="input-group">
                    <label class="input-label">
                        <i class="fas fa-users"></i>
                        Khóa được phép đăng ký
                    </label>
                    <div class="checkbox-group">
                        <div class="cohort-grid">
                            @php($cohorts = ['K17', 'K18', 'K19', 'K20', 'K21'])
                            @foreach($cohorts as $cohort)
                            <label class="cohort-item">
                                <input type="checkbox" name="cohorts[]" value="{{ $cohort }}" id="cohort{{ $cohort }}" {{ in_array($cohort, old('cohorts', [])) ? 'checked' : '' }}>
                                <span style="font-weight: 600;">{{ $cohort }}</span>
                            </label>
                            @endforeach
                        </div>
                        <div style="margin-top: 16px; padding: 12px; background: rgba(107, 75, 157, 0.10); border-radius: 8px; font-size: 13px; color: #6B4B9D;">
                            <i class="fas fa-info-circle"></i> Để trống = Tất cả các khóa
                        </div>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="btn-group">
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-save"></i>
                        Lưu đợt đăng ký
                    </button>
                    <a href="{{ route('registration-waves.index') }}" class="btn-secondary">
                        <i class="fas fa-times"></i>
                        Hủy bỏ
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
