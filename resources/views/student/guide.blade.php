@extends('student.layout')

@section('title', 'Hướng dẫn sử dụng')

@section('content')
<style>
    .guide-container {
        max-width: 1000px;
        margin: 0 auto;
    }

    .guide-header {
        text-align: center;
        margin-bottom: 40px;
        padding: 40px 20px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 16px;
        color: white;
    }

    .guide-header h1 {
        margin: 0 0 12px 0;
        font-size: 32px;
        font-weight: 700;
    }

    .guide-header p {
        margin: 0;
        font-size: 16px;
        opacity: 0.95;
    }

    .guide-icon-big {
        font-size: 64px;
        margin-bottom: 20px;
        filter: drop-shadow(0 4px 12px rgba(0,0,0,0.2));
    }

    .guide-section {
        background: white;
        border-radius: 12px;
        padding: 30px;
        margin-bottom: 24px;
        border: 1px solid #e2e8f0;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }

    .guide-section h2 {
        margin: 0 0 20px 0;
        font-size: 22px;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 12px;
        padding-bottom: 16px;
        border-bottom: 2px solid #f1f5f9;
    }

    .guide-section h2 .icon {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 10px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
    }

    .guide-steps {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .guide-step {
        display: flex;
        gap: 16px;
        padding: 20px;
        background: #f8fafc;
        border-radius: 10px;
        border-left: 4px solid #667eea;
        transition: all 0.3s ease;
    }

    .guide-step:hover {
        background: #f1f5f9;
        transform: translateX(4px);
    }

    .step-number {
        flex-shrink: 0;
        width: 36px;
        height: 36px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 16px;
        box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3);
    }

    .step-content h3 {
        margin: 0 0 8px 0;
        font-size: 17px;
        font-weight: 600;
        color: #1e293b;
    }

    .step-content p {
        margin: 0;
        color: #475569;
        line-height: 1.6;
        font-size: 15px;
    }

    .guide-features {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 16px;
        margin-top: 20px;
    }

    .feature-card {
        background: white;
        padding: 24px;
        border-radius: 12px;
        border: 1px solid #e2e8f0;
        text-align: center;
        transition: all 0.3s ease;
        text-decoration: none;
        display: block;
        color: inherit;
    }

    .feature-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
        border-color: #cbd5e1;
        cursor: pointer;
    }

    .feature-icon {
        font-size: 48px;
        margin-bottom: 16px;
    }

    .feature-card h3 {
        margin: 0 0 8px 0;
        font-size: 17px;
        font-weight: 600;
        color: #1e293b;
    }

    .feature-card p {
        margin: 0;
        color: #64748b;
        font-size: 14px;
        line-height: 1.5;
    }

    .guide-tips {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        padding: 24px;
        border-radius: 12px;
        border-left: 4px solid #f59e0b;
        margin-top: 20px;
    }

    .guide-tips h3 {
        margin: 0 0 12px 0;
        color: #92400e;
        font-size: 18px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .guide-tips ul {
        margin: 0;
        padding-left: 24px;
        color: #78350f;
    }

    .guide-tips li {
        margin-bottom: 8px;
        line-height: 1.6;
    }

    .contact-section {
        background: linear-gradient(135deg, #e0e7ff 0%, #ddd6fe 100%);
        padding: 30px;
        border-radius: 12px;
        text-align: center;
        margin-top: 40px;
    }

    .contact-section h3 {
        margin: 0 0 16px 0;
        font-size: 20px;
        font-weight: 700;
        color: #3730a3;
    }

    .contact-section p {
        margin: 0;
        color: #4c1d95;
        font-size: 15px;
    }

    .contact-links {
        display: flex;
        justify-content: center;
        gap: 16px;
        margin-top: 20px;
        flex-wrap: wrap;
    }

    .contact-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 12px 24px;
        background: white;
        color: #4c1d95;
        text-decoration: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .contact-link:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.12);
        background: #f5f3ff;
    }
</style>

