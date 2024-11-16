<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourierZonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courier_zones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('courier_company_id');
            $table->foreign('courier_company_id')->references('id')->on('courier_companies');

            $table->string('name');
            $table->string('duration');
            $table->float('delivery_cost',15,2);
            $table->float('return_cost',15,2);
            
            $table->text('description')->nullable();
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
        Schema::dropIfExists('courier_zones');
    }
}
