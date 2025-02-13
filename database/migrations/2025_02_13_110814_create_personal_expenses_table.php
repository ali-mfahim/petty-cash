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
        Schema::create('personal_expenses', function (Blueprint $table) {
            $table->id();
            $table->integer('paid_by')->nullable();
            $table->integer('submit_by')->nullable();
            $table->integer('user_id')->nullable();
            $table->string('title')->nullable();
            $table->string('amount')->nullable();
            $table->longText('description')->nullable();
            $table->string('date')->nullable();
            $table->integer('type')->nullable()->comment("1=credit , 2=debit");
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
        Schema::dropIfExists('personal_expenses');
    }
};
