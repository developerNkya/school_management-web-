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
    ];
}
