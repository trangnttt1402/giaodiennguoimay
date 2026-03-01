<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ClassSection;
use App\Models\Course;
use App\Models\Faculty;
use App\Models\User;
use App\Models\Room;
use App\Models\StudyShift;
use App\Models\LogEntry;
use Illuminate\Validation\Rule;

class ClassSectionController extends Controller
{
    // A-3: Danh sách lớp học phần
    public function index(Request $request)
    {
        $academicYear = $request->query('academic_year', session('academic_year', '2024-2025'));
        $term = $request->query('term', session('term', 'HK1'));

        $query = ClassSection::with(['course.faculty', 'lecturer', 'room', 'shift'])
            ->where('academic_year', $academicYear)
            ->where('term', $term);

        // Filter: Khoa (Faculty)
        if ($facultyId = $request->query('faculty_id')) {
            $query->whereHas('course', function ($q) use ($facultyId) {
                $q->where('faculty_id', $facultyId);
            });
        }

        // Filter: Tìm kiếm (Search)
        if ($search = $request->query('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('section_code', 'like', "%{$search}%")
                    ->orWhereHas('course', function ($qq) use ($search) {
                        $qq->where('code', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%");
                    });
            });
        }

        // Filter: Phòng học (Room)
        if ($roomId = $request->query('room_id')) {
            $query->where('room_id', $roomId);
        }

        // Filter: Ca học (Shift)
        if ($shiftId = $request->query('shift_id')) {
            $query->where('shift_id', $shiftId);
        }

        // Filter: Trạng thái (Status)
        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        // Filter: Chưa phân công GV (Unassigned lecturer)
        if ($request->query('unassigned_lecturer') === '1') {
            $query->whereNull('lecturer_id');
        }

        $classSections = $query->orderBy('section_code')->paginate(15)->appends($request->query());

        // Load data for filters
        $faculties = Faculty::orderBy('code')->get();
        $rooms = Room::where('status', 'active')->orderBy('code')->get();
        $shifts = StudyShift::orderBy('day_of_week')->orderBy('start_period')->get();

        return view('admin.class-sections.index', [
            'classSections' => $classSections,
            'academicYear' => $academicYear,
            'term' => $term,
            'faculties' => $faculties,
            'rooms' => $rooms,
            'shifts' => $shifts,
            'filters' => $request->query(),
        ]);
    }

    // A-3: Hiển thị form tạo lớp học phần mới
    public function create()
    {
        $academicYear = session('academic_year', '2024-2025');
        $term = session('term', 'HK1');

        $courses = Course::with('faculty')->orderBy('code')->get();
        $lecturers = User::where('role', 'lecturer')->orderBy('name')->get();
        $rooms = Room::orderBy('code')->get();
        $shifts = StudyShift::orderBy('day_of_week')->orderBy('start_period')->get();

        return view('admin.class-sections.create', [
            'academicYear' => $academicYear,
            'term' => $term,
            'courses' => $courses,
            'lecturers' => $lecturers,
            'rooms' => $rooms,
            'shifts' => $shifts,
        ]);
    }

