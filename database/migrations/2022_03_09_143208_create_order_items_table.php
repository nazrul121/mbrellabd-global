   <?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('order_id')->nullable();
            $table->foreign('order_id')->references('id')->on('orders');

            $table->unsignedBigInteger('campaign_id')->nullable();
            $table->foreign('campaign_id')->references('id')->on('campaigns');

            $table->unsignedBigInteger('promotion_id')->nullable();
            $table->foreign('promotion_id')->references('id')->on('promotions');

            $table->unsignedBigInteger('product_id');
            $table->foreign('product_id')->references('id')->on('products');

            $table->unsignedBigInteger('variation_option_id')->nullable();
            $table->foreign('variation_option_id')->references('id')->on('variation_options');

            $table->unsignedBigInteger('product_combination_id')->nullable();
            $table->foreign('product_combination_id')->references('id')->on('product_combinations');
            $table->integer('qty')->default(1);

            $table->string('outlet_customer_id')->nullable();
            $table->float('outlet_percent',15,2)->nullable();
            $table->float('outlet_percent_amt',15,2)->nullable();
            $table->string('outlet_category',25)->nullable();

            $table->float('net_price',15,2);
            $table->float('sale_price',15,2);
            $table->float('net_weight',15,2)->nullable();
            $table->float('gross_weight',15,2)->nullable();
            $table->float('discount_price',15,2)->nullable();
            $table->float('vat',15,2)->default(0);
            $table->string('vat_type');
            $table->string('status')->default('placed');

            $table->unsignedBigInteger('user_id')->nullable()->comment('who made the order');
            $table->foreign('user_id')->references('id')->on('users');

            $table->string('created_by')->nullable();
            
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
        Schema::dropIfExists('order_items');
    }
}
