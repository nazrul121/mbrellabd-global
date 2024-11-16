<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('country_id')->default(1);
            $table->foreign('country_id')->references('id')->on('countries');

            $table->integer('google_id')->nullable();
            $table->integer('facebook_id')->nullable();
            $table->unsignedBigInteger('user_type_id');
            $table->foreign('user_type_id')->references('id')->on('user_types');
            $table->string('phone')->unique();
            $table->string('email')->unique()->nullable();
            $table->string('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->enum('status',['1','0'])->default(1);
            $table->timestamps();
        });

        User::create(['user_type_id'=>'1','phone'=>'01749015457','email'=>'super@email.com','password'=>Hash::make('12345678')]);
        User::create(['user_type_id'=>'2','phone'=>'01834167576','email'=>'admin@email.com','password'=>Hash::make('12345678')]);
        User::create(['user_type_id'=>'3','phone'=>'01748548574','email'=>'staff@email.com','password'=>Hash::make('12345678')]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
