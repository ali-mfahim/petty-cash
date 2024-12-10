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
        Schema::create('monthly_calculations', function (Blueprint $table) {
            $table->id();
            $table->string("link_id")->nullable();
            $table->string("form_id")->nullable();
            $table->string("user_id")->nullable();
            $table->date("date")->nullable();
            $table->string("month")->nullable();
            $table->string("year")->nullable();
            $table->string("amount")->nullable();
            $table->string("type")->comment("1=debit , 2= credit ")->nullable();
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
        Schema::dropIfExists('monthly_calculations');
    }
};
