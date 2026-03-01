<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registration;
use App\Models\RegistrationWave;
use App\Models\Announcement;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $year = session('academic_year', '2024-2025');
        $term = session('term', 'HK1');

        $currentRegs = Registration::with(['classSection.course', 'classSection.room', 'classSection.shift'])
            ->where('student_id', $user->id)
            ->whereHas('classSection', function ($q) use ($year, $term) {
                $q->where('academic_year', $year)->where('term', $term);
            })->get();

        $totalCredits = $currentRegs->sum(fn($r) => $r->classSection->course->credits);

        $waves = RegistrationWave::orderBy('starts_at', 'desc')->take(3)->get();
        $announcements = Announcement::orderBy('published_at', 'desc')->take(5)->get();

        return view('student.dashboard', compact('currentRegs', 'totalCredits', 'waves', 'announcements', 'year', 'term'));
    }
}
