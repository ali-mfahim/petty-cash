<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomerForm extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = "";
    public function files()
    {
        return $this->hasMany(CustomerFormHasFiles::class, 'form_id', "id");
    }
    public function categories()
    {
        return $this->hasMany(FormHasCategory::class, 'form_id', "id");
    }
    public function hasStatus()
    {
        return $this->belongsTo(CustomerFormStatus::class, 'status', 'id');
    }

    public function hasColor()
    {
        return $this->belongsTo(Color::class, 'color', 'id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function country()
    {
        return $this->belongsTo(Country::class, 'phone_code', 'id');
    }
}
