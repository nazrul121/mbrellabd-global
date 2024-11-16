<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('brand_id');
            $table->foreign('brand_id')->references('id')->on('brands');
            $table->unsignedBigInteger('supplier_id');
            $table->foreign('supplier_id')->references('id')->on('suppliers');

            $table->unsignedBigInteger('size_chirt_id')->nullable();
            $table->foreign('size_chirt_id')->references('id')->on('size_chirts');

            // $table->enum('type',['RMG','Non-RMG']);
            $table->string('title');
            $table->string('design_code')->unique();
            $table->string('design_year');
            $table->string('sku',150)->unique();
            $table->string('barcode',100)->unique();
            $table->string('slug')->unique();
            $table->string('feature_photo')->nullable()->default('images/thumbs_photo.png');
            $table->string('thumbs')->nullable()->default('images/thumbs_photo.png');
            $table->float('net_price',15,2);
            $table->float('sale_price',15,2);
            $table->longText('description');
            $table->string('tags');
            $table->enum('vat_type',['including','excluding']);
            $table->float('vat',8,2);

            $table->integer('qty');
            $table->enum('cod',['0','1'])->default(0);
            $table->enum('portable',['0','1'])->default(0);
            $table->enum('is_group',['0','1'])->default(0);
            $table->enum('newArrival',['0','1'])->default(0);
            $table->string('additional_field')->nullable();
            $table->string('additional_value')->nullable();
            $table->enum('status',['0','1','delete'])->default(1);
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
        Schema::dropIfExists('products');
    }
}
