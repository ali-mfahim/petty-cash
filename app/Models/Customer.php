<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = "";
    public function forms()
    {
        return $this->hasMany(CustomerForm::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'phone_code', 'id');
    }
}
