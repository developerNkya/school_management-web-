<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Finance extends Model
{
    use HasFactory;
    protected $fillable = ['last_payment', 'last_payment_date','pending_balance', 'next_payment_date','next_payment_amount','school_id'];
    
    public function school()
    {
        return $this->belongsTo(School::class,'school_id');
    }
}
