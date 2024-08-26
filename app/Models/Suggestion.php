<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Suggestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'school_id',
        'suggestion'
    ];


    public function student()
    {
        return $this->belongsTo(StudentInfo::class,'student_id');
    }

    public function school()
    {
        return $this->belongsTo(School::class,'school_id');
    }
}
