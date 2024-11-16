<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariationOptionPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variation_option_photos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('variation_id');
            $table->foreign('variation_id')->references('id')->on('variations');

            $table->unsignedBigInteger('variation_option_id');
            $table->foreign('variation_option_id')->references('id')->on('variation_options');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->string('thumbs')->nullable();
            $table->string('photo')->nullable();
            $table->enum('status',['0','1'])->default(1);
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
        Schema::dropIfExists('variation_option_photos');
    }
}
