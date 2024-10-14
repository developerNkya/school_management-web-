<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;
    protected $fillable = ['from', 'to','grade', 'school_id'];
    
    public function school()
    {
        return $this->belongsTo(School::class,'school_id');
    }
}
