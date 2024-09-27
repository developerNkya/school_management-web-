<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SchoolClass extends Model
{
    use HasFactory;
    protected $table = 'classes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'school_id',
    ];

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'exam_class');
    }

    public function resultSent()
    {
        return $this->hasMany(ResultSent::class, 'class_id');
    }


}
