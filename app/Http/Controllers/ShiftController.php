<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudyShift;
use App\Models\LogEntry;

class ShiftController extends Controller
{
    // A-1: Danh sách ca học + Tìm kiếm/Lọc
    public function index(Request $request)
    {
        $query = StudyShift::query();

        // Search by code or name
        if ($search = trim((string) $request->query('q'))) {
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%");
            });
        }

        // Filter by day_of_week
        if ($dow = $request->query('day')) {
            $query->where('day_of_week', (int) $dow);
        }

        // Filter by frame (Sáng/Chiều/Tối) derived from start_period
        if ($frame = $request->query('frame')) {
            if ($frame === 'morning') {
                $query->whereBetween('start_period', [1, 3]);
            } elseif ($frame === 'afternoon') {
                $query->whereBetween('start_period', [4, 6]);
            } elseif ($frame === 'evening') {
                $query->where('start_period', '>=', 7);
            }
        }

        // Filter by status
        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }

        $shifts = $query->orderBy('day_of_week')->orderBy('start_period')->paginate(10)->appends($request->query());

        $filters = [
            'q' => $request->query('q'),
            'day' => $request->query('day'),
            'frame' => $request->query('frame'),
            'status' => $request->query('status'),
        ];

        return view('admin.shifts.index', compact('shifts', 'filters'));
    }

    // A-1: Hiển thị form tạo ca học mới
    public function create()
    {
        return view('admin.shifts.create');
    }

    // A-1: Lưu ca học mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'nullable|string|max:20|unique:study_shifts,code',
            'name' => 'required|string|max:100',
            'day_of_week' => 'required|integer|min:1|max:7',
            // Prefer time pickers per UC2.6
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            // Fallback numeric periods (hidden or computed client-side)
            'start_period' => 'nullable|integer|min:1',
            'end_period' => 'nullable|integer|min:1',
            'status' => 'nullable|in:active,inactive',
        ], [
            'name.required' => 'Tên ca là bắt buộc.',
            'day_of_week.required' => 'Thứ trong tuần là bắt buộc.',
            'day_of_week.min' => 'Thứ phải từ 1 đến 7.',
            'day_of_week.max' => 'Thứ phải từ 1 đến 7.',
            'start_time.required' => 'Giờ bắt đầu là bắt buộc.',
            'end_time.required' => 'Giờ kết thúc là bắt buộc.',
        ]);

        // Compute periods from times if not provided
        [$sp, $ep] = $this->computePeriodsFromTimes($validated['start_time'], $validated['end_time']);
        if (isset($validated['start_period'])) $sp = (int) $validated['start_period'];
        if (isset($validated['end_period'])) $ep = (int) $validated['end_period'];

        if ($ep < $sp) {
            return back()->withErrors(['end_time' => 'Giờ kết thúc phải sau giờ bắt đầu.'])->withInput();
        }

        // Overlap check within same day
        $overlap = StudyShift::where('day_of_week', $validated['day_of_week'])
            ->where(function ($q) use ($sp, $ep) {
                $q->where(function ($q2) use ($sp, $ep) {
                    $q2->where('start_period', '<=', $ep)
                       ->where('end_period', '>=', $sp);
                });
            })
            ->exists();
        if ($overlap) {
            return back()->withErrors(['start_time' => 'Ca học bị trùng với ca khác trong cùng ngày.'])->withInput();
        }

        $shift = StudyShift::create([
            'code' => $validated['code'] ?? null,
            'name' => $validated['name'],
            'day_of_week' => (int) $validated['day_of_week'],
            'start_period' => $sp,
            'end_period' => $ep,
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'status' => $validated['status'] ?? 'active',
        ]);

        LogEntry::create([
            'user_id' => auth()->id(),
            'action' => 'shift_created',
            'metadata' => json_encode(['shift_id' => $shift->id]),
        ]);

        return redirect()->route('shifts.index')->with('success', 'Tạo ca học thành công.');
    }

    // A-1: Hiển thị form chỉnh sửa ca học
    public function edit(StudyShift $shift)
    {
        return view('admin.shifts.edit', ['shift' => $shift]);
    }

    // A-1: Cập nhật ca học
    public function update(Request $request, StudyShift $shift)
    {
        $validated = $request->validate([
            'code' => 'nullable|string|max:20|unique:study_shifts,code,' . $shift->id,
            'name' => 'required|string|max:100',
            'day_of_week' => 'required|integer|min:1|max:7',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i',
            'start_period' => 'nullable|integer|min:1',
            'end_period' => 'nullable|integer|min:1',
            'status' => 'nullable|in:active,inactive',
        ]);

        [$sp, $ep] = $this->computePeriodsFromTimes($validated['start_time'], $validated['end_time']);
        if (isset($validated['start_period'])) $sp = (int) $validated['start_period'];
        if (isset($validated['end_period'])) $ep = (int) $validated['end_period'];
        if ($ep < $sp) {
            return back()->withErrors(['end_time' => 'Giờ kết thúc phải sau giờ bắt đầu.'])->withInput();
        }

        $overlap = StudyShift::where('day_of_week', $validated['day_of_week'])
            ->where('id', '!=', $shift->id)
            ->where(function ($q) use ($sp, $ep) {
                $q->where(function ($q2) use ($sp, $ep) {
                    $q2->where('start_period', '<=', $ep)
                       ->where('end_period', '>=', $sp);
                });
            })
            ->exists();
        if ($overlap) {
            return back()->withErrors(['start_time' => 'Ca học bị trùng với ca khác trong cùng ngày.'])->withInput();
        }

        $shift->update([
            'code' => $validated['code'] ?? null,
            'name' => $validated['name'],
            'day_of_week' => (int) $validated['day_of_week'],
            'start_period' => $sp,
            'end_period' => $ep,
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'status' => $validated['status'] ?? 'active',
        ]);

        LogEntry::create([
            'user_id' => auth()->id(),
            'action' => 'shift_updated',
            'metadata' => json_encode(['shift_id' => $shift->id]),
        ]);

        return redirect()->route('shifts.index')->with('success', 'Cập nhật ca học thành công.');
    }

    // A-1: Xóa ca học
    public function destroy(StudyShift $shift)
    {
        if ($shift->classSections()->exists()) {
            return back()->with('error', 'Không thể xóa ca học đang được sử dụng. Đề xuất: Tạm ngưng thay vì xóa.');
        }

        $shift->delete();

        LogEntry::create([
            'user_id' => auth()->id(),
            'action' => 'shift_deleted',
            'metadata' => json_encode(['shift_id' => $shift->id]),
        ]);

        return redirect()->route('shifts.index')->with('success', 'Xóa ca học thành công.');
    }

    // Helpers
    private function computePeriodsFromTimes(string $startTime, string $endTime): array
    {
        [$sh, $sm] = array_map('intval', explode(':', $startTime));
        [$eh, $em] = array_map('intval', explode(':', $endTime));
        $startMinutes = $sh * 60 + $sm;
        $endMinutes = $eh * 60 + $em;
        // Base 07:00, 50 mins per period
        $base = 7 * 60;
        $sp = (int) floor(max(0, $startMinutes - $base) / 50) + 1;
        $ep = (int) ceil(max(0, $endMinutes - $base) / 50);
        $sp = max($sp, 1);
        $ep = max($ep, $sp);
        return [$sp, $ep];
    }
}
