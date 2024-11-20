<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Collection extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = "";
    public function products()
    {
        return $this->hasMany(CollectionProduct::class);
    }
    public function matchedProducts()
    {
        return $this->hasMany(CollectionProduct::class)->where("matched_store", 1)->where("status", 0);
    }
}
