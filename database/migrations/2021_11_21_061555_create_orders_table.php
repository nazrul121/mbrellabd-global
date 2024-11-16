<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_id',100)->unique();

            $table->unsignedBigInteger('customer_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers');

            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries');
            
            $table->float('currency_value',15,2);

            $table->unsignedBigInteger('payment_geteway_id');
            $table->foreign('payment_geteway_id')->references('id')->on('payment_gateways');

            $table->unsignedBigInteger('zone_id')->nullable();
            $table->foreign('zone_id')->references('id')->on('zones');

            $table->unsignedBigInteger('shipping_address_id');
            $table->foreign('shipping_address_id')->references('id')->on('shipping_addresses');

            $table->unsignedBigInteger('order_status_id')->default(1);
            $table->foreign('order_status_id')->references('id')->on('order_statuses');

            $table->unsignedBigInteger('coupon_id')->nullable();
            $table->foreign('coupon_id')->references('id')->on('coupons');

            $table->string('shippingCostFrom',50);


            $table->integer('total_items');
            $table->float('total_cost',15,2);
            $table->float('invoice_discount',15,2)->default(0)->nullable();
            $table->string('transaction_id',50);
            $table->float('shipping_cost',15,2);
            $table->float('discount',15,2)->default(0);
            $table->float('vat',15,2)->default(0);
            $table->text('note')->nullable();
            $table->date('order_date')->default(Carbon::now());
            $table->string('ref',30)->default('self');

            $table->string('division')->nullable();
            $table->string('district')->nullable();
            $table->string('city')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->string('address')->nullable();
            $table->string('postCode')->nullable();

            $table->string('ship_division')->nullable();
            $table->string('ship_district')->nullable();
            $table->string('ship_city')->nullable();
            $table->string('ship_first_name')->nullable();
            $table->string('ship_last_name')->nullable();
            $table->string('ship_phone')->nullable();
            $table->string('ship_email')->nullable();
            $table->string('ship_address')->nullable();
            $table->string('ship_postCode')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users');

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
        Schema::dropIfExists('orders');
    }
}
