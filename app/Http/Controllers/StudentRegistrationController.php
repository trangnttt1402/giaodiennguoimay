<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\ClassSection;
use App\Models\Course;
use App\Models\Registration;
use App\Models\Transcript;
use App\Models\RegistrationWave;

class StudentRegistrationController extends Controller
{
    private int $minCredits = 12;
    private int $maxCredits = 22;

    public function offerings(Request $request)
    {
        $user = Auth::user();
        $year = $request->input('academic_year', session('academic_year', '2024-2025'));
        $term = $request->input('term', session('term', 'HK1'));
        $query = ClassSection::with(['course', 'lecturer', 'room', 'shift'])
            ->where('academic_year', $year)->where('term', $term);

        if ($search = $request->input('search')) {
            $query->whereHas('course', function ($q) use ($search) {
                $q->where('code', 'like', "%$search%")
                    ->orWhere('name', 'like', "%$search%");
            })->orWhere('section_code', 'like', "%$search%");
        }

        if ($facultyId = $request->input('faculty_id')) {
            $query->whereHas('course', function ($q) use ($facultyId) {
                $q->where('faculty_id', $facultyId);
            });
        }

        $sections = $query->paginate(20)->withQueryString();

        $currentRegs = $this->currentRegistrations($year, $term)->load('classSection.course', 'classSection.shift', 'classSection.room');
        $registeredSectionIds = $currentRegs->pluck('class_section_id')->toArray();

        // Current open wave info
        $wave = RegistrationWave::where('academic_year', $year)->where('term', $term)
            ->where('starts_at', '<=', now())->where('ends_at', '>=', now())
            ->orderBy('starts_at', 'desc')->first();
        $openForUser = $this->isRegistrationOpenFor($user, $year, $term);

        return view('student.registrations.offerings', compact('sections', 'year', 'term', 'registeredSectionIds', 'currentRegs', 'wave', 'openForUser'));
    }

    public function my()
    {
        $year = session('academic_year', '2024-2025');
        $term = session('term', 'HK1');
        $regs = $this->currentRegistrations($year, $term)->load('classSection.course', 'classSection.room', 'classSection.shift', 'classSection.lecturer');
        $credits = $regs->sum(fn($r) => $r->classSection->course->credits);
        return view('student.registrations.my', compact('regs', 'year', 'term', 'credits'));
    }

    public function register(Request $request, ClassSection $classSection)
    {
        $user = Auth::user();
        // Re-validate context
        $year = $classSection->academic_year;
        $term = $classSection->term;

        // Check if student is locked (UC3.1 - 2)
        if ($user->is_locked) {
            return back()->with('error', 'Tài khoản của bạn đã bị khóa học vụ. Vui lòng liên hệ phòng đào tạo.');
        }

        // Check wave open and audience (UC3.1 - 2)
        if (!$this->isRegistrationOpenFor($user, $year, $term)) {
            return back()->with('error', 'Hiện không trong thời gian đăng ký cho đối tượng của bạn.');
        }

        // Check if already registered for this section
        $alreadyRegistered = Registration::where('student_id', $user->id)
            ->where('class_section_id', $classSection->id)
            ->exists();
        if ($alreadyRegistered) {
            return back()->with('error', 'Bạn đã đăng ký lớp học phần này rồi.');
        }

        // Prerequisites check with detailed error (UC3.1 - 2a)
        $prereqCheck = $this->checkPrerequisites($user->id, $classSection->course_id);
        if (!$prereqCheck['satisfied']) {
            $missing = implode(', ', $prereqCheck['missing']);
            return back()->with('error', 'Chưa đủ điều kiện tiên quyết. Bạn cần hoàn thành các học phần: ' . $missing);
        }

        // Schedule conflict with suggestion (UC3.1 - 2b)
        $conflictCheck = $this->checkScheduleConflict($user->id, $classSection);
        if ($conflictCheck['hasConflict']) {
            $conflictWith = $conflictCheck['conflictWith'];
            $errorMsg = 'Xung đột lịch học với lớp ' . $conflictWith . '.';

            // Suggest alternative sections
            $alternatives = ClassSection::with('course')
                ->where('course_id', $classSection->course_id)
                ->where('academic_year', $year)
                ->where('term', $term)
                ->where('id', '!=', $classSection->id)
                ->get();

            if ($alternatives->count() > 0) {
                $errorMsg .= ' Gợi ý: Bạn có thể đăng ký các lớp khác cùng môn học.';
            }

            return back()->with('error', $errorMsg);
        }

        // Credit limit check (UC3.1 - 2c)
        $currentCredits = $this->currentRegistrations($year, $term)->sum(fn($r) => $r->classSection->course->credits);
        $newTotal = $currentCredits + $classSection->course->credits;
        if ($newTotal > $this->maxCredits) {
            return back()->with('error', 'Vượt giới hạn tín chỉ tối đa (' . $this->maxCredits . ' TC). Hiện tại: ' . $currentCredits . ' TC, sau khi thêm: ' . $newTotal . ' TC.');
        }

        // Check for equivalent courses (UC3.1 - 2d)
        $equivalentCheck = $this->hasEquivalentCourse($user->id, $classSection->course_id);
        if ($equivalentCheck['hasEquivalent']) {
            return back()->with('error', 'Bạn đã đăng ký/đạt học phần tương đương: ' . $equivalentCheck['courseName']);
        }

        // Capacity check (UC3.1 - 2e)
        $enrolled = Registration::where('class_section_id', $classSection->id)->count();
        if ($enrolled >= $classSection->max_capacity) {
            return back()->with('error', 'Lớp đã hết chỗ (' . $enrolled . '/' . $classSection->max_capacity . ').');
        }

        // All checks passed - create registration (UC3.1 - 3)
        try {
            Registration::create([
                'student_id' => $user->id,
                'class_section_id' => $classSection->id,
            ]);

            // UC3.1 - 4: Success notification
            return back()->with('success', 'Đăng ký thành công môn ' . $classSection->course->code . ' - ' . $classSection->course->name . ', lớp ' . $classSection->section_code . '.');
        } catch (\Exception $e) {
            // UC3.1 - 2f: System error
            return back()->with('error', 'Có lỗi xảy ra. Vui lòng thử lại sau.');
        }
    }

