<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Followup extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = "";
    public function user()
    {
        return $this->belongsTo(User::class, "causer_id", "id");
    }
}
