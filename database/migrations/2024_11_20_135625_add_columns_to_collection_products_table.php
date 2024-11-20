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
        Schema::table('collection_products', function (Blueprint $table) {
            $table->longText("new_product_title")->comment("the is the data of the new product data found from export_store_id")->nullable();
            $table->longText("new_product_handle")->comment("the is the data of the new product data found from export_store_id")->nullable();
            $table->longText("new_product_raw_data")->comment("the is the data of the new product data found from export_store_id")->nullable();
            $table->string("matched_store")->comment("1= matched successful  , 2= match failed , 0 match process in pending , 3 = warning or error ")->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collection_products', function (Blueprint $table) {
            //
        });
    }
};
