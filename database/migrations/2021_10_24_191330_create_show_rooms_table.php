<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShowRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('show_rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('district_id');
            $table->foreign('district_id')->references('id')->on('districts');
            $table->string('title');
            $table->string('photo')->default('images/thumbs_photo.jpg');
            $table->string('phone');
            $table->text('location');
            $table->text('embed_code')->nullable();
            $table->text('description');
            $table->enum('status',['1','0'])->default(1);
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
        Schema::dropIfExists('show_rooms');
    }
}
