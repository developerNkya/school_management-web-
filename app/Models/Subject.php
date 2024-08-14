<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;
    protected $table = 'subjects';

    protected $fillable = [
        'name',
        'short_name',
        'school_id'
    ];

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'exam_subject');
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }
    
}
