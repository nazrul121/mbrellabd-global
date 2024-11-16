<?php

use App\Models\Payment_type;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_types', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('status',['0','1'])->default(1);
            $table->timestamps();
        });

        Payment_type::create(['title'=>'SSLCOMMERZ']);
        Payment_type::create(['title'=>'Cash On Delivery']);
        Payment_type::create(['title'=>'Bkash']);
        Payment_type::create(['title'=>'Rocket']);
        Payment_type::create(['title'=>'Nagad']);
        Payment_type::create(['title'=>'Check payment']);
        Payment_type::create(['title'=>'Bank payment']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_types');
    }
}
