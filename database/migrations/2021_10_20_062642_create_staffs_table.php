<?php

use App\Models\Staff;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staffs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('staff_type_id');
            $table->foreign('staff_type_id')->references('id')->on('staff_types');

            $table->string('first_name',100);
            $table->string('last_name',100);
            $table->string('sex',6)->default('male');
            $table->string('position',120);
            $table->float('salary',18,2);
            $table->string('photo')->nullable()->default('images/user.jpg');
            $table->string('address');
            $table->string('post_code');
            $table->enum('status',['0','1'])->default(1);
            $table->timestamps();
        });
        Staff::create([
            'user_id'=>'3', 'staff_type_id'=>1,'first_name'=>'F Name','last_name'=>'L name','position'=>'supern',
            'salary'=>100, 'address'=>'savar', 'post_code'=>'123'
        ]);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staffs');
    }
}
