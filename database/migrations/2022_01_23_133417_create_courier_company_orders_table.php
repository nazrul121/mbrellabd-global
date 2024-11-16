<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourierCompanyOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courier_company_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->foreign('order_id')->references('id')->on('orders');

            $table->unsignedBigInteger('courier_order_bundle_id');
            $table->foreign('courier_order_bundle_id')->references('id')->on('courier_order_bundles');

            $table->unsignedBigInteger('courier_company_id');
            $table->foreign('courier_company_id')->references('id')->on('courier_companies');

            $table->unsignedBigInteger('courier_zone_id');
            $table->foreign('courier_zone_id')->references('id')->on('courier_zones');

            $table->float('delivery_cost',8,2)->nullable();
            $table->float('return_cost',8,2)->nullable();

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
        Schema::dropIfExists('courier_company_orders');
    }
}
