<?php

use App\Models\Child_group;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChildGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('child_groups', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inner_group_id')->nullable();
            $table->foreign('inner_group_id')->references('id')->on('inner_groups');
            $table->string('title');
            $table->string('display_name')->nullable();
            $table->string('slug')->unique();
            $table->string('photo')->default('images/thumbs_photo.png');
            $table->text('description')->nullable();
            $table->integer('sort_by')->default(0);
            $table->enum('status',['0','1'])->default(1);
            $table->timestamps();
        });

        // $data = array('Party Shirt'=>'party-shirt', 'Formal Pant'=>'formal-pant', 'Eid Panjabi'=>'eid-panjabi');
        // $i=1;
        // foreach($data as $key=>$data){
        //     Child_group::create([
        //     'inner_group_id'=>$i,
        //     'title'=>$key,'slug'=>$data]); $i++;
        // }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('child_groups');
    }
}
