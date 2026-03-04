<?php

namespace Database\Seeders;

use App\Models\Announcement;
use Illuminate\Database\Seeder;

class AnnouncementSeeder extends Seeder
{
    public function run(): void
    {
        Announcement::truncate();

        Announcement::create([
            'title' => '📢 Thông báo mở đợt đăng ký học phần HK2 năm học 2025-2026',
            'content' => "Phòng Đào tạo thông báo lịch đăng ký học phần Học kỳ 2, năm học 2025-2026 như sau:\n\n"
                . "🔹 Đợt 1 – Ưu tiên Khóa cũ (K17, K18):\n"
                . "   • Thời gian: 01/03/2026 – 07/03/2026\n"
                . "   • Đối tượng: Sinh viên K17, K18 các khoa CNTT, Kinh tế\n\n"
                . "🔹 Đợt 2 – Tất cả sinh viên:\n"
                . "   • Thời gian: 08/03/2026 – 14/03/2026\n"
                . "   • Đối tượng: Tất cả sinh viên hệ chính quy\n\n"
                . "📋 Các môn học mở trong kỳ này:\n"
                . "   • CS101 – Nhập môn Lập trình (3 TC)\n"
                . "   • CS201 – Cấu trúc Dữ liệu (3 TC)\n"
                . "   • CS301 – Cơ sở Dữ liệu (3 TC)\n"
                . "   • MATH101 – Giải tích 1 (3 TC)\n"
                . "   • ENG101 – Tiếng Anh cơ bản (2 TC)\n\n"
                . "⚠️ Lưu ý:\n"
                . "   - Sinh viên cần kiểm tra điều kiện tiên quyết trước khi đăng ký\n"
                . "   - Số tín chỉ tối đa mỗi kỳ: 24 tín chỉ\n"
                . "   - Sinh viên nợ học phí sẽ bị khóa tài khoản đăng ký\n\n"
                . "Mọi thắc mắc xin liên hệ Phòng Đào tạo (P.101, Tòa nhà A1) hoặc email: daotao@daihocabc.edu.vn",
            'audience' => json_encode(['roles' => ['student'], 'faculties' => [], 'cohorts' => []]),
            'published_at' => '2026-02-25 08:00:00',
        ]);

        Announcement::create([
            'title' => '📅 Lịch thi kết thúc học phần HK1 năm học 2025-2026',
            'content' => "Phòng Đào tạo thông báo lịch thi kết thúc học phần HK1 năm học 2025-2026:\n\n"
                . "📌 Thời gian thi: 15/01/2026 – 30/01/2026\n"
                . "📌 Hình thức: Thi tập trung tại trường\n\n"
                . "Sinh viên kiểm tra lịch thi chi tiết trên cổng thông tin và mang theo thẻ sinh viên khi đến phòng thi.\n\n"
                . "⚠️ Sinh viên vắng thi không có lý do chính đáng sẽ nhận điểm 0.",
            'audience' => json_encode(['roles' => ['student'], 'faculties' => [], 'cohorts' => []]),
            'published_at' => '2026-01-05 09:00:00',
        ]);

        Announcement::create([
            'title' => '🎓 Thông báo về việc xét tốt nghiệp đợt tháng 3/2026',
            'content' => "Phòng Đào tạo thông báo về việc xét tốt nghiệp đợt tháng 3/2026:\n\n"
                . "📌 Điều kiện xét tốt nghiệp:\n"
                . "   - Đã hoàn thành tất cả các học phần trong chương trình đào tạo\n"
                . "   - Điểm trung bình tích lũy ≥ 2.0\n"
                . "   - Không đang bị kỷ luật\n"
                . "   - Đã hoàn thành nghĩa vụ tài chính\n\n"
                . "📌 Thời gian nộp đơn: 01/02/2026 – 28/02/2026\n"
                . "📌 Nơi nộp: Phòng Đào tạo (P.101, Tòa nhà A1)\n\n"
                . "Sinh viên đủ điều kiện vui lòng nộp đơn đúng hạn.",
            'audience' => json_encode(['roles' => ['student'], 'faculties' => [], 'cohorts' => []]),
            'published_at' => '2026-01-20 10:00:00',
        ]);

        Announcement::create([
            'title' => '💰 Thông báo về học phí HK2 năm học 2025-2026',
            'content' => "Phòng Tài chính thông báo về việc đóng học phí HK2 năm học 2025-2026:\n\n"
                . "📌 Thời hạn đóng: 01/03/2026 – 31/03/2026\n"
                . "📌 Hình thức: Chuyển khoản qua cổng thanh toán trực tuyến\n\n"
                . "🔹 Mức học phí:\n"
                . "   • Chương trình đại trà: 450,000 VNĐ/tín chỉ\n"
                . "   • Chương trình chất lượng cao: 750,000 VNĐ/tín chỉ\n\n"
                . "⚠️ Sinh viên không đóng học phí đúng hạn sẽ bị khóa tài khoản đăng ký học phần.\n\n"
                . "Mọi thắc mắc xin liên hệ Phòng Tài chính (P.201, Tòa nhà A1).",
            'audience' => json_encode(['roles' => ['student'], 'faculties' => [], 'cohorts' => []]),
            'published_at' => '2026-02-20 08:30:00',
        ]);

        Announcement::create([
            'title' => '🔧 Bảo trì hệ thống đăng ký học phần',
            'content' => "Phòng Công nghệ thông tin thông báo:\n\n"
                . "Hệ thống đăng ký học phần sẽ tạm ngưng hoạt động để bảo trì nâng cấp:\n\n"
                . "📌 Thời gian: 22:00 ngày 28/02/2026 – 06:00 ngày 01/03/2026\n\n"
                . "Trong thời gian này, sinh viên không thể truy cập chức năng đăng ký học phần.\n"
                . "Các chức năng khác (xem điểm, tra cứu thông tin) vẫn hoạt động bình thường.\n\n"
                . "Xin cảm ơn sự thông cảm của các bạn sinh viên!",
            'audience' => json_encode(['roles' => ['student', 'lecturer'], 'faculties' => [], 'cohorts' => []]),
            'published_at' => '2026-02-27 14:00:00',
        ]);

        Announcement::create([
            'title' => '📚 Thông báo mở đăng ký học phần HK1 năm học 2025-2026',
            'content' => "Phòng Đào tạo thông báo lịch đăng ký học phần Học kỳ 1, năm học 2025-2026:\n\n"
                . "🔹 Đợt đăng ký chính:\n"
                . "   • Thời gian: 15/08/2025 – 30/08/2025\n"
                . "   • Đối tượng: Tất cả sinh viên\n\n"
                . "🔹 Đợt bổ sung:\n"
                . "   • Thời gian: 01/09/2025 – 07/09/2025\n"
                . "   • Đối tượng: Sinh viên chưa đăng ký đủ tín chỉ\n\n"
                . "Sinh viên đăng nhập vào cổng thông tin để đăng ký.",
            'audience' => json_encode(['roles' => ['student'], 'faculties' => [], 'cohorts' => []]),
            'published_at' => '2025-08-01 08:00:00',
        ]);

        $this->command->info('✅ Đã seed 6 thông báo mẫu thành công!');
    }
}
