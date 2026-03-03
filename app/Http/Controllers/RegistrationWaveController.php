<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RegistrationWave;
use App\Models\Faculty;
use App\Models\LogEntry;

class RegistrationWaveController extends Controller
{
    // S-1: Danh sách đợt đăng ký
    public function index()
    {
        $waves = RegistrationWave::orderBy('starts_at', 'desc')->paginate(20);
        $faculties = Faculty::orderBy('name')->get();
        return view('admin.registration-waves.index', [
            'waves' => $waves,
            'faculties' => $faculties,
        ]);
    }

    // S-1: Hiển thị form tạo đợt đăng ký mới
    public function create()
    {
        $faculties = Faculty::orderBy('name')->get();
        return view('admin.registration-waves.create', ['faculties' => $faculties]);
    }

    // S-1: Lưu đợt đăng ký mới
    public function store(Request $request)
    {
        $validated = $request->validate([
            'academic_year' => 'required|string|max:20',
            'term' => 'required|string|max:20',
            'name' => 'required|string|max:150',
            'faculties' => 'nullable|array',
            'faculties.*' => 'exists:faculties,id',
            'cohorts' => 'nullable|array',
            'cohorts.*' => 'string|max:50',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
        ], [
            'academic_year.required' => 'Năm học là bắt buộc.',
            'term.required' => 'Học kỳ là bắt buộc.',
            'name.required' => 'Tên đợt đăng ký là bắt buộc.',
            'starts_at.required' => 'Thời gian bắt đầu là bắt buộc.',
            'ends_at.required' => 'Thời gian kết thúc là bắt buộc.',
            'ends_at.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
        ]);

        // S-1: Lưu audience dưới dạng JSON
        $audience = [
            'faculties' => $validated['faculties'] ?? [],
            'cohorts' => $validated['cohorts'] ?? [],
        ];

        $wave = RegistrationWave::create([
            'academic_year' => $validated['academic_year'],
            'term' => $validated['term'],
            'name' => $validated['name'],
            'audience' => json_encode($audience),
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'],
        ]);

        LogEntry::create([
            'user_id' => auth()->id(),
            'action' => 'registration_wave_created',
            'metadata' => json_encode(['wave_id' => $wave->id, 'name' => $wave->name]),
        ]);

        return redirect()->route('registration-waves.index')->with('success', 'Tạo đợt đăng ký thành công.');
    }

    // S-1: Hiển thị chi tiết đợt đăng ký
    public function show(RegistrationWave $registration_wafe)
    {
        $faculties = Faculty::orderBy('name')->get();
        $audience = json_decode($registration_wafe->audience, true) ?? [];

        return view('admin.registration-waves.show', [
            'wave' => $registration_wafe,
            'faculties' => $faculties,
            'audience' => $audience,
        ]);
    }

    // S-1: Hiển thị form chỉnh sửa đợt đăng ký
    public function edit(RegistrationWave $registration_wafe)
    {
        $faculties = Faculty::orderBy('name')->get();
        $audience = json_decode($registration_wafe->audience, true) ?? [];

        return view('admin.registration-waves.edit', [
            'wave' => $registration_wafe,
            'faculties' => $faculties,
            'audience' => $audience,
        ]);
    }

    // S-1: Cập nhật đợt đăng ký
    public function update(Request $request, RegistrationWave $registration_wafe)
    {
        $validated = $request->validate([
            'academic_year' => 'required|string|max:20',
            'term' => 'required|string|max:20',
            'name' => 'required|string|max:150',
            'faculties' => 'nullable|array',
            'faculties.*' => 'exists:faculties,id',
            'cohorts' => 'nullable|array',
            'cohorts.*' => 'string|max:50',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date|after:starts_at',
        ], [
            'academic_year.required' => 'Năm học là bắt buộc.',
            'term.required' => 'Học kỳ là bắt buộc.',
            'name.required' => 'Tên đợt đăng ký là bắt buộc.',
            'starts_at.required' => 'Thời gian bắt đầu là bắt buộc.',
            'ends_at.required' => 'Thời gian kết thúc là bắt buộc.',
            'ends_at.after' => 'Thời gian kết thúc phải sau thời gian bắt đầu.',
        ]);

        $audience = [
            'faculties' => $validated['faculties'] ?? [],
            'cohorts' => $validated['cohorts'] ?? [],
        ];

    $registration_wafe->update([
            'academic_year' => $validated['academic_year'],
            'term' => $validated['term'],
            'name' => $validated['name'],
            'audience' => json_encode($audience),
            'starts_at' => $validated['starts_at'],
            'ends_at' => $validated['ends_at'],
        ]);

        LogEntry::create([
            'user_id' => auth()->id(),
            'action' => 'registration_wave_updated',
            'metadata' => json_encode(['wave_id' => $registration_wafe->id, 'name' => $registration_wafe->name]),
        ]);

        return redirect()->route('registration-waves.index')->with('success', 'Cập nhật đợt đăng ký thành công.');
    }

    // S-1: Xóa đợt đăng ký
    public function destroy(RegistrationWave $registration_wafe)
    {
        $registration_wafe->delete();

        LogEntry::create([
            'user_id' => auth()->id(),
            'action' => 'registration_wave_deleted',
            'metadata' => json_encode(['wave_name' => $registration_wafe->name]),
        ]);

        return redirect()->route('registration-waves.index')->with('success', 'Xóa đợt đăng ký thành công.');
    }
}
