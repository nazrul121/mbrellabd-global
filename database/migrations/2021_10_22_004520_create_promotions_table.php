<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('promotion_type_id');
            $table->foreign('promotion_type_id')->references('id')->on('promotion_types');
            $table->string('title');
            $table->string('slug')->nullable();
            $table->string('bg_color',50)->nullable();
            $table->string('text_color',50)->nullable();

            $table->string('photo')->default('images/thumbs_photo.png');
            $table->text('description')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->string('start_time',20);
            $table->string('end_time',20);
            $table->string('expiry_visibility',50)->default('show');

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
        Schema::dropIfExists('promotions');
    }
}
