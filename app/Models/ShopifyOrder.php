<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopifyOrder extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = [];

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    public function app()
    {
        return $this->belongsTo(StoreApp::class ,'app_id' ,'id');
    }
}
