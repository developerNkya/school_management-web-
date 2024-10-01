<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    // Define the fillable properties
    protected $table = 'assignments';
    protected $fillable = [
        'name',
        'subject_id',
        'class_id',
        'sender_id',
        'submission_date',
        'file_path',
        'school_id',
        'assignment_type'
    ];

    // Relationships
    public function subject()
    {
        return $this->belongsTo(Subject::class,'subject_id');
    }

    public function class()
    {
        return $this->belongsTo(SchoolClass::class,'class_id');
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }

    
    public function school()
    {
        return $this->belongsTo(School::class, 'school_id');
    }
}
