<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Permission;
use App\Models\Permission_group;
use App\Models\Permission_label;

class CreatePermissionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('permission_group_id')->nullable();
            $table->foreign('permission_group_id')->references('id')->on('permission_groups');

            $table->unsignedBigInteger('permission_label_id');
            $table->foreign('permission_label_id')->references('id')->on('permission_labels');

            $table->string('origin')->unique();
            $table->string('name')->unique();
            $table->timestamps();
        });

        $data = ['Product line','Orders','Couriers','Promotions','Page Post','Quick Service','Testimonials','Users','Settings','Payment','Area and Zone','Outlets','Career','Reports','backup'];
        foreach($data as $info){
            $label = Permission_label::create(['title'=>$info]);
            if($label->id==1){
                $groupData = ['Product','Season', 'Groups', 'Highlight','Access labels', 'size chirt','Product variation'];
                foreach($groupData as $data){
                    Permission_group::create(['permission_label_id'=>$label->id,'name'=>$data]);
                }
            }

            if($label->id==2){
                $groupData = ['Order'];
                foreach($groupData as $data){
                    Permission_group::create(['permission_label_id'=>$label->id,'name'=>$data]);
                }
            }

            if($label->id==3){
                $groupData = ['Courier company','Courier representatives','Courier company zone'];
                foreach($groupData as $data){
                    Permission_group::create(['permission_label_id'=>$label->id,'name'=>$data]);
                }
            }

            if($label->id==4){
                $groupData = ['Coupons','Promotions','Invoice discount', 'Banners','Campaigns'];
                foreach($groupData as $data){
                    Permission_group::create(['permission_label_id'=>$label->id,'name'=>$data]);
                }
            }
            if($label->id==5){
                $groupData = ['Videos', 'Slider','Policy','Page post','Blog', 'FAQs'];
                foreach($groupData as $data){
                    Permission_group::create(['permission_label_id'=>$label->id,'name'=>$data]);
                }
            }
            if($label->id==6){
                $groupData = ['Quick Service',];
                foreach($groupData as $data){
                    Permission_group::create(['permission_label_id'=>$label->id,'name'=>$data]);
                }
            }

            if($label->id==7){
                $groupData = ['Testimonial',];
                foreach($groupData as $data){
                    Permission_group::create(['permission_label_id'=>$label->id,'name'=>$data]);
                }
            }

            if($label->id==8){
                $groupData = ['Customer', 'Staffs', 'Suppliers','Adminns'];
                foreach($groupData as $data){
                    Permission_group::create(['permission_label_id'=>$label->id,'name'=>$data]);
                }
            }
            if($label->id==9){
                $groupData = ['Settings','Social Media', 'Currency'];
                foreach($groupData as $data){
                    Permission_group::create(['permission_label_id'=>$label->id,'name'=>$data]);
                }
            }
            if($label->id==10){
                $groupData = ['Payment method','payment type'];
                foreach($groupData as $data){
                    Permission_group::create(['permission_label_id'=>$label->id,'name'=>$data]);
                }
            }
        
            if($label->id==11){
                $groupData = ['Area', 'Zones'];
                foreach($groupData as $data){
                    Permission_group::create(['permission_label_id'=>$label->id,'name'=>$data]);
                }
            }
            if($label->id==12){
                $groupData = ['Outlet'];
                foreach($groupData as $data){
                    Permission_group::create(['permission_label_id'=>$label->id,'name'=>$data]);
                }
            }
            if($label->id==13){
                $groupData = ['Career'];
                foreach($groupData as $data){
                    Permission_group::create(['permission_label_id'=>$label->id,'name'=>$data]);
                }
            }
            if($label->id==14){
                $groupData = ['report'];
                foreach($groupData as $data){
                    Permission_group::create(['permission_label_id'=>$label->id,'name'=>$data]);
                }
            }
            if($label->id==15){
                $groupData = ['backup'];
                foreach($groupData as $data){
                    Permission_group::create(['permission_label_id'=>$label->id,'name'=>$data]);
                }
            }
        }


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('permissions');
    }
}
