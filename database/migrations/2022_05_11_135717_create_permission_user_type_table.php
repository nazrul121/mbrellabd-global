<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePermissionUserTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permission_user_type', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_type_id');
            $table->foreign('user_type_id')->references('id')->on('user_types');

            $table->unsignedBigInteger('permission_label_id');
            $table->foreign('permission_label_id')->references('id')->on('permission_labels');

            $table->unsignedBigInteger('permission_id')->nullable();
            $table->foreign('permission_id')->references('id')->on('permissions');

            $table->enum('status',['0','1'])->default(1);
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
        Schema::dropIfExists('permission_user_type');
    }
}
