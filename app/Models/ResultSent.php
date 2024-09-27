<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResultSent extends Model
{
    use HasFactory;
    protected $table = 'results_sent';
    protected $fillable = [
        'exam_id',
        'class_id',
        'sent_at',
        'school_id'
    ];

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id');
    }
    
}
