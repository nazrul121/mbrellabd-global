<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInnerGroupMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inner_group_metas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('inner_group_id');
            $table->foreign('inner_group_id')->references('id')->on('inner_groups');
            $table->string('meta_type');
            $table->string('meta_content');
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
        Schema::dropIfExists('inner_group_metas');
    }
}
