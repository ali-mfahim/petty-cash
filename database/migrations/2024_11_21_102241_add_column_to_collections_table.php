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
            $table->integer("is_exported")->after("status")->default(0);
            $table->integer("is_product_imported")->after("is_exported")->default(0);
            $table->integer("is_product_exported")->after("is_product_imported")->default(0);
            $table->integer("is_product_matched")->after("is_product_exported")->default(0);
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
