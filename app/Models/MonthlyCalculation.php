<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MonthlyCalculation extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = "";
    public function form() {
        return $this->belongsTo(PaymentForm::class);
    }
}
