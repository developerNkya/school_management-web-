<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendenceData extends Model
{
    use HasFactory;

    protected $table = 'attendance_data';

    protected $fillable = [
        'attendence_id',
        'class_id',
        'section_id',
        'subject_id',
        'student_id',
        'school_id',
        'date',
        'status',
        'total_appearance',
        'percentage',
        'created_at'
    ];

    public function attendance()
    {
        return $this->belongsTo(Attendence::class, 'attendence_id');
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class);
    }

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function student()
    {
        return $this->belongsTo(StudentInfo::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
