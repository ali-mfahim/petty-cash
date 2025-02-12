<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentForm extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = "";
   
    public function paidBy()
    {
        return $this->belongsTo(User::class, 'paid_by', 'id');
    }
}
