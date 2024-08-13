<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    protected $table = 'teachers';

    protected $fillable = [
        'first_name',
        'second_name',
        'last_name',
        'date_of_birth',
        'gender',
        'nationality',
        'city',
        'phone_number',
        'user_id',
        'school_id',
        'email'
    ];
}

