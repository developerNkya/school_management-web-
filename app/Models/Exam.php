<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exam extends Model
{
    protected $fillable = ['name', 'start_date', 'school_id'];

    public function classes()
    {
        return $this->belongsToMany(SchoolClass::class, 'exam_class', 'exam_id', 'class_id');
    }



    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'exam_subject');
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }

    public function resultSent()
    {
        return $this->hasMany(ResultSent::class, 'exam_id');
    }

}
