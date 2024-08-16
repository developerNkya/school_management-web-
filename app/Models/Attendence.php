<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attendence extends Model
{
    use HasFactory;

    protected $table = 'attendances';

    protected $fillable = [
        'attendance_name', 
        'academic_year', 
        'attendance_type', 
        'school_id',
    ];
}
