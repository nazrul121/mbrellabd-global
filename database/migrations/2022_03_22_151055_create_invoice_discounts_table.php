<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoiceDiscountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_discounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');

            $table->enum('type',['general','free-delivery','porduct'])->default('general');
            $table->string('title');
            $table->integer('min_order_amount');
            $table->string('discount_in',30)->nullable();
            $table->string('discount_value',30)->nullable();

            $table->unsignedBigInteger('product_id')->nullable();
            $table->foreign('product_id')->references('id')->on('products');

            $table->string('photo')->default('images/thumbs_photo.png');
            $table->date('start_date');
            $table->date('end_date');
            $table->string('start_time',20);
            $table->string('end_time',20);
            $table->enum('status',['0','1'])->default('1');

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
        Schema::dropIfExists('invoice_discounts');
    }
}
