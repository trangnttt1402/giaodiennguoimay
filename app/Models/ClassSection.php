<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSection extends Model
{
    use HasFactory;

    protected $fillable = [
        'academic_year',
        'term',
        'course_id',
        'section_code',
        'lecturer_id',
        'day_of_week',
        'shift_id',
        'room_id',
        'max_capacity',
        'status'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function lecturer()
    {
        return $this->belongsTo(User::class, 'lecturer_id');
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function shift()
    {
        return $this->belongsTo(StudyShift::class, 'shift_id');
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    // Accessors
    public function getDayNameAttribute(): string
    {
        $days = [2 => 'Thứ 2', 3 => 'Thứ 3', 4 => 'Thứ 4', 5 => 'Thứ 5', 6 => 'Thứ 6', 7 => 'Thứ 7', 1 => 'CN'];
        return $days[$this->day_of_week] ?? 'N/A';
    }
}
