<?php

use App\Models\Admin;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('first_name');
            $table->string('last_name');
            $table->string('sex',6)->default('male');
            $table->string('position',100);
            $table->string('photo')->nullable()->default('images/user.jpg');
            $table->string('address')->nullable();
            $table->enum('is_super',['0','1'])->default(0);
            $table->enum('has_permission',['0','1'])->default(0);
            $table->enum('status',['0','1'])->default(1);
            $table->timestamps();
        });

        Admin::create([
            'user_id'=>'1', 'first_name'=>'F Name','last_name'=>'L name','position'=>'super-admin','is_super'=>'1'
        ]);
        Admin::create([
            'user_id'=>'2', 'first_name'=>'F Name','last_name'=>'L name','position'=>'admin'
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('admins');
    }
}
