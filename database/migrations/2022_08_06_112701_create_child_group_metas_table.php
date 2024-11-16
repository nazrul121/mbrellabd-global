<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateChildGroupMetasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('child_group_metas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('child_group_id');
            $table->foreign('child_group_id')->references('id')->on('child_groups');
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
        Schema::dropIfExists('child_group_metas');
    }
}
