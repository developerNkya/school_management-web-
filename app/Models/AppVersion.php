<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppVersion extends Model
{
    use HasFactory;

    // Define the fillable properties
    protected $table = 'app_version';
    protected $fillable = [
        'current_version',
    ];
}
