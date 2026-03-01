@extends('admin.layout')

@section('title', 'Thêm Đợt đăng ký')

@section('content')
<div class="form-container">
    <div class="form-card">
        <div class="form-header">
            <div style="display: flex; align-items: center; gap: 12px;">
                <a href="{{ route('registration-waves.index') }}" style="display: inline-flex; align-items: center; justify-content: center; width: 36px; height: 36px; border: 1px solid #ddd; border-radius: 8px; color: #666; text-decoration: none; transition: all 0.2s;" onmouseover="this.style.borderColor='#6B4B9D'; this.style.color='#6B4B9D'" onmouseout="this.style.borderColor='#ddd'; this.style.color='#666'"><i class="fas fa-arrow-left"></i></a>
                <div>
                    <h2 style="margin: 0; font-size: 24px; color: #333;">Thêm Đợt đăng ký</h2>
                    <p style="margin: 4px 0 0 0; color: #888; font-size: 14px;">Thiết lập thời gian mở đăng ký theo đối tượng</p>
                </div>
            </div>
        </div>

        <form action="{{ route('registration-waves.store') }}" method="POST">
            @csrf

            <div class="form-row cols-2">
                <div class="form-group">
                    <label class="form-label">Năm học <span class="text-danger">*</span></label>
                    <input type="text" name="academic_year" class="form-input @error('academic_year') is-invalid @enderror" value="{{ old('academic_year', '2024-2025') }}" placeholder="VD: 2024-2025" required>
                    @error('academic_year')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Học kỳ <span class="text-danger">*</span></label>
                    <select name="term" class="form-select @error('term') is-invalid @enderror" required>
                        <option value="">-- Chọn học kỳ --</option>
                        <option value="HK1" {{ old('term') == 'HK1' ? 'selected' : '' }}>Học kỳ 1</option>
                        <option value="HK2" {{ old('term') == 'HK2' ? 'selected' : '' }}>Học kỳ 2</option>
                        <option value="HK3" {{ old('term') == 'HK3' ? 'selected' : '' }}>Học kỳ 3 (Hè)</option>
                    </select>
                    @error('term')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Tên đợt <span class="text-danger">*</span></label>
                <input type="text" name="name" class="form-input @error('name') is-invalid @enderror" value="{{ old('name') }}" placeholder="VD: Đợt 1 - Ưu tiên" required>
                @error('name')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-row cols-2">
                <div class="form-group">
                    <label class="form-label">Thời gian bắt đầu <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="starts_at" class="form-input @error('starts_at') is-invalid @enderror" value="{{ old('starts_at') }}" required>
                    @error('starts_at')<div class="form-error">{{ $message }}</div>@enderror
                </div>
                <div class="form-group">
                    <label class="form-label">Thời gian kết thúc <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="ends_at" class="form-input @error('ends_at') is-invalid @enderror" value="{{ old('ends_at') }}" required>
                    @error('ends_at')<div class="form-error">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Khoa được phép đăng ký</label>
                <div style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 16px; background: #fafafa;">
                    @foreach($faculties as $faculty)
                    <div class="form-check" style="margin-bottom: 10px;">
                        <input class="form-check-input" type="checkbox" name="faculties[]" value="{{ $faculty->id }}" id="faculty{{ $faculty->id }}" {{ in_array($faculty->id, old('faculties', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="faculty{{ $faculty->id }}">{{ $faculty->name }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">Khóa được phép đăng ký</label>
                <div style="border: 1px solid #e0e0e0; border-radius: 8px; padding: 16px; background: #fafafa;">
                    @php($cohorts = ['K17', 'K18', 'K19', 'K20', 'K21'])
                    @foreach($cohorts as $cohort)
                    <div class="form-check form-check-inline" style="margin-bottom: 10px;">
                        <input class="form-check-input" type="checkbox" name="cohorts[]" value="{{ $cohort }}" id="cohort{{ $cohort }}" {{ in_array($cohort, old('cohorts', [])) ? 'checked' : '' }}>
                        <label class="form-check-label" for="cohort{{ $cohort }}">{{ $cohort }}</label>
                    </div>
                    @endforeach
                </div>
            </div>

            <div style="display: flex; gap: 12px; margin-top: 24px;">
                <button type="submit" class="btn-submit"><i class="fas fa-save" style="margin-right: 8px;"></i>Lưu</button>
                <a href="{{ route('registration-waves.index') }}" class="btn-cancel">Hủy</a>
            </div>
        </form>
    </div>
</div>
@endsection
