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
            $table->integer("import_store_id")->nullable()->comment("This column will hold the store from which we are cloning the collections");
            $table->integer("export_store_id")->nullable()->comment("This column will hold the store where we are importing the collections");
            $table->string("gid")->nullable()->comment("this columns depends upon the type of collection , if type is 1 that means this gid is from the collection of import_store_id & if the type is 2 that means this gid if from the export_store_id column store");
            $table->string("clone_from_collection_id")->nullable();
            $table->string("handle")->nullable()->comment("same as GID");
            $table->string("title")->nullable()->comment("same as GID");
            $table->longText("description")->nullable()->comment("same as GID");
            $table->longText("image")->nullable()->comment("same as GID");
            $table->integer("type")->comment("1= you are cloning this collection to another store export_store_id , 2= this collection has been cloned from another store from import_store_id")->nullable();
            $table->string("c_updated_at")->nullable()->comment("same as GID");
            $table->string("sort_order")->nullable()->comment("same as GID");
            $table->longText("raw_data")->nullable()->comment("same as GID");
            $table->integer('status')->default(0)->comment("0 = imported, 1= in process , 2= exported , 3= product linked , 4 = collection product imported , 5 = products matched");
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