<div class="guide-container">
    <!-- Header -->
    <div class="guide-header">
        <div class="guide-icon-big">📚</div>
        <h1>Hướng dẫn sử dụng hệ thống</h1>
        <p>Tất cả những gì bạn cần biết để sử dụng hệ thống đăng ký tín chỉ hiệu quả</p>
    </div>

    <!-- Tính năng chính -->
    <div class="guide-section">
        <h2><span class="icon">✨</span> Tính năng chính</h2>
        <div class="guide-features">
            <a href="{{ route('student.dashboard') }}" class="feature-card">
                <div class="feature-icon">🏠</div>
                <h3>Trang chủ</h3>
                <p>Xem tổng quan thông tin học tập, điểm số và tiến độ học tập của bạn</p>
            </a>
            <a href="{{ route('student.profile.show') }}" class="feature-card">
                <div class="feature-icon">👤</div>
                <h3>Hồ sơ cá nhân</h3>
                <p>Quản lý và cập nhật thông tin cá nhân, ảnh đại diện</p>
            </a>
            <a href="{{ route('student.offerings') }}" class="feature-card">
                <div class="feature-icon">📝</div>
                <h3>Đăng ký môn học</h3>
                <p>Đăng ký, hủy và quản lý các môn học trong học kỳ</p>
            </a>
            <a href="{{ route('student.timetable') }}" class="feature-card">
                <div class="feature-icon">📅</div>
                <h3>Thời khóa biểu</h3>
                <p>Xem lịch học, lịch thi và xuất file iCalendar</p>
            </a>
        </div>
    </div>

    <!-- Hướng dẫn đăng ký môn học -->
    <div class="guide-section">
        <h2><span class="icon">📝</span> Cách đăng ký môn học</h2>
        <div class="guide-steps">
            <div class="guide-step">
                <div class="step-number">1</div>
                <div class="step-content">
                    <h3>Truy cập trang "Đăng ký trực tuyến"</h3>
                    <p>Click vào menu "Đăng ký trực tuyến" trên thanh menu bên trái để xem danh sách môn học đang mở đăng ký</p>
                </div>
            </div>
            <div class="guide-step">
                <div class="step-number">2</div>
                <div class="step-content">
                    <h3>Tìm kiếm và lọc môn học</h3>
                    <p>Sử dụng thanh tìm kiếm hoặc bộ lọc theo khoa, học kỳ để tìm môn học phù hợp. Kiểm tra thông tin lớp học, giảng viên, phòng học và thời gian</p>
                </div>
            </div>
            <div class="guide-step">
                <div class="step-number">3</div>
                <div class="step-content">
                    <h3>Kiểm tra điều kiện đăng ký</h3>
                    <p>Đảm bảo bạn đã hoàn thành các môn tiên quyết (nếu có) và lớp học còn chỗ trống</p>
                </div>
            </div>
            <div class="guide-step">
                <div class="step-number">4</div>
                <div class="step-content">
                    <h3>Nhấn nút "Đăng ký"</h3>
                    <p>Click vào nút "Đăng ký" bên cạnh môn học muốn đăng ký. Hệ thống sẽ kiểm tra và xác nhận đăng ký của bạn</p>
                </div>
            </div>
            <div class="guide-step">
                <div class="step-number">5</div>
                <div class="step-content">
                    <h3>Kiểm tra môn đã đăng ký</h3>
                    <p>Vào tab "Môn đã đăng ký" để xem danh sách các môn học bạn đã đăng ký thành công</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Hướng dẫn xem thời khóa biểu -->
    <div class="guide-section">
        <h2><span class="icon">📅</span> Cách xem thời khóa biểu</h2>
        <div class="guide-steps">
            <div class="guide-step">
                <div class="step-number">1</div>
                <div class="step-content">
                    <h3>Mở trang "Thời khóa biểu"</h3>
                    <p>Click vào menu "Thời khóa biểu" để xem lịch học của bạn trong tuần</p>
                </div>
            </div>
            <div class="guide-step">
                <div class="step-number">2</div>
                <div class="step-content">
                    <h3>Xem chi tiết lịch học</h3>
                    <p>Thời khóa biểu hiển thị theo từng ngày trong tuần với đầy đủ thông tin: môn học, giảng viên, phòng học, ca học</p>
                </div>
            </div>
            <div class="guide-step">
                <div class="step-number">3</div>
                <div class="step-content">
                    <h3>Xuất lịch ra Google Calendar</h3>
                    <p>Nhấn nút "Xuất iCal" để tải file lịch và import vào Google Calendar, Outlook hoặc ứng dụng lịch khác</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Mẹo hữu ích -->
    <div class="guide-section">
        <h2><span class="icon">💡</span> Mẹo hữu ích</h2>
        <div class="guide-tips">
            <h3>💡 Lưu ý quan trọng</h3>
            <ul>
                <li><strong>Đăng ký sớm:</strong> Các lớp học phổ biến thường kín chỗ nhanh, hãy đăng ký ngay khi đợt mở đăng ký</li>
                <li><strong>Kiểm tra thời gian:</strong> Đảm bảo không có xung đột lịch học giữa các môn đã đăng ký</li>
                <li><strong>Môn tiên quyết:</strong> Kiểm tra kỹ các môn tiên quyết trước khi đăng ký môn mới</li>
                <li><strong>Số tín chỉ tối đa:</strong> Mỗi học kỳ có giới hạn số tín chỉ tối đa được đăng ký</li>
                <li><strong>Thời hạn hủy:</strong> Lưu ý thời hạn hủy môn học để tránh mất học phí</li>
                <li><strong>Cập nhật thường xuyên:</strong> Kiểm tra email và thông báo hệ thống thường xuyên để không bỏ lỡ thông tin quan trọng</li>
            </ul>
        </div>
    </div>

    <!-- Các câu hỏi thường gặp -->
    <div class="guide-section">
        <h2><span class="icon">❓</span> Câu hỏi thường gặp</h2>
        <div class="guide-steps">
            <div class="guide-step">
                <div class="step-number">?</div>
                <div class="step-content">
                    <h3>Làm sao để hủy môn học đã đăng ký?</h3>
                    <p>Vào trang "Đăng ký trực tuyến" → Tab "Môn đã đăng ký" → Nhấn nút "Hủy" bên cạnh môn muốn hủy. Lưu ý: chỉ có thể hủy trong thời gian cho phép.</p>
                </div>
            </div>
            <div class="guide-step">
                <div class="step-number">?</div>
                <div class="step-content">
                    <h3>Tôi quên mật khẩu, phải làm sao?</h3>
                    <p>Tại trang đăng nhập, nhấn vào "Quên mật khẩu?", nhập email hoặc mã sinh viên, sau đó làm theo hướng dẫn để đặt lại mật khẩu.</p>
                </div>
            </div>
            <div class="guide-step">
                <div class="step-number">?</div>
                <div class="step-content">
                    <h3>Làm sao để cập nhật thông tin cá nhân?</h3>
                    <p>Vào trang "Hồ sơ cá nhân", nhấn nút "Chỉnh sửa", cập nhật thông tin cần thiết và nhấn "Lưu thay đổi".</p>
                </div>
            </div>
            <div class="guide-step">
                <div class="step-number">?</div>
                <div class="step-content">
                    <h3>Tại sao không đăng ký được môn học?</h3>
                    <p>Có thể do: lớp đã đầy, chưa hoàn thành môn tiên quyết, trùng lịch với môn khác, hoặc vượt quá số tín chỉ tối đa. Kiểm tra thông báo lỗi để biết nguyên nhân cụ thể.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Liên hệ hỗ trợ -->
    <div class="contact-section">
        <h3>🆘 Cần hỗ trợ thêm?</h3>
        <p>Nếu bạn gặp vấn đề hoặc cần hỗ trợ, đừng ngần ngại liên hệ với chúng tôi</p>
        <div class="contact-links">
            <a href="mailto:support@dangkytinchi.edu.vn" class="contact-link">
                📧 Email hỗ trợ
            </a>
            <a href="tel:1900xxxx" class="contact-link">
                📞 Hotline: 1900 xxxx
            </a>
        </div>
    </div>
</div>
@endsection
