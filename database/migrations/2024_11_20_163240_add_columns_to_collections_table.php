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
        Schema::table('collections', function (Blueprint $table) {
            $table->string("new_gid")->comment("this is the data received from the collection after exporting to the new store")->nullable();
            $table->string("new_title")->comment("this is the data received from the collection after exporting to the new store")->nullable();
            $table->string("new_handle")->comment("this is the data received from the collection after exporting to the new store")->nullable();
            $table->longText("new_raw_data")->comment("this is the data received from the collection after exporting to the new store")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('collections', function (Blueprint $table) {
            //
        });
    }
};
