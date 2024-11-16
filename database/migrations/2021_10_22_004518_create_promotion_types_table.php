<?php

use App\Models\Promotion_type;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePromotionTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promotion_types', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('name_origin');
            $table->string('photo')->default('images/thumbs_photo.png');
            $table->text('description')->nullable();
            $table->timestamps();
        });

        $datas = array(
            'Flat Sale'=>'group-discount',
            'Bundle Promotion'=>'bundle-promotion',
            'Buy one get one free'=>'buy-one-get-one',
            'Buy and get discount'=>'buy-and-discount',
            'Supplier discount'=>'supplier-discount',
            'Price Changed'=>'price-changed'
        );
        foreach($datas as $key=>$data){
            Promotion_type::create([
                'title'=>$key, 'name_origin'=>$data
            ]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotion_types');
    }
}
