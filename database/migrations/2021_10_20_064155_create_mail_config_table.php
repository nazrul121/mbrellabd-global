<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMailConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mail_config', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('server_name');
            $table->string('server_port');
            $table->string('username');
            $table->string('password');
            $table->string('encryption');
            $table->string('send_email');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mail_config');
    }
}
