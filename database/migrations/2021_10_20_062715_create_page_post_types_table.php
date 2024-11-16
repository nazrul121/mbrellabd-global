<?php

use App\Models\Page_post_type;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePagePostTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('page_post_types', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->string('photo')->nullable();
            
            $table->enum('status',['0','1'])->default(1);
            $table->timestamps();
        });

        Page_post_type::create([ 'title'=>'Contact us', 'slug'=>'contact-us']);
        Page_post_type::create([ 'title'=>'About us', 'slug'=>'about-us']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('page_post_types');
    }
}
