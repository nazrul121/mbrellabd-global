<?php

use App\Models\Group;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('display_name')->nullable();
            $table->string('slug')->unique();
            $table->string('photo')->default('images/thumbs_photo.png');
            $table->text('description')->nullable();
            $table->integer('sort_by')->default(0);
            $table->enum('status',['0','1'])->default(1);
            $table->timestamps();
        });
        // $data = array('Men'=>'men', 'Women'=>'women', 'Kids'=>'kids');
        // foreach($data as $key=>$data){
        //     Group::create(['title'=>$key, 'slug'=>$data]);
        // }

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('groups');
    }
}
