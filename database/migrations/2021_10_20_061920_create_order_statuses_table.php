<?php

use App\Models\Order_status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->string('description')->nullable();
            $table->enum('action',['continue','stop-action']);
            $table->enum('qty_status',['return-qty','general']);
            $table->string('relational_activity',50)->unique()->nullable();
            $table->string('sort_by',50)->nullable();
            $table->timestamps();
        });

        Order_status::create([ 'title'=>'Order placed','action'=>'continue', 'qty_status'=>'general']);
        Order_status::create([ 'title'=>'Order confirmed','action'=>'continue', 'qty_status'=>'general']);
        Order_status::create([ 'title'=>'Processing in Progress','action'=>'continue', 'qty_status'=>'general']);
        Order_status::create([ 'title'=>'Order shipped','action'=>'continue', 'qty_status'=>'general']);
        Order_status::create([ 'title'=>'Refund','action'=>'stop-action', 'qty_status'=>'return-qty']);
        Order_status::create([ 'title'=>'Order Returned','action'=>'stop-action', 'qty_status'=>'return-qty']);
        Order_status::create([ 'title'=>'Order Delivered','action'=>'stop-action', 'qty_status'=>'general']);
        Order_status::create([ 'title'=>'Cancelled by Customer','action'=>'stop-action', 'qty_status'=>'return-qty']);
        Order_status::create([ 'title'=>'Cancelled by Authority','action'=>'stop-action', 'qty_status'=>'return-qty']);


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_statuses');
    }
}