    // A-3, A-4: Lưu lớp học phần mới với validation
    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year' => 'required|string|max:20',
            'term' => 'required|string|max:20',
            'course_id' => 'required|exists:courses,id',
            'section_code' => 'required|string|max:50',
            'lecturer_id' => 'nullable|exists:users,id',
            'day_of_week' => 'required|integer|min:1|max:7',
            'shift_id' => 'required|exists:study_shifts,id',
            'room_id' => 'required|exists:rooms,id',
            'max_capacity' => 'required|integer|min:1',
            'status' => 'nullable|in:active,locked',
        ], [
            'academic_year.required' => 'Năm học là bắt buộc.',
            'term.required' => 'Học kỳ là bắt buộc.',
            'course_id.required' => 'Học phần là bắt buộc.',
            'section_code.required' => 'Mã lớp là bắt buộc.',
            'day_of_week.required' => 'Thứ trong tuần là bắt buộc.',
            'shift_id.required' => 'Ca học là bắt buộc.',
            'room_id.required' => 'Phòng học là bắt buộc.',
            'max_capacity.required' => 'Sĩ số tối đa là bắt buộc.',
            'max_capacity.min' => 'Sĩ số tối đa phải lớn hơn 0.',
        ]);

        try {
            // A-4: Kiểm tra mã lớp trùng lặp trong cùng năm học và học kỳ
            $duplicate = ClassSection::where('academic_year', $validated['academic_year'])
                ->where('term', $validated['term'])
                ->where('course_id', $validated['course_id'])
                ->where('section_code', $validated['section_code'])
                ->exists();

            if ($duplicate) {
                return back()->withInput()->withErrors([
                    'section_code' => 'Mã lớp "' . $validated['section_code'] . '" đã tồn tại cho môn học này trong năm học và học kỳ này.',
                ]);
            }

            // A-4: Kiểm tra sĩ số tối đa <= sức chứa phòng
            // Thay vì chặn lưu, tự động điều chỉnh xuống bằng sức chứa phòng và hiển thị cảnh báo
            $room = Room::find($validated['room_id']);
            if ($room && $validated['max_capacity'] > $room->capacity) {
                // Ghi chú cảnh báo cho người dùng
                session()->flash('warning', "Sĩ số tối đa ({$validated['max_capacity']}) vượt quá sức chứa phòng \"{$room->code}\" ({$room->capacity} người). Hệ thống đã tự động điều chỉnh về {$room->capacity}.");
                // Điều chỉnh để cho phép lưu
                $validated['max_capacity'] = (int) $room->capacity;
            }

            // A-4: Kiểm tra xung đột lịch giảng viên (cùng thứ, cùng ca học, cùng HK/NH)
            if ($validated['lecturer_id'] && $validated['shift_id']) {
                $lecturerConflict = ClassSection::where('academic_year', $validated['academic_year'])
                    ->where('term', $validated['term'])
                    ->where('lecturer_id', $validated['lecturer_id'])
                    ->where('day_of_week', $validated['day_of_week'])
                    ->where('shift_id', $validated['shift_id'])
                    ->exists();

                if ($lecturerConflict) {
                    $lecturer = User::find($validated['lecturer_id']);
                    $dayName = $this->getDayName($validated['day_of_week']);
                    return back()->withInput()->withErrors([
                        'lecturer_id' => "Giảng viên \"{$lecturer->name}\" đã có lịch dạy vào {$dayName} ca này.",
                    ]);
                }
            }

            // A-4: Kiểm tra xung đột phòng học (cùng thứ, cùng ca học, cùng HK/NH)
            if ($validated['room_id'] && $validated['shift_id']) {
                $roomConflict = ClassSection::where('academic_year', $validated['academic_year'])
                    ->where('term', $validated['term'])
                    ->where('room_id', $validated['room_id'])
                    ->where('day_of_week', $validated['day_of_week'])
                    ->where('shift_id', $validated['shift_id'])
                    ->exists();

                if ($roomConflict) {
                    $dayName = $this->getDayName($validated['day_of_week']);
                    return back()->withInput()->withErrors([
                        'room_id' => "Phòng \"{$room->code}\" đã được sử dụng vào {$dayName} ca này.",
                    ]);
                }
            }

            // Set default status if not provided
            $validated['status'] = $validated['status'] ?? 'active';

            $classSection = ClassSection::create($validated);

            LogEntry::create([
                'user_id' => auth()->id(),
                'action' => 'class_section_created',
                'metadata' => json_encode([
                    'class_section_id' => $classSection->id,
                    'section_code' => $classSection->section_code,
                    'course_id' => $classSection->course_id,
                    'academic_year' => $classSection->academic_year,
                    'term' => $classSection->term
                ]),
            ]);

            return redirect()->route('class-sections.index')->with('success', "✓ Tạo lớp học phần \"{$classSection->section_code}\" thành công.");
        } catch (\Exception $e) {
            \Log::error('Error creating class section: ' . $e->getMessage());
            return back()->withInput()->with('error', '❌ Lỗi hệ thống: Không thể tạo lớp học phần. Vui lòng thử lại.');
        }
    }

    // A-3: Hiển thị form chỉnh sửa lớp học phần
    public function edit(ClassSection $classSection)
    {
        $courses = Course::with('faculty')->orderBy('code')->get();
        $lecturers = User::where('role', 'lecturer')->orderBy('name')->get();
        $rooms = Room::orderBy('code')->get();
        $shifts = StudyShift::orderBy('day_of_week')->orderBy('start_period')->get();

        return view('admin.class-sections.edit', [
            'classSection' => $classSection,
            'courses' => $courses,
            'lecturers' => $lecturers,
            'rooms' => $rooms,
            'shifts' => $shifts,
        ]);
    }

    // A-3, A-4: Cập nhật lớp học phần với validation
    public function update(Request $request, ClassSection $classSection)
    {
        $validated = $request->validate([
            'academic_year' => 'required|string|max:20',
            'term' => 'required|string|max:20',
            'course_id' => 'required|exists:courses,id',
            'section_code' => 'required|string|max:50',
            'lecturer_id' => 'nullable|exists:users,id',
            'day_of_week' => 'required|integer|min:1|max:7',
            'shift_id' => 'required|exists:study_shifts,id',
            'room_id' => 'required|exists:rooms,id',
            'max_capacity' => 'required|integer|min:1',
            'status' => 'nullable|in:active,locked',
        ], [
            'academic_year.required' => 'Năm học là bắt buộc.',
            'term.required' => 'Học kỳ là bắt buộc.',
            'course_id.required' => 'Học phần là bắt buộc.',
            'section_code.required' => 'Mã lớp là bắt buộc.',
            'day_of_week.required' => 'Thứ trong tuần là bắt buộc.',
            'shift_id.required' => 'Ca học là bắt buộc.',
            'room_id.required' => 'Phòng học là bắt buộc.',
            'max_capacity.required' => 'Sĩ số tối đa là bắt buộc.',
        ]);

        try {
            // A-4: Kiểm tra mã lớp trùng lặp (trừ chính nó)
            $duplicate = ClassSection::where('academic_year', $validated['academic_year'])
                ->where('term', $validated['term'])
                ->where('course_id', $validated['course_id'])
                ->where('section_code', $validated['section_code'])
                ->where('id', '!=', $classSection->id)
                ->exists();

            if ($duplicate) {
                return back()->withInput()->withErrors([
                    'section_code' => 'Mã lớp đã tồn tại cho môn học này trong năm học và học kỳ này.',
                ]);
            }

            // A-4: Kiểm tra sĩ số tối đa <= sức chứa phòng
            // Thay vì chặn cập nhật, tự động điều chỉnh và cảnh báo
            $room = Room::find($validated['room_id']);
            if ($room && $validated['max_capacity'] > $room->capacity) {
                session()->flash('warning', "Sĩ số tối đa ({$validated['max_capacity']}) vượt quá sức chứa phòng \"{$room->code}\" ({$room->capacity} người). Hệ thống đã tự động điều chỉnh về {$room->capacity}.");
                $validated['max_capacity'] = (int) $room->capacity;
            }

            // A-4: Kiểm tra xung đột lịch giảng viên
            if ($validated['lecturer_id'] && $validated['shift_id']) {
                $lecturerConflict = ClassSection::where('academic_year', $validated['academic_year'])
                    ->where('term', $validated['term'])
                    ->where('lecturer_id', $validated['lecturer_id'])
                    ->where('day_of_week', $validated['day_of_week'])
                    ->where('shift_id', $validated['shift_id'])
                    ->where('id', '!=', $classSection->id)
                    ->exists();

                if ($lecturerConflict) {
                    $lecturer = User::find($validated['lecturer_id']);
                    $dayName = $this->getDayName($validated['day_of_week']);
                    return back()->withInput()->withErrors([
                        'lecturer_id' => "Giảng viên \"{$lecturer->name}\" đã có lịch dạy vào {$dayName} ca này.",
                    ]);
                }
            }

            // A-4: Kiểm tra xung đột phòng học
            if ($validated['room_id'] && $validated['shift_id']) {
                $roomConflict = ClassSection::where('academic_year', $validated['academic_year'])
                    ->where('term', $validated['term'])
                    ->where('room_id', $validated['room_id'])
                    ->where('day_of_week', $validated['day_of_week'])
                    ->where('shift_id', $validated['shift_id'])
                    ->where('id', '!=', $classSection->id)
                    ->exists();

                if ($roomConflict) {
                    $dayName = $this->getDayName($validated['day_of_week']);
                    return back()->withInput()->withErrors([
                        'room_id' => "Phòng \"{$room->code}\" đã được sử dụng vào {$dayName} ca này.",
                    ]);
                }
            }

            $classSection->update($validated);

            LogEntry::create([
                'user_id' => auth()->id(),
                'action' => 'class_section_updated',
                'metadata' => json_encode([
                    'class_section_id' => $classSection->id,
                    'section_code' => $classSection->section_code,
                ]),
            ]);

            return redirect()->route('class-sections.index')->with('success', "✓ Cập nhật lớp học phần \"{$classSection->section_code}\" thành công.");
        } catch (\Exception $e) {
            \Log::error('Error updating class section: ' . $e->getMessage());
            return back()->withInput()->with('error', '❌ Lỗi hệ thống: Không thể cập nhật lớp học phần. Vui lòng thử lại.');
        }
    }

    // A-3: Xóa lớp học phần
    public function destroy(ClassSection $classSection)
    {
        try {
            // Kiểm tra lớp đã có sinh viên đăng ký
            $registrationCount = $classSection->registrations()->count();

            if ($registrationCount > 0) {
                return back()->with(
                    'error',
                    "❌ Không thể xóa lớp học phần \"{$classSection->section_code}\" vì đã có {$registrationCount} sinh viên đăng ký.\n\n" .
                        "💡 GỢI Ý: Thay vì xóa, bạn có thể:\n" .
                        "1. Chuyển trạng thái lớp thành \"Tạm khóa\" (giữ dữ liệu lịch sử)\n" .
                        "2. Hủy đăng ký của sinh viên trước khi xóa\n" .
                        "3. Đợi đến khi kết thúc học kỳ"
                );
            }

            $sectionCode = $classSection->section_code;
            $classSection->delete();

            LogEntry::create([
                'user_id' => auth()->id(),
                'action' => 'class_section_deleted',
                'metadata' => json_encode([
                    'section_code' => $sectionCode,
                    'course_id' => $classSection->course_id,
                    'academic_year' => $classSection->academic_year,
                    'term' => $classSection->term,
                    'deleted_at' => now()->toDateTimeString()
                ]),
            ]);

            return redirect()->route('class-sections.index')->with('success', "✓ Xóa lớp học phần \"{$sectionCode}\" thành công.");
        } catch (\Exception $e) {
            \Log::error('Error deleting class section: ' . $e->getMessage());
            return back()->with('error', '❌ Lỗi hệ thống: Không thể xóa lớp học phần. Vui lòng thử lại.');
        }
    }

    /**
     * Get class section detail (API endpoint for modal)
     */
    public function getDetail(ClassSection $classSection)
    {
        // Load relationships
        $classSection->load([
            'course.faculty',
            'lecturer',
            'room',
            'shift',
            'registrations.student' // Load registered students
        ]);

        // Get recent log entries for this class section
        $logs = LogEntry::where('metadata->class_section_id', $classSection->id)
            ->with('user')
            ->latest()
            ->take(10)
            ->get()
            ->map(function ($log) {
                return [
                    'action' => $log->action,
                    'user' => $log->user->name ?? 'System',
                    'timestamp' => $log->created_at->format('d/m/Y H:i'),
                    'details' => json_decode($log->metadata, true)
                ];
            });

        // Get registered students with status
        $students = $classSection->registrations->map(function ($registration) {
            return [
                'student_id' => $registration->student->student_id ?? 'N/A',
                'name' => $registration->student->name ?? 'N/A',
                'status' => $registration->status ?? 'registered',
                'registered_at' => $registration->created_at->format('d/m/Y H:i')
            ];
        });

        return response()->json([
            'class_section' => [
                'id' => $classSection->id,
                'section_code' => $classSection->section_code,
                'academic_year' => $classSection->academic_year,
                'term' => $classSection->term,
                'course' => [
                    'code' => $classSection->course->code ?? 'N/A',
                    'name' => $classSection->course->name ?? 'N/A',
                    'credits' => $classSection->course->credits ?? 0,
                    'faculty' => $classSection->course->faculty->name ?? 'N/A'
                ],
                'lecturer' => $classSection->lecturer ? [
                    'name' => $classSection->lecturer->name,
                    'email' => $classSection->lecturer->email
                ] : null,
                'schedule' => [
                    'day_of_week' => $classSection->day_of_week,
                    'day_name' => $this->getDayName($classSection->day_of_week),
                    'shift' => $classSection->shift ? [
                        'name' => $classSection->shift->name ?? 'N/A',
                        'start_period' => $classSection->shift->start_period,
                        'end_period' => $classSection->shift->end_period
                    ] : null
                ],
                'room' => $classSection->room ? [
                    'code' => $classSection->room->code,
                    'name' => $classSection->room->name,
                    'building' => $classSection->room->building,
                    'capacity' => $classSection->room->capacity
                ] : null,
                'max_capacity' => $classSection->max_capacity,
                'current_enrollment' => $classSection->registrations->count(),
                'status' => $classSection->status ?? 'active'
            ],
            'students' => $students,
            'logs' => $logs
        ]);
    }

    /**
     * Helper: Get Vietnamese day name
     */
    private function getDayName($dayNumber)
    {
        $days = [
            1 => 'Thứ Hai',
            2 => 'Thứ Ba',
            3 => 'Thứ Tư',
            4 => 'Thứ Năm',
            5 => 'Thứ Sáu',
            6 => 'Thứ Bảy',
            7 => 'Chủ Nhật'
        ];
        return $days[$dayNumber] ?? 'N/A';
    }
}
