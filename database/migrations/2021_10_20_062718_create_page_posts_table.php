<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_posts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('page_post_type_id');
            $table->foreign('page_post_type_id')->references('id')->on('page_post_types');
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('photo')->nullable();
            $table->longText('description');

            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            
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
        Schema::dropIfExists('page_posts');
    }
}
