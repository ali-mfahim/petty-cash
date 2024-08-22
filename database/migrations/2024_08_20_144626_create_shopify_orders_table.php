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
        Schema::create('shopify_orders', function (Blueprint $table) {
            $table->id();
            $table->string("url")->nullable();
            $table->string("shopify_order_id")->nullable();
            $table->longText("shopify_order_gid")->nullable();
            $table->longText("raw_data")->nullable();
            $table->longText("tags")->nullable();
            $table->longText("response_data")->nullable();
            $table->integer("status")->nullable()->comment("0 => Pending , 1 = In Process , 2 = Process Completed");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shopify_orders');
    }
};
