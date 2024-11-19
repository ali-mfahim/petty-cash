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
        Schema::create('collections', function (Blueprint $table) {
            $table->id();
            $table->integer("import_store_id")->nullable();
            $table->integer("export_store_id")->nullable();
            $table->string("gid")->nullable();
            $table->string("clone_from_collection_id")->nullable();
            $table->string("handle")->nullable();
            $table->string("title")->nullable();
            $table->longText("description")->nullable();
            $table->longText("image")->nullable();
            $table->integer("type")->comment("1=import , 2=export")->nullable();
            $table->string("c_updated_at")->nullable();
            $table->string("sort_order")->nullable();
            $table->longText("raw_data")->nullable();
            $table->integer('status')->default(0)->comment("0 = imported, 1= in process , 2= exported , 3= product linked , 4 = collection product imported");
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collections');
    }
};