    public function cancel(Request $request, Registration $registration)
    {
        $user = Auth::user();
        if ($registration->student_id !== $user->id) {
            abort(403);
        }

        $classSection = $registration->classSection;
        if (!$this->isRegistrationOpenFor($user, $classSection->academic_year, $classSection->term)) {
            return back()->with('error', 'Đợt đăng ký đã đóng.');
        }

        $registration->delete();
        return back()->with('success', 'Hủy đăng ký thành công.');
    }

    public function swap(Request $request)
    {
        $request->validate([
            'from_registration_id' => 'required|exists:registrations,id',
            'to_section_id' => 'required|exists:class_sections,id',
        ]);
        $user = Auth::user();
        $from = Registration::with('classSection')->findOrFail($request->from_registration_id);
        if ($from->student_id !== $user->id) abort(403);
        $to = ClassSection::with(['course', 'room', 'shift'])->findOrFail($request->to_section_id);

        // same course constraint for swap
        if ($from->classSection->course_id !== $to->course_id) {
            return back()->with('error', 'Chỉ được đổi giữa các lớp cùng một môn học.');
        }
        // Wave open
        if (!$this->isRegistrationOpenFor($user, $to->academic_year, $to->term)) {
            return back()->with('error', 'Đợt đăng ký đã đóng.');
        }
        // Capacity
        $enrolled = Registration::where('class_section_id', $to->id)->count();
        if ($enrolled >= $to->max_capacity) {
            return back()->with('error', 'Lớp chuyển đến đã đủ.');
        }
        // Schedule conflict (excluding the from section)
        $conflictCheck = $this->checkScheduleConflictForSwap($user->id, $to, $from->id);
        if ($conflictCheck['hasConflict']) {
            return back()->with('error', 'Xung đột lịch học với lớp ' . $conflictCheck['conflictWith'] . '.');
        }

        DB::transaction(function () use ($from, $to) {
            $from->delete();
            Registration::create([
                'student_id' => $from->student_id,
                'class_section_id' => $to->id,
            ]);
        });

        return back()->with('success', 'Đổi lớp thành công.');
    }

    public function timetable()
    {
        $year = session('academic_year', '2024-2025');
        $term = session('term', 'HK1');
        $regs = $this->currentRegistrations($year, $term)->load('classSection.course', 'classSection.room', 'classSection.shift');
        return view('student.timetable.index', compact('regs', 'year', 'term'));
    }

