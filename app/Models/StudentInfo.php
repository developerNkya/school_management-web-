<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentInfo extends Model
{
    use HasFactory;
    protected $table = 'student_info';

    protected $fillable = [
        'first_name',
        'middle_name',
        'last_name',
        'registration_no',
        'date_of_birth',
        'gender',
        'blood_group',
        'nationality',
        'city',
        'passport_photo',
        'class_id',
        'section',
        'parent_first_name',
        'parent_last_name',
        'parent_phone',
        'parent_email',
        'user_id',
        'school_id',
        'activity',
        'driver_id'
    ];


    public function user()
    {
        return $this->belongsTo(User::class,'user_id');
    }

    public function School()
    {
        return $this->belongsTo(User::class,'school_id');
    }

    public function Schoolclass()
    {
        return $this->belongsTo(SchoolClass::class,'class_id');
    }
    public function section()
    {
        return $this->belongsTo(Section::class,'section');
    }

    public function attendances()
{
    return $this->hasMany(AttendenceData::class, 'student_id');
}

public function getFullNameAttribute()
{
    return "{$this->first_name} {$this->middle_name} {$this->last_name}";
}

}
