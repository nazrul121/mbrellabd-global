<?php

use App\Models\Payment_gateway;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentGatewaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_gateways', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('name_origin');
            $table->string('icon',100)->nullable();
            $table->text('description')->nullable();
            $table->enum('status',['0','1'])->default(1);
            $table->timestamps();
        });

        Payment_gateway::create(['name'=>'Cash On Delivery', 'name_origin'=>'cash','icon'=>'fa fa-gift','description'=>'Pay order amount after getting product at your hand']);
        Payment_gateway::create(['name'=>'Pay oneline', 'name_origin'=>'sslcommerz','icon'=>'fa fa-money','description'=>'Pay online via Mobile banking, Internet banking or Bank Account']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_gateways');
    }
}
