<?php

use App\Models\Zone;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZonesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('duration');

            $table->float('delivery_cost',15,2);
            $table->text('description');
            $table->enum('location',['inside','abroad'])->default('inside');
            $table->enum('status',['0','1'])->default(1);
            $table->timestamps();
        });

        Zone::create(['name'=>'Inside Dhaka','duration'=>'5-10 working days','description'=>'Delivery inside dhaka district','delivery_cost'=>'100']);
        Zone::create(['name'=>'Outside Dhaka','duration'=>'10-10 working days','description'=>'Delivery outside of dhaka district','delivery_cost'=>'120']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('zones');
    }
}
