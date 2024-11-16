<?php

use App\Models\Division;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDivisionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('divisions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_id');
            $table->foreign('country_id')->references('id')->on('countries');

            $table->string('name');
            $table->string('url')->nullable();
            $table->enum('status',['1','0'])->default(1);
            $table->timestamps();
        });

        $divisions = array(
            array('name'=> 'Chattagram'),
            array('name'=> 'Rajshahi'),
            array('name'=> 'Khulna'),
            array('name'=> 'Barisal'),
            array('name'=> 'Sylhet'),
            array('name'=> 'Dhaka'),
            array('name'=> 'Rangpur'),
            array('name'=> 'Mymensingh')
        );

        foreach($divisions as $dis){
        //    echo $dis['id'].'\n';
            Division::create([ 'country_id'=>'2', 'name'=>$dis['name'] ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('divisions');
    }
}
