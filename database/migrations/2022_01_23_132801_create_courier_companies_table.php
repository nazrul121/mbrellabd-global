<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourierCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courier_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('logo')->default('images/thumbs_photo.png');
            $table->string('location');
            $table->enum('commission_in',['percentage','fix_amount']);
            $table->float('amount',15,2);
            
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
        Schema::dropIfExists('courier_companies');
    }
}
