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
            $table->string("new_gid")->comment("this is the column where gid holds up whose gid column from 
            store 1 matches from gid column of store 2 , Basicaly if we are cloning collections from Druids to Druids VIP , 
            that means import_store_id  is store 1 , and export_store_id is store 2")->nullable();


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
