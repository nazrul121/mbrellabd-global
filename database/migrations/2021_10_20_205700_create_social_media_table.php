<?php

use App\Models\Social_media;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSocialMediaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_media', function (Blueprint $table) {
            $table->id();
            $table->string('media_name');
            $table->string('media_link');
            $table->string('media_icon')->nullable();
            $table->enum('status',['0','1'])->default(1);
            $table->timestamps();
        });

        Social_media::create(['media_name'=>'Facebook','media_link'=>'!#','media_icon'=>'fab fa-facebook']);
        Social_media::create(['media_name'=>'Youtube','media_link'=>'!#','media_icon'=>'fab fa-youtube']);
        Social_media::create(['media_name'=>'LinkedIn','media_link'=>'!#','media_icon'=>'fab fa-linkedin']);
        Social_media::create(['media_name'=>'Instragram','media_link'=>'!#','media_icon'=>'fab fa-instagram']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_media');
    }
}
