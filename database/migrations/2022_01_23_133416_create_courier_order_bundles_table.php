<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourierOrderBundlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courier_order_bundles', function (Blueprint $table) {
            $table->id();
            $table->string('bundle_id')->unique();

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('courier_company_id');
            $table->foreign('courier_company_id')->references('id')->on('courier_companies');

            $table->unsignedBigInteger('courier_representative_id')->nullable();
            $table->foreign('courier_representative_id')->references('id')->on('courier_representatives');

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
        Schema::dropIfExists('courier_order_bundles');
    }
}
