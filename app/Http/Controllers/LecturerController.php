<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Faculty;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class LecturerController extends Controller
{
    /**
     * Display a listing of lecturers (UC2.2)
     */
    public function index(Request $request)
    {
        $query = User::where('role', 'lecturer')->with('faculty');

        // Search filter
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        // Faculty filter
        if ($facultyId = $request->input('faculty_id')) {
            $query->where('faculty_id', $facultyId);
        }

        $lecturers = $query->orderBy('code')->paginate(20);
        $faculties = Faculty::orderBy('name')->get();

        return view('admin.lecturers.index', compact('lecturers', 'faculties'));
    }

    /**
     * Show the form for creating a new lecturer
     */
    public function create()
    {
        $faculties = Faculty::orderBy('name')->get();
        return view('admin.lecturers.create', compact('faculties'));
    }

    /**
     * Store a newly created lecturer in storage
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:20|unique:users,code',
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'faculty_id' => 'required|exists:faculties,id',
            'phone' => 'nullable|string|max:20',
            'degree' => 'nullable|string|max:100',
            'password' => 'required|string|min:6',
        ]);

        $validated['role'] = 'lecturer';
        $validated['password'] = Hash::make($validated['password']);
        $validated['is_locked'] = false;

        User::create($validated);

        return redirect()->route('lecturers.index')
            ->with('success', 'Thêm giảng viên thành công!');
    }

    /**
     * Show the form for editing the specified lecturer
     */
    public function edit(User $lecturer)
    {
        if ($lecturer->role !== 'lecturer') {
            abort(404);
        }

        $faculties = Faculty::orderBy('name')->get();
        return view('admin.lecturers.edit', compact('lecturer', 'faculties'));
    }

    /**
     * Update the specified lecturer in storage
     */
    public function update(Request $request, User $lecturer)
    {
        if ($lecturer->role !== 'lecturer') {
            abort(404);
        }

        $validated = $request->validate([
            'code' => ['required', 'string', 'max:20', Rule::unique('users')->ignore($lecturer->id)],
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', Rule::unique('users')->ignore($lecturer->id)],
            'faculty_id' => 'required|exists:faculties,id',
            'phone' => 'nullable|string|max:20',
            'degree' => 'nullable|string|max:100',
            'password' => 'nullable|string|min:6',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $lecturer->update($validated);

        return redirect()->route('lecturers.index')
            ->with('success', 'Cập nhật giảng viên thành công!');
    }

    /**
     * Remove the specified lecturer from storage
     */
    public function destroy(User $lecturer)
    {
        if ($lecturer->role !== 'lecturer') {
            abort(404);
        }

        // Check if lecturer is assigned to any class sections
        if ($lecturer->classSections()->exists()) {
            return back()->with('error', 'Không thể xóa giảng viên đang phụ trách lớp học phần!');
        }

        $lecturer->delete();

        return redirect()->route('lecturers.index')
            ->with('success', 'Xóa giảng viên thành công!');
    }
}
