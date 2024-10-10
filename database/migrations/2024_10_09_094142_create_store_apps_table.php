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
        Schema::create('store_apps', function (Blueprint $table) {
            $table->id();
            $table->integer('created_by')->nullable();
            $table->integer("store_id")->nullable();
            $table->string("app_name")->nullable();
            $table->string("slug")->nullable();
            $table->string("app_key")->nullable();
            $table->string("app_secret")->nullable();
            $table->string("access_token")->nullable();
            $table->string("api_version")->nullable();
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
        Schema::dropIfExists('store_apps');
    }
};
