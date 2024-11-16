<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User_type;
class CreateUserTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_types', function (Blueprint $table) {
            $table->id();
            $table->string('title',30);
            $table->text('description')->nullable();
            $table->enum('status',['0','1'])->default('1');
            $table->timestamps();
        });

        $datas = array('superAdmin','admin','staff','customer');

        foreach($datas as $data){
            User_type::create(['title'=>$data]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_types');
    }
}
