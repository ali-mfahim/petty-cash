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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string("logo_black")->nullable();
            $table->string("logo_white")->nullable();
            $table->string("fav_icon")->nullable();
            $table->integer("cron_enable")->nullable()->comment("1= enable, 2= disable");
            $table->string("support_email")->nullable()->comment("1= enable, 2= disable");
            $table->integer('status')->default(1);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
