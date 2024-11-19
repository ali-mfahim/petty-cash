<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('collection_products', function (Blueprint $table) {
            $table->id();
            $table->integer('store_id')->nullable();
            $table->integer('collection_id')->nullable();
            $table->string('product_gid')->nullable();
            $table->string('title')->nullable();
            $table->string('handle')->nullable();
            $table->integer('status')->default(0)->comment("0= pending , 1= in process , 2= completed , 3= error ");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collection_products');
    }
};
