<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassSection;
use App\Models\User;
use App\Models\Registration;
use App\Models\Announcement;
use App\Models\LogEntry;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class LecturerDashboardController extends Controller
{
    /**
     * UC4.1: Hiển thị thời khóa biểu giảng dạy (Dashboard)
     */
    public function index()
    {
        $lecturer = auth()->user();
        $academicYear = session('academic_year', '2024-2025');
        $term = session('term', 'HK1');

        // Lấy tất cả lớp học phần được phân công
        $classSections = ClassSection::with(['course', 'room', 'shift'])
            ->where('lecturer_id', $lecturer->id)
            ->where('academic_year', $academicYear)
            ->where('term', $term)
            ->get();

        // Tổ chức dữ liệu theo lịch tuần (7 ngày x các ca học)
        $schedule = [];
        for ($i = 0; $i < 7; $i++) {
            $schedule[$i] = [];
        }

        foreach ($classSections as $section) {
            $dayIndex = $section->day_of_week - 1; // 1=Thứ 2 -> index 0

            $schedule[$dayIndex][] = [
                'id' => $section->id,
                'course_name' => $section->course->name,
                'course_code' => $section->course->code,
                'section_code' => $section->section_code,
                'room' => $section->room ? $section->room->code : 'TBA',
                'shift' => $section->shift ? "Tiết {$section->shift->start_period}-{$section->shift->end_period}" : '',
                'time' => $section->shift ? $section->shift->start_time . ' - ' . $section->shift->end_time : '',
                'enrollment' => $section->current_enrollment . '/' . $section->max_capacity,
            ];
        }

        $days = ['Thứ 2', 'Thứ 3', 'Thứ 4', 'Thứ 5', 'Thứ 6', 'Thứ 7', 'Chủ nhật'];

        return view('lecturer.dashboard', [
            'schedule' => $schedule,
            'days' => $days,
            'academicYear' => $academicYear,
            'term' => $term,
            'totalClasses' => $classSections->count(),
        ]);
    }

    /**
     * UC2.8: Danh sách các lớp giảng dạy
     */
    public function myClasses()
    {
        $lecturer = auth()->user();
        $academicYear = session('academic_year', '2024-2025');
        $term = session('term', 'HK1');

        $classSections = ClassSection::with(['course', 'room', 'shift'])
            ->where('lecturer_id', $lecturer->id)
            ->where('academic_year', $academicYear)
            ->where('term', $term)
            ->orderBy('day_of_week')
            ->orderBy('shift_id')
            ->get();

        return view('lecturer.classes.index', [
            'classSections' => $classSections,
            'academicYear' => $academicYear,
            'term' => $term,
        ]);
    }

    /**
     * UC2.8: Xem chi tiết lớp và danh sách sinh viên
     */
    public function classDetail(ClassSection $classSection)
    {
        // Kiểm tra quyền: chỉ giảng viên được phân công mới được xem
        if ($classSection->lecturer_id !== auth()->id()) {
            return redirect()->route('lecturer.classes')->with('error', 'Bạn không có quyền xem lớp học này.');
        }

        $classSection->load(['course', 'room', 'shift']);

        // Danh sách sinh viên đã đăng ký (status = approved hoặc registered)
        $students = Registration::where('class_section_id', $classSection->id)
            ->whereIn('status', ['approved', 'registered'])
            ->with('student')
            ->orderBy('created_at')
            ->get()
            ->map(function ($registration, $index) {
                return [
                    'stt' => $index + 1,
                    'mssv' => $registration->student->code,
                    'name' => $registration->student->name,
                    'email' => $registration->student->email,
                    'phone' => $registration->student->phone ?? '--',
                    'registered_at' => $registration->created_at->format('d/m/Y H:i'),
                ];
            });

        return view('lecturer.classes.detail', [
            'classSection' => $classSection,
            'students' => $students,
        ]);
    }

    /**
     * UC1.4: Hiển thị hồ sơ cá nhân
     */
    public function profile()
    {
        $lecturer = auth()->user();
        $lecturer->load('faculty');

        return view('lecturer.profile.show', [
            'lecturer' => $lecturer,
        ]);
    }

    /**
     * UC1.4: Cập nhật hồ sơ cá nhân
     */
    public function updateProfile(Request $request)
    {
        $lecturer = auth()->user();

        $validated = $request->validate([
            'email' => 'required|email|unique:users,email,' . $lecturer->id,
            'phone' => 'nullable|string|max:20',
            'avatar' => 'nullable|image|max:2048', // 2MB max
        ], [
            'email.required' => 'Email là bắt buộc.',
            'email.email' => 'Email không hợp lệ.',
            'email.unique' => 'Email đã được sử dụng.',
            'avatar.image' => 'File phải là ảnh.',
            'avatar.max' => 'Ảnh không được vượt quá 2MB.',
        ]);

        // Xử lý upload avatar
        if ($request->hasFile('avatar')) {
            // Xóa avatar cũ nếu có
            if ($lecturer->avatar_url && Storage::disk('public')->exists($lecturer->avatar_url)) {
                Storage::disk('public')->delete($lecturer->avatar_url);
            }

            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar_url'] = $path;
        }

        $lecturer->update($validated);

        LogEntry::create([
            'user_id' => $lecturer->id,
            'action' => 'lecturer_profile_updated',
            'metadata' => json_encode(['email' => $validated['email']]),
        ]);

        return redirect()->route('lecturer.profile')->with('success', 'Cập nhật hồ sơ thành công.');
    }

    /**
     * UC1.3/UC1.6: Hiển thị form đổi mật khẩu
     */
    public function showChangePasswordForm()
    {
        return view('lecturer.profile.change-password');
    }

    /**
     * UC1.3/UC1.6: Xử lý đổi mật khẩu
     */
    public function changePassword(Request $request)
    {
        $lecturer = auth()->user();

        $validated = $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'new_password.required' => 'Vui lòng nhập mật khẩu mới.',
            'new_password.min' => 'Mật khẩu mới phải có ít nhất 8 ký tự.',
            'new_password.confirmed' => 'Xác nhận mật khẩu không khớp.',
        ]);

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($validated['current_password'], $lecturer->password)) {
            return back()->withErrors(['current_password' => 'Mật khẩu hiện tại không đúng.']);
        }

        $lecturer->update([
            'password' => Hash::make($validated['new_password']),
        ]);

        LogEntry::create([
            'user_id' => $lecturer->id,
            'action' => 'lecturer_password_changed',
            'metadata' => json_encode(['timestamp' => now()]),
        ]);

        return redirect()->route('lecturer.profile')->with('success', 'Đổi mật khẩu thành công.');
    }

    /**
     * UC1.7: Xem thông báo hệ thống
     */
    public function notifications()
    {
        $lecturer = auth()->user();

        // Lấy thông báo dành cho giảng viên
        $announcements = Announcement::where(function ($query) use ($lecturer) {
            $query->where('target_audience', 'all')
                ->orWhere('target_audience', 'lecturers')
                ->orWhere(function ($q) use ($lecturer) {
                    $q->where('target_audience', 'faculty')
                        ->where('target_faculty_id', $lecturer->faculty_id);
                });
        })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('lecturer.notifications', [
            'announcements' => $announcements,
        ]);
    }
}
