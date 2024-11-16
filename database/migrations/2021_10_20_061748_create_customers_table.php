<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries');

            $table->unsignedBigInteger('division_id')->nullable();
            $table->foreign('division_id')->references('id')->on('divisions');

            $table->unsignedBigInteger('district_id');
            $table->foreign('district_id')->references('id')->on('districts');

            $table->unsignedBigInteger('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('cities');

            $table->string('first_name');
            $table->string('last_name');
            $table->string('sex',6)->default('male');
            $table->string('phone',50)->nullable();
            $table->string('photo')->nullable()->default('images/user.jpg');
            $table->string('address')->nullable();
            $table->string('postCode')->nullable();

            $table->float('balance',15,2)->default(0);
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
        Schema::dropIfExists('customers');
    }
}
