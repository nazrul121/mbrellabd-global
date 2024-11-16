<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Country;
class CreateCountriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('short_name');
            $table->string('short_code');
            $table->string('phone_code')->nullable();
            $table->string('currency_code')->nullable();

            $table->string('flag')->nullable();
            $table->string('currencySymbol');
            $table->float('currencyValue',12,2);

            $table->string('zone')->nullable();

            $table->enum('is_default',['0','1'])->default(0);
            $table->enum('status',['0','1'])->default(1);
            $table->timestamps();
        });

        Country::create(['name'=>'USA','short_name'=>'usa','phone_code'=>'+1', 'flag'=>'storage/images/flag/usd.jpg', 'currencySymbol'=>'$',
            'currencyValue'=>100.00,'currency_code'=>'UAD','zone'=>'5']);
            
        Country::create(['name'=>'Bangladesh','short_name'=>'BD','phone_code'=>'+88', 'flag'=>'storage/images/flag/bd.png', 'currencySymbol'=>'৳',
            'currencyValue'=>1.00,'currency_code'=>'BDT']); 
       
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('countries');
    }
}