    public function exportIcs()
    {
        $year = session('academic_year', '2024-2025');
        $term = session('term', 'HK1');
        $regs = $this->currentRegistrations($year, $term)->load('classSection.course', 'classSection.room', 'classSection.shift');

        $lines = [
            'BEGIN:VCALENDAR',
            'VERSION:2.0',
            'PRODID:-//DKTC//Student Schedule//VN'
        ];
        foreach ($regs as $r) {
            $shift = $r->classSection->shift;
            $course = $r->classSection->course;
            // Placeholder date mapping: assume week start today for demo
            $date = now()->startOfWeek()->addDays(($shift->day_of_week ?? 2) - 1);
            $start = $date->copy()->setTime(7, 0)->addMinutes(($shift->start_period - 1) * 50);
            $end = $date->copy()->setTime(7, 0)->addMinutes(($shift->end_period) * 50);
            $lines[] = 'BEGIN:VEVENT';
            $lines[] = 'UID:' . uniqid();
            $lines[] = 'DTSTAMP:' . now()->format('Ymd\THis\Z');
            $lines[] = 'DTSTART:' . $start->format('Ymd\THis');
            $lines[] = 'DTEND:' . $end->format('Ymd\THis');
            $lines[] = 'SUMMARY:' . $course->code . ' - ' . $course->name;
            $lines[] = 'LOCATION:' . ($r->classSection->room->code ?? '');
            $lines[] = 'END:VEVENT';
        }
        $lines[] = 'END:VCALENDAR';

        return response(implode("\r\n", $lines), 200, [
            'Content-Type' => 'text/calendar; charset=utf-8',
            'Content-Disposition' => 'attachment; filename="timetable.ics"'
        ]);
    }

    // --- Registration Cart (Shopping Cart) ---
    public function cart()
    {
        $year = session('academic_year', '2024-2025');
        $term = session('term', 'HK1');
        $ids = session('reg_cart', []);
        $sections = ClassSection::with(['course', 'room', 'shift'])->whereIn('id', $ids)->get();
        $existingRegs = $this->currentRegistrations($year, $term)->load('classSection.course', 'classSection.shift');
        $issues = $this->evaluateCart($sections, $existingRegs);
        $totalCredits = $existingRegs->sum(fn($r) => $r->classSection->course->credits) + $sections->sum(fn($s) => $s->course->credits);
        return view('student.registrations.cart', compact('sections', 'issues', 'totalCredits', 'year', 'term'));
    }

    public function cartAdd(ClassSection $classSection)
    {
        $user = Auth::user();
        // Basic wave check (quick feedback; full checks on checkout)
        if (!$this->isRegistrationOpenFor($user, $classSection->academic_year, $classSection->term)) {
            return back()->with('error', 'Hiện không trong thời gian đăng ký.');
        }
        $cart = session('reg_cart', []);
        if (!in_array($classSection->id, $cart)) {
            $cart[] = $classSection->id;
            session(['reg_cart' => $cart]);
        }
        return back()->with('status', 'Đã thêm vào giỏ.');
    }

    public function cartRemove(ClassSection $classSection)
    {
        $cart = array_values(array_filter(session('reg_cart', []), fn($id) => (int)$id !== (int)$classSection->id));
        session(['reg_cart' => $cart]);
        return back()->with('status', 'Đã bỏ khỏi giỏ.');
    }

    public function cartCheckout(Request $request)
    {
        $user = Auth::user();
        $year = session('academic_year', '2024-2025');
        $term = session('term', 'HK1');
        $ids = session('reg_cart', []);
        if (empty($ids)) return redirect()->route('student.cart')->with('error', 'Giỏ trống.');
        $sections = ClassSection::with(['course', 'room', 'shift'])->whereIn('id', $ids)->get();
        $existingRegs = $this->currentRegistrations($year, $term)->load('classSection.course', 'classSection.shift');
        $issues = $this->evaluateCart($sections, $existingRegs);

        if (!empty($issues)) {
            return redirect()->route('student.cart')->with('error', 'Giỏ có lỗi, vui lòng kiểm tra.');
        }

        DB::transaction(function () use ($sections, $user) {
            foreach ($sections as $s) {
                // Final guards per item
                $enrolled = Registration::where('class_section_id', $s->id)->count();
                if ($enrolled >= $s->max_capacity) {
                    throw new \RuntimeException('Lớp ' . $s->section_code . ' đã đủ.');
                }
                Registration::firstOrCreate([
                    'student_id' => $user->id,
                    'class_section_id' => $s->id,
                ]);
            }
        });

        session()->forget('reg_cart');
        return redirect()->route('student.my')->with('status', 'Đăng ký thành công các môn trong giỏ.');
    }

