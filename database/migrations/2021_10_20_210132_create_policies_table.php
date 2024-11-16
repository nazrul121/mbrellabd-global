<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePoliciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('policies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('policy_type_id');
            $table->foreign('policy_type_id')->references('id')->on('policy_types');
            $table->string('title');
            $table->string('photo')->nullable();
            $table->longText('description');

            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            
            $table->enum('visibility',['0','1'])->default(1);
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
        Schema::dropIfExists('policies');
    }
}
