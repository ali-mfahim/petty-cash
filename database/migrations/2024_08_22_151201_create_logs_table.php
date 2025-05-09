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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->longText("model_id")->nullable();
            $table->longText("model_name")->nullable();
            $table->longText("data")->nullable();
            $table->longText("description")->nullable();
            $table->integer("status")->nullable()->comment("1=>succes , 2=>error , 3 warning");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
