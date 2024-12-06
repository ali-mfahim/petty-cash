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
        Schema::create('payment_forms', function (Blueprint $table) {
            $table->id();
            $table->integer('submit_by')->nullable()->comment("User ID");
            $table->string('title')->nullable();
            $table->longText('description')->nullable();
            $table->string('amount')->nullable();
            $table->longText('divided_in')->nullable()->comment("Ids of the users in which the amount is being divided");
            $table->longText('document')->nullable();
            $table->string('date')->nullable();
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
        Schema::dropIfExists('payment_forms');
    }
};
