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
        Schema::create('user_monthly_report_statuses', function (Blueprint $table) {
            $table->id();
            $table->string("month")->nullable();
            $table->string("year")->nullable();
            $table->string("month_year")->nullable();
            $table->string("user_id")->nullable();
            $table->integer("transaction_user_id")->nullable()->comment("user id of the person from the user received or paid the amount to sattle");
            $table->integer('transaction_type')->default(0)->comment("0=pending, 1= paid , 2= received ");
            $table->integer('status')->default(0)->comment("0=pending , 1= sattled , 2= not sattled ");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_monthly_report_statuses');
    }
};
