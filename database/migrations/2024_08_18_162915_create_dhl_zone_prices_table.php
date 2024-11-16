<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDhlZonePricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dhl_zone_prices', function (Blueprint $table) {
            $table->id();
            $table->integer('zone');
            $table->float('kg_from',8,2);
            $table->float('kg_to',8,2);
            $table->float('price',8,2);
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
        Schema::dropIfExists('dhl_zone_prices');
    }
}
