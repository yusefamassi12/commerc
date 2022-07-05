<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_details', function (Blueprint $table) {
            $table->id();
            $table->string('slug');
            $table->text('descrption');
            $table->enum('stockStatus',['inStock','outofstock']);
            $table->unsignedInteger('quantity')->default('10');
            $table->bigInteger('categoryid')->unsigned()->nullable();
            $table->foreign('categoryid')->references('id')->on('categories')->onDelete('cascade');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('_product_details');
    }
};