    private function evaluateCart($sections, $existingRegs): array
    {
        $user = Auth::user();
        $year = session('academic_year', '2024-2025');
        $term = session('term', 'HK1');
        $issues = [];

        // Wave for each
        foreach ($sections as $s) {
            if (!$this->isRegistrationOpenFor($user, $s->academic_year, $s->term)) {
                $issues[$s->id][] = 'Đợt đăng ký đã đóng.';
            }
            // Already registered
            if ($existingRegs->firstWhere('class_section_id', $s->id)) {
                $issues[$s->id][] = 'Đã đăng ký lớp này.';
            }
            // Capacity
            $enrolled = Registration::where('class_section_id', $s->id)->count();
            if ($enrolled >= $s->max_capacity) {
                $issues[$s->id][] = 'Lớp đã đủ.';
            }
            // Prereq
            if (!$this->satisfyPrerequisites($user->id, $s->course_id)) {
                $issues[$s->id][] = 'Chưa thỏa điều kiện tiên quyết.';
            }
        }

        // Schedule conflicts (with existing regs)
        foreach ($sections as $s) {
            if ($this->hasScheduleConflict($user->id, $s)) {
                $issues[$s->id][] = 'Xung đột với lớp đã đăng ký.';
            }
        }

        // Conflicts within cart and duplicate courses
        for ($i = 0; $i < count($sections); $i++) {
            for ($j = $i + 1; $j < count($sections); $j++) {
                $a = $sections[$i];
                $b = $sections[$j];
                if ($a->course_id === $b->course_id) {
                    $issues[$a->id][] = 'Trùng môn với lớp khác trong giỏ.';
                    $issues[$b->id][] = 'Trùng môn với lớp khác trong giỏ.';
                }
                if ($a->day_of_week === $b->day_of_week && $a->shift_id === $b->shift_id) {
                    $issues[$a->id][] = 'Trùng lịch với lớp trong giỏ.';
                    $issues[$b->id][] = 'Trùng lịch với lớp trong giỏ.';
                }
            }
        }

        // Credit limit check
        $currentCredits = $existingRegs->sum(fn($r) => $r->classSection->course->credits);
        $cartCredits = $sections->sum(fn($s) => $s->course->credits);
        if ($currentCredits + $cartCredits > $this->maxCredits) {
            foreach ($sections as $s) {
                $issues[$s->id][] = 'Vượt giới hạn tín chỉ.';
            }
        }

        return array_map('array_unique', $issues);
    }

    private function currentRegistrations(string $year, string $term)
    {
        $userId = Auth::id();
        return Registration::with(['classSection.course'])
            ->where('student_id', $userId)
            ->whereHas('classSection', function ($q) use ($year, $term) {
                $q->where('academic_year', $year)->where('term', $term);
            })->get();
    }

    private function hasScheduleConflict(int $studentId, ClassSection $section, ?int $excludeRegistrationId = null): bool
    {
        $query = Registration::where('student_id', $studentId)
            ->whereHas('classSection', function ($q) use ($section) {
                $q->where('academic_year', $section->academic_year)
                    ->where('term', $section->term)
                    ->where('day_of_week', $section->day_of_week)
                    ->where('shift_id', $section->shift_id);
            });
        if ($excludeRegistrationId) {
            $query->where('id', '!=', $excludeRegistrationId);
        }
        return $query->exists();
    }

    private function satisfyPrerequisites(int $studentId, int $courseId): bool
    {
        $check = $this->checkPrerequisites($studentId, $courseId);
        return $check['satisfied'];
    }

