<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\General_info;
class CreateGeneralInfosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_infos', function (Blueprint $table) {
            $table->id();
            $table->string('field')->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        $datas = array(
            'system_title'=>'Mbrella',
            'system_slogan'=>'be comfortable',
            'system_domain'=>'mbrella.com.bd',
            'system_email'=>'info@mbrella.com.bd',
            'system_helpline'=>'01701101010101',
            'system_phone'=>'01749015457',
            'system_fax'=>'xxxxxx',
            'reg_no'=>'123345',
            'invoice_logo'=>'images/invoice-logo.png',
            'header_logo'=>'images/header-logo.png',
            'footer_logo'=>'images/footer-logo.png',
            'favicon'=>'images/favicon.ico',
            'office_address'=>'Plot-20 Road-04,Section-7,Pallabi, Dhaka 1216',
            'system_currency'=>'$',
            'system_description'=>'Mbrella is a clothing & lifestyle retail brand based in Bangladesh by countrys leading RMG manufacturer Mondol Group.',
            'watermark_logo'=>'images/watermark_logo.png',
        );
        foreach($datas as $key=>$data){
            General_info::create(['field'=>$key,'value'=>$data]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('general_infos');
    }
}
