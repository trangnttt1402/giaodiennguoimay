@extends('lecturer.layout')

@section('title', 'Đổi mật khẩu')

@section('content')
<div class="form-container" style="max-width: 600px;">
    <div class="form-card">
        <div class="form-header">
            <h2>🔐 Đổi mật khẩu</h2>
            <p>Thay đổi mật khẩu của bạn để bảo vệ tài khoản</p>
        </div>

        <form action="{{ route('lecturer.password.change.submit') }}" method="POST">
            @csrf

            <div class="form-group">
                <label class="form-label">Mật khẩu hiện tại <span class="required">*</span></label>
                <input type="password" class="form-input" name="current_password" required autocomplete="current-password">
                @error('current_password')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Mật khẩu mới <span class="required">*</span></label>
                <input type="password" class="form-input" name="new_password" required minlength="8" autocomplete="new-password">
                <small style="color: #94a3b8; display: block; margin-top: 4px;">Tối thiểu 8 ký tự</small>
                @error('new_password')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-group">
                <label class="form-label">Xác nhận mật khẩu mới <span class="required">*</span></label>
                <input type="password" class="form-input" name="new_password_confirmation" required minlength="8" autocomplete="new-password">
                @error('new_password_confirmation')<div class="form-error">{{ $message }}</div>@enderror
            </div>

            <div class="form-hint">
                <strong>Lưu ý:</strong> Sau khi đổi mật khẩu thành công, bạn sẽ được chuyển về trang hồ sơ cá nhân.
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-submit">💾 Đổi mật khẩu</button>
                <a href="{{ route('lecturer.profile') }}" class="btn-cancel">Hủy</a>
            </div>
        </form>
    </div>

    <!-- Security Tips -->
    <div class="card" style="border: 1px solid #ece8f6; border-radius: 12px; padding: 20px; margin-top: 20px;">
        <div style="margin-bottom: 12px; padding-bottom: 12px; border-bottom: 1px solid #ece8f6;">
            <h5 style="margin: 0; color: #1e293b; font-size: 16px; font-weight: 600;">
                <i class="fas fa-shield-alt" style="margin-right: 8px; color: #6B4B9D;"></i>Lời khuyên bảo mật
            </h5>
        </div>
        <ul style="margin: 0; padding-left: 20px; color: #64748b; font-size: 13px;">
            <li style="margin-bottom: 8px;">Sử dụng mật khẩu mạnh (chữ hoa, chữ thường, số, ký tự đặc biệt)</li>
            <li style="margin-bottom: 8px;">Không chia sẻ mật khẩu với bất kỳ ai</li>
            <li style="margin-bottom: 8px;">Thay đổi mật khẩu định kỳ (3-6 tháng/lần)</li>
            <li>Không sử dụng cùng mật khẩu cho nhiều hệ thống</li>
        </ul>
    </div>
</div>
@endsection