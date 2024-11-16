<?php

use App\Models\Inner_group;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInnerGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inner_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('group_id')->nullable();
            $table->foreign('group_id')->references('id')->on('groups');
            $table->string('title');
            $table->string('display_name')->nullable();
            $table->string('slug')->unique()->nullable();
            $table->string('photo')->default('images/thumbs_photo.png');
            $table->text('description')->nullable();
            $table->enum('is_top',['0','1'])->default(0);
            $table->integer('sort_by')->default(0);
            $table->enum('status',['0','1'])->default(1);
            $table->timestamps();
        });

        // $data = array('Shirt'=>'shirt', 'Pant'=>'pant', 'Panjabi'=>'panjabi');
        // $i = 1;
        // foreach($data as $key=>$data){
        //     Inner_group::create([
        //     'group_id'=>$i,
        //     'title'=>$key,'slug'=>$data]);
        //     $i++;
        // }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('inner_groups');
    }
}
