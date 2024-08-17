<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamSubject extends Model
{
    use HasFactory;
    protected $table = 'exam_subject';


    public function subjects()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

}
