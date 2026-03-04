<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Registration;
use App\Models\RegistrationWave;
use Illuminate\Support\Facades\Auth;

class StudentNotificationController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get announcements
        $announcements = Announcement::whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->paginate(15);

        // Get registration waves with status info
        $waves = RegistrationWave::orderBy('starts_at', 'desc')->take(10)->get()->map(function ($wave) {
            $now = now();
            if ($now->lt($wave->starts_at)) {
                $wave->status = 'upcoming';
                $wave->status_label = 'Sắp mở';
            } elseif ($now->between($wave->starts_at, $wave->ends_at)) {
                $wave->status = 'open';
                $wave->status_label = 'Đang mở';
            } else {
                $wave->status = 'closed';
                $wave->status_label = 'Đã đóng';
            }
            return $wave;
        });

        // Get student's total registrations count
        $totalRegs = Registration::where('student_id', $user->id)->count();

        return view('student.notifications.index', compact('announcements', 'waves', 'totalRegs'));
    }

    public function show(Announcement $announcement)
    {
        $user = Auth::user();

        // Get student's registrations for context
        $regs = Registration::where('student_id', $user->id)
            ->with(['classSection.course', 'classSection.shift', 'classSection.room', 'classSection.lecturer'])
            ->get();

        // Get current/upcoming waves
        $waves = RegistrationWave::where('ends_at', '>=', now()->subDays(30))
            ->orderBy('starts_at', 'desc')
            ->get()
            ->map(function ($wave) {
                $now = now();
                if ($now->lt($wave->starts_at)) {
                    $wave->status = 'upcoming';
                    $wave->status_label = 'Sắp mở';
                } elseif ($now->between($wave->starts_at, $wave->ends_at)) {
                    $wave->status = 'open';
                    $wave->status_label = 'Đang mở';
                } else {
                    $wave->status = 'closed';
                    $wave->status_label = 'Đã đóng';
                }
                return $wave;
            });

        return view('student.notifications.show', compact('announcement', 'regs', 'waves'));
    }

    public function guide()
    {
        return view('student.guide');
    }
}
