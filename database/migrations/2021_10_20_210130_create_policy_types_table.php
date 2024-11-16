<?php

use App\Models\Policy_type;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePolicyTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('policy_types', function (Blueprint $table) {
            $table->id();
            $table->string('title')->unique();
            $table->string('slug')->unique();
            $table->string('photo')->nullable();
            $table->enum('status',['0','1'])->default(1);

            $table->timestamps();
        });

        Policy_type::create(['title'=>'Return policy', 'slug'=>'return-policy']);
        Policy_type::create(['title'=>'Terms and Conditions', 'slug'=>'terms-and-conditions']);
        Policy_type::create(['title'=>'Privacy policy', 'slug'=>'privacy-policy']);
        Policy_type::create(['title'=>'Cookie policy', 'slug'=>'cookie-policy']);
        Policy_type::create(['title'=>'Delivery policy', 'slug'=>'delivery-policy']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('policy_types');
    }
}
