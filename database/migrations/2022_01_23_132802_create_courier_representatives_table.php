<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourierRepresentativesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courier_representatives', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('courier_company_id');
            $table->foreign('courier_company_id')->references('id')->on('courier_companies');
            $table->string('name');
            $table->string('phone')->unique();
            $table->string('photo')->default('images/user.jpg');
            $table->text('address');
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
        Schema::dropIfExists('courier_representatives');
    }
}