    private function checkPrerequisites(int $studentId, int $courseId): array
    {
        $course = Course::with('prerequisites')->find($courseId);
        if (!$course) {
            return ['satisfied' => false, 'missing' => ['Môn học không tồn tại']];
        }

        if ($course->prerequisites->isEmpty()) {
            return ['satisfied' => true, 'missing' => []];
        }

        $passedCourseIds = Transcript::where('student_id', $studentId)
            ->where('passed', true)
            ->pluck('course_id')
            ->toArray();

        $missing = [];
        foreach ($course->prerequisites as $pr) {
            if (!in_array($pr->id, $passedCourseIds)) {
                $missing[] = $pr->code . ' - ' . $pr->name;
            }
        }

        return [
            'satisfied' => empty($missing),
            'missing' => $missing
        ];
    }

    private function checkScheduleConflict(int $studentId, ClassSection $section): array
    {
        $conflict = Registration::with('classSection.course')
            ->where('student_id', $studentId)
            ->whereHas('classSection', function ($q) use ($section) {
                $q->where('academic_year', $section->academic_year)
                    ->where('term', $section->term)
                    ->where('day_of_week', $section->day_of_week)
                    ->where('shift_id', $section->shift_id);
            })
            ->first();

        if ($conflict) {
            return [
                'hasConflict' => true,
                'conflictWith' => $conflict->classSection->course->code . ' (' . $conflict->classSection->section_code . ')'
            ];
        }

        return ['hasConflict' => false, 'conflictWith' => null];
    }

    private function checkScheduleConflictForSwap(int $studentId, ClassSection $section, int $excludeRegistrationId): array
    {
        $conflict = Registration::with('classSection.course')
            ->where('student_id', $studentId)
            ->where('id', '!=', $excludeRegistrationId)
            ->whereHas('classSection', function ($q) use ($section) {
                $q->where('academic_year', $section->academic_year)
                    ->where('term', $section->term)
                    ->where('day_of_week', $section->day_of_week)
                    ->where('shift_id', $section->shift_id);
            })
            ->first();

        if ($conflict) {
            return [
                'hasConflict' => true,
                'conflictWith' => $conflict->classSection->course->code . ' (' . $conflict->classSection->section_code . ')'
            ];
        }

        return ['hasConflict' => false, 'conflictWith' => null];
    }

    private function hasEquivalentCourse(int $studentId, int $courseId): array
    {
        // Check if student has already registered or passed equivalent courses
        // For now, we'll check if the same course is already in their transcript
        $existingInTranscript = Transcript::with('course')
            ->where('student_id', $studentId)
            ->where('course_id', $courseId)
            ->where('passed', true)
            ->first();

        if ($existingInTranscript) {
            return [
                'hasEquivalent' => true,
                'courseName' => $existingInTranscript->course->code . ' - ' . $existingInTranscript->course->name
            ];
        }

        // Check if already registered for current term
        $year = session('academic_year', '2024-2025');
        $term = session('term', 'HK1');

        $alreadyRegistered = Registration::with('classSection.course')
            ->where('student_id', $studentId)
            ->whereHas('classSection', function ($q) use ($courseId, $year, $term) {
                $q->where('course_id', $courseId)
                    ->where('academic_year', $year)
                    ->where('term', $term);
            })
            ->first();

        if ($alreadyRegistered) {
            return [
                'hasEquivalent' => true,
                'courseName' => $alreadyRegistered->classSection->course->code . ' (đã đăng ký lớp ' . $alreadyRegistered->classSection->section_code . ')'
            ];
        }

        return ['hasEquivalent' => false, 'courseName' => null];
    }

    private function isRegistrationOpenFor($user, string $year, string $term): bool
    {
        $now = now();
        $waves = RegistrationWave::where('academic_year', $year)->where('term', $term)
            ->where('starts_at', '<=', $now)->where('ends_at', '>=', $now)->get();
        if ($waves->isEmpty()) return false;
        foreach ($waves as $w) {
            $aud = json_decode($w->audience, true) ?? [];
            $facOk = empty($aud['faculties']) || in_array($user->faculty_id, $aud['faculties']);
            $cohOk = empty($aud['cohorts']) || in_array($user->class_cohort, $aud['cohorts']);
            if ($facOk && $cohOk) return true;
        }
        return false;
    }

    public function print()
    {
        $year = session('academic_year', '2024-2025');
        $term = session('term', 'HK1');
        $regs = $this->currentRegistrations($year, $term)->load('classSection.course', 'classSection.room', 'classSection.shift');
        $credits = $regs->sum(fn($r) => $r->classSection->course->credits);
        return view('student.registrations.print', compact('regs', 'year', 'term', 'credits'));
    }
}
