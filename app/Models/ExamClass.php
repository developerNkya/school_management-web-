<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamClass extends Model
{
    use HasFactory;
    protected $table = 'exam_class';

    protected $fillable = [
        'exam_id',
        'class_id'
    ];
}
