<?php

namespace App\Http\Controllers\superAdmin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Child_group;
use App\Models\Child_group_product;
use App\Models\City;
use App\Models\City_zone;
use App\Models\District;
use App\Models\Group;
use App\Models\Group_product;
use App\Models\Inner_group;
use App\Models\Inner_group_product;
use App\Models\Policy;
use App\Models\Policy_type;
use App\Models\Product;
use App\Models\Product_combination;
use App\Models\Product_variation_option;
use App\Models\Quick_service;
use App\Models\Show_room;
use App\Models\Slider;
use App\Models\Order;
use App\Models\Social_media;
use App\Models\Customer;
use App\Models\Product_promotion;
use App\Models\Order_item;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
//user this intervention image library to resize/crop image
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;

use App\Models\Variation;
use App\Models\Variation_option;
use App\Models\Variation_option_photo;
use DB;
use Illuminate\Support\Facades\Http;
class DashboardController extends Controller
{

    public function index(){
        // $this->reArrange_orders();
        // $this->get_districts();
        // $this->get_cities();

        // $this->get_groups();
        // $this->get_inner_groups();
        // $this->get_child_groups();

        // $this->get_products();
        // $this->get_group_products();
        // $this->get_inner_group_products();
        // $this->get_child_group_products();

        // $this->get_variations();

        // $this->product_variants();
        // $this->product_combinations();
        // $this->get_color_product();


        // $this->city_zone();
        // $this->sliders();
        // $this->policies();
        // $this->policy_types();
        // $this->show_rooms();
        // $this->social_media();
        // $this->quick_services();

        //$this->updateCustomerPhoneFormUserTable();
        //$this->updateOrderPhoneIsNull();
        // $this->reArrangeOrderItemPromotion();
        return view('common.includes.dashboard');
    }


    function reArrangeOrderItemPromotion(){
        echo Order_item::count().'<br/>';
        $order_items = Order_item::all();
        // dd($order_items);
       
        $orderNum = 1;
        foreach($order_items as $row){
            $disPercent = ((($row->sale_price - $row->discount_price) / $row->sale_price) * 100);
            if($row->promotion_id==null && $disPercent>0 && $row->order->order_status_id==7){
                // $nearestOffer = Product_promotion::where('discount_in','percent')->orderByRaw('ABS(discount_value - ?) ASC', [$disPercent])->first();
                $nearestOffer = Product_promotion::where('discount_in', 'percent')
                ->whereDate('created_at', '<=', $row->order->order_date) // Filter by date
                ->orderByRaw('ABS(discount_value - ?) ASC', [$disPercent]) // Nearest discount
                ->first();
    
                if($nearestOffer->discount_value==$disPercent){
                    $color = 'green';
                }else $color = 'red';

                echo '<p style="color:'.$color.'">'.$orderNum.'. no promo, but '.$disPercent.'% dis. order-item id: '.$row->id.', 
                order-date='.$row->order->order_date.' <a target="_blank" href="'.route('print-invoice',$row->order->transaction_id).'">Invoice</a>, 
                Nearist: '.$nearestOffer->promotion_id.', '.$nearestOffer->discount_value.'% ,promo-date='.$nearestOffer->promotion->created_at.' </p>';
                $orderNum++;

            }
            
            // if($row->promotion_id!=null){
            //     // echo $disPercent.'% = '.$row->promotion_id.'<br/>';
            //     // echo 'promo ID: '.$row->promotion_id.', Product id: '.$row->product_id.'<br/>';
            //     $product_promotion = Product_promotion::where(['promotion_id'=>$row->promotion_id, 'product_id'=>$row->product_id]);
            //     if($product_promotion->count()>1) dd($product_promotion->get());

            //     if($product_promotion->count() >0 ){
            //         if($product_promotion->count()==1 && $disPercent>0){

            //             //echo 'note: '.$row->product_id.'<br/>';
            //             // $product_promotion = Product_promotion::where([$row->promotion_id]);
            //             // if($product_promotion){
            //             //     $checkOrder = Order_item::where(['product_id'=>$product_promotion->product_id, 'promotion_id'=>$product_promotion->promotion_id]);
            //             // }
            //         }
            //     }
            // }
        }
        exit;
    }

    function updateCustomerPhoneFormUserTable(){
        $customers = Customer::where('phone','=',null)->get();
        foreach($customers as $customer){
            $customer->update(['phone'=>$customer->user->phone]);
        }
    }

    function updateOrderPhoneIsNull(){
        $orders = Order::where('phone','=',null)->get();

        foreach($orders as $order){
            if($order->shipping_address_id !=null){
                $order->update([
                    "ship_division" =>$order->shipping_address->division->name,
                    "ship_district" =>$order->shipping_address->district->name,
                    "ship_city" =>$order->shipping_address->city->name,
                    "ship_first_name" =>$order->shipping_address->fname,
                    "ship_last_name" =>$order->shipping_address->lname,
                    "ship_phone" =>$order->shipping_address->phone,
                    "ship_email" =>$order->shipping_address->email,
                    "ship_address" =>$order->shipping_address->address,
                ]);
            }
            if($order->customer_id !=null){
               
                $order->update([
                    "division" =>$order->customer->division->name,
                    "district" =>$order->customer->district->name,
                    "city" =>$order->customer->city->name,
                    "first_name" =>$order->customer->fname,
                    "last_name" =>$order->customer->lname,
                    "phone" =>$order->customer->phone,
                    "email" =>$order->customer->email,
                    "address" =>$order->customer->address,
                ]);
            }
        }
    }
    
    function reArrange_orders(){
        $orders = Order::offset(12000)->limit(1000)->get();
       
        foreach($orders as $order){
            $customer = $order->customer;
            if($customer !=null){
                if($order->customer->district!=null && $order->customer->city !=null){
                    if($order->customer->division==null){
                        $divId = \DB::table('districts')->where('id',$order->customer->district_id)->pluck('division_id')->first();
                        $div = \DB::table('divisions')->where('id',$divId)->pluck('name')->first();
                    }else $div = $order->customer->division->name;
    
                    $data = [
                        'division'=>$div,
                        'district'=>$order->customer->district->name,
                        'city'=>$order->customer->city->name,
                        'first_name'=> $order->customer->first_name,
                        'last_name'=> $order->customer->last_name,
                        'phone'=>$order->customer->phone,
                        'email'=>$order->customer->email,
                        'address'=>$order->customer->address,
                    ];
                    $order->update($data);
                }
            }

            $shipping_address = $order->shipping_address;
            if($shipping_address !=null){
                if($order->customer->district!=null && $order->customer->city !=null){
                    if($order->shipping_address->division==null){
                        $divId = \DB::table('districts')->where('id',$order->shipping_address->district_id)->pluck('division_id')->first();
                        $div = \DB::table('divisions')->where('id',$divId)->pluck('name')->first();
                    }else $div = $order->shipping_address->division->name;
    
                    $data = [
                        'ship_division'=>$div,
                        'ship_district'=>$order->shipping_address->district->name,
                        'ship_city'=>$order->shipping_address->city->name,
                        'ship_first_name'=> $order->shipping_address->fname,
                        'ship_last_name'=> $order->shipping_address->lname,
                        'ship_phone'=>$order->shipping_address->phone,
                        'ship_email'=>$order->shipping_address->email,
                        'ship_address'=>$order->shipping_address->address,
                    ];
                    $order->update($data);
                }
            }
        }
    }


    public function profile(){
        return view('superAdmin.profile.index');
    }

    public function update(Request $request){
        Session::flash('message', 'yes'); Session::flash('alert', 'Validation error! Please check the form');
        $data = $this->fields();
        $admin = Admin::where('id',Auth::user()->admin->id)->update($data);

        $this->storeImage($admin,'update');

        Session::flash('success', 'Profile information has been updated successfully');
        return back();
    }

    private function fields($id=null){
        return request()->validate([
            'first_name'=>'required',
            'last_name'=>'required',
            'sex'=>'required',
            'photo'=>'sometimes|nullable|image',
            'position'=>'required',
            'address'=>'sometimes|nullable',
        ]);
    }

    private function storeImage($admin){
        if (request()->has('photo')) {

            $fieldFile = request()->photo;
            $mime= $fieldFile->getClientOriginalExtension();
            $imageName = request()->first_name.'-'.Auth::user()->admin->id.'.'.$mime;
            $image = Image::make($fieldFile)->fit(300, 300);
            Storage::disk('public')->put("images/user/admin/".$imageName, (string) $image->encode());

           $admin->update(['photo'=>'images/'.$imageName]);
        }
    }


    //testing for coping data form database
    function get_variations(){
        $types = ['colors','sizes','fabrics','types','fittings','features','style_sizes'];
        foreach($types as $type){
            $variations = Http::get('https://mbrellabd.com/get-data/nz-test/'.$type);
            // dd( $variations->object() );
            if($variations->object() !=null){
                foreach($variations->object() as $v){
                    $checkV = Variation::where('origin',$type);
                    if($checkV->count() <1){
                        $variation = Variation::create([
                            'title'=>ucfirst($type),  'origin'=>strtolower($type)
                        ]);
                    }else $variation = $checkV->first();

                    $checkVO = Variation_option::where( [ 'variation_id'=>$variation->id, 'title'=> $v->title ]);

                    if($checkVO->count() <1){
                        $check = Variation_option::where(['origin'=>str_replace(' ','-', strtolower($v->title) )]);
                        if($check->count() >0){
                            $origin = str_replace(' ','-', strtolower($v->title) ).'-'.$check->count();
                        }else $origin = str_replace(' ','-', strtolower($v->title) );
                        Variation_option::create([
                            'variation_id'=>$variation->id,
                            'title'=>$v->title,
                            'origin'=>$origin,
                            'code'=>$v->code,
                        ]);
                    }else{

                    }
                }
            }
        }
        // dd('done');
    }
    function get_products(){
        $currency = DB::table('currencies')->where('name','USD')->first();
        $response = Http::get('https://mbrellabd.com/get-data/nz-test/products');
        foreach($response->object() as $key=>$product){
            echo $key.'. '.$product->title.'<br/>';
            $data = [
                'id'=>$product->id,
                'brand_id'=>$product->brand_id,
                'supplier_id'=>$product->supplier_id ,
                //$product->size_chirt_id ,
                'title'=>$product->title,
                'design_code'=>$product->design_code,
                'design_year'=>$product->design_year,
                'sku'=>$product->sku ,
                'barcode'=>$product->barcode ,
                'slug'=>$product->slug,
                'feature_photo'=>$product->feature_photo,
                'thumbs'=>$product->thumbs,
                'net_price'=>round($product->net_price * $currency->value),
                'sale_price'=>round($product->sale_price * $currency->value),
                'description'=>$product->description,
                'tags'=>$product->tags,
                'vat_type'=>$product->vat_type,
                'vat'=>$product->vat,
                'qty'=>$product->qty,
                'cod'=>$product->cod,
                'portable'=>$product->portable,
                'is_group'=>$product->is_group,
                'additional_field'=>$product->additional_field,
                'additional_value'=>$product->additional_value,
                'status'=>$product->status
            ];
            $check = Product::where(['id'=>$product->id]);
            if($check->count() <1){
                $data[ 'size_chirt_id'] = null;
                Product::create($data);
            }else {
                Product::where('id',$product->id)->update([
                    'net_price'=>round($product->net_price * $currency->value),
                    'sale_price'=>round($product->sale_price * $currency->value),
                ]);
            }

        }
    }

    function get_group_products(){
        $response = Http::get('https://mbrellabd.com/get-data/nz-test/group_product');
        foreach($response->object() as $key=>$row){
            $data = [
                'id'=>$row->id, 'group_id'=>$row->group_id,
                'product_id'=>$row->product_id, 'status'=>$row->status
            ];

            $check = Group_product::where(['id'=>$row->id]);
            if($check->count() <1){
                Group_product::create($data);
            }else $check->update($data);
        }
    }
    function get_inner_group_products(){
        $response = Http::get('https://mbrellabd.com/get-data/nz-test/inner_group_product');
        foreach($response->object() as $key=>$row){
            $data = [
                'id'=>$row->id, 'group_id'=>$row->group_id, 'inner_group_id'=>$row->inner_group_id,
                'product_id'=>$row->product_id, 'status'=>$row->status
            ];

            $check = Inner_group_product::where(['id'=>$row->id]);
            if($check->count() <1){
                Inner_group_product::create($data);
            }else $check->update($data);
        }
    }
    function get_child_group_products(){
        $response = Http::get('https://mbrellabd.com/get-data/nz-test/child_group_product');
        foreach($response->object() as $key=>$row){
            $data = [
                'id'=>$row->id, 'group_id'=>$row->group_id, 'inner_group_id'=>$row->inner_group_id,
                'child_group_id'=>$row->child_group_id, 'product_id'=>$row->product_id, 'status'=>$row->status
            ];

            $check = Child_group_product::where(['id'=>$row->id]);
            if($check->count() <1){
                Child_group_product::create($data);
            }else $check->update($data);
        }
    }




    function get_districts(){
        $response = Http::get('https://mbrellabd.com/get-data/nz-test/districts');
        foreach($response->object() as $key=>$row){
            $data = [
                'id'=>$row->id, 'division_id'=>$row->division_id,
                'name'=>$row->name ,  'url'=>$row->url ,
                'delivery_cost'=>$row->delivery_cost,  'status'=>$row->status
            ];

            $check = District::where(['id'=>$row->id]);
            if($check->count() <1){
                District::create($data);
            }else $check->update($data);
        }
    }
    function get_cities(){
        $response = Http::get('https://mbrellabd.com/get-data/nz-test/cities');
        foreach($response->object() as $key=>$row){
            $data = [
                'id'=>$row->id, 'district_id'=>$row->district_id,
                'name'=>$row->name , 'url'=>$row->url, 'status'=>$row->status
            ];
            $check = City::where(['id'=>$row->id]);
            if($check->count() <1){
                City::create($data);
            }else $check->update($data);
        }
    }
    function get_groups(){
        $response = Http::get('https://mbrellabd.com/get-data/nz-test/groups');
        foreach($response->object() as $key=>$row){
            $data = [
                'id'=>$row->id, 'title'=>$row->title,  'slug'=>$row->slug , 'photo'=>$row->photo,
                'description'=>$row->description, 'sort_by'=>$row->sort_by,'status'=>$row->status
            ];
            $check = Group::where(['id'=>$row->id]);
            if($check->count() <1){
                Group::create($data);
            }else $check->update($data);
        }
    }
    function get_inner_groups(){
        $response = Http::get('https://mbrellabd.com/get-data/nz-test/inner_groups');
        foreach($response->object() as $key=>$row){
            $data = [
                'id'=>$row->id, 'group_id'=>$row->group_id, 'title'=>$row->title,  'slug'=>$row->slug , 'photo'=>$row->photo,
                'description'=>$row->description, 'is_top'=>$row->is_top, 'sort_by'=>$row->sort_by,'status'=>$row->status
            ];
            $check = Inner_group::where(['id'=>$row->id]);
            if($check->count() <1){
                Inner_group::create($data);
            }else $check->update($data);
        }
    }
    function get_child_groups(){
        $response = Http::get('https://mbrellabd.com/get-data/nz-test/child_groups');
        foreach($response->object() as $key=>$row){
            $data = [
                'id'=>$row->id, 'inner_group_id'=>$row->inner_group_id, 'title'=>$row->title,  'slug'=>$row->slug , 'photo'=>$row->photo,
                'description'=>$row->description, 'sort_by'=>$row->sort_by,'status'=>$row->status
            ];
            $check = Child_group::where(['id'=>$row->id]);
            if($check->count() <1){
                Child_group::create($data);
            }else $check->update($data);
        }
    }


    function product_variants(){
        // $response = Http::get('https://mbrellabd.com/get-data/nz-test/product_variants');
        // dd($response->object());
        $color_id= $size_id = $fabric_id=$feature_id = '';
        $color_variation_id= $size_variation_id = $fabric_variation_id=$feature_variation_id = '';
        $product_variants = DB::table('product_variants')->get();

        foreach($product_variants as $key=>$row){

            if($row->color_id !=null){
                $color_variation_id = 1;
                $color_id = $row->color_id;
                $color_title = \DB::table('colors')->where('id',$color_id)->pluck('title')->first();

                $origin = DB::table('variation_options')->where(['variation_id'=>$color_variation_id,'title'=>$color_title])->pluck('origin')->first();

                $color_variation_option_id = Variation_option::where(['variation_id'=> $color_variation_id,'origin'=>$origin])->pluck('id')->first();

                $data = [ 'product_id'=>$row->product_id,'variation_id'=> $color_variation_id,
                    'variation_option_id'=> $color_variation_option_id,
                ];
                $check = Product_variation_option::where($data);
                if($check->count() <1){
                    Product_variation_option::create($data);
                }else $check->update($data);
            }else $color_id = $color_title = '';

            if($row->size_id !=null){
                $size_variation_id = 2;
                $size_id = $row->size_id;
                $size_title = \DB::table('sizes')->where('id',$size_id)->pluck('title')->first();

                $origin = DB::table('variation_options')->where(['variation_id'=>$size_variation_id,'title'=>$size_title])->pluck('origin')->first();

                $size_variation_option_id = Variation_option::where(['variation_id'=>$size_variation_id,'origin'=>$origin])->pluck('id')->first();

                $data = [ 'product_id'=>$row->product_id,'variation_id'=>$size_variation_id,
                    'variation_option_id'=> $size_variation_option_id,
                ];

                $check = Product_variation_option::where($data);
                if($check->count() <1){
                    Product_variation_option::create($data);
                }else $check->update($data);

            }else $size_id = $size_title = '';

            if($row->fabric_id !=null){
                $fabric_variation_id = 3;
                $fabric_id = $row->fabric_id;
                $fabric_title = \DB::table('fabrics')->where('id',$fabric_id)->pluck('title')->first();

                $origin = DB::table('variation_options')->where(['variation_id'=>$fabric_variation_id,'title'=>$fabric_title])->pluck('origin')->first();

                $fabric_variation_option_id = Variation_option::where(['variation_id'=>$fabric_variation_id,'origin'=>$origin])->pluck('id')->first();

                $data = [ 'product_id'=>$row->product_id,'variation_id'=>$fabric_variation_id,
                    'variation_option_id'=> $fabric_variation_option_id,
                ];

                $check = Product_variation_option::where($data);
                if($check->count() <1){
                    Product_variation_option::create($data);
                }else $check->update($data);

            }else $fabric_id = $fabric_title= $fabric_variation_id = $fabric_variation_option_id = '';

            if($row->type_id !=null){
                $type_variation_id = 4;
                $type_id = $row->type_id;
                $type_title = \DB::table('types')->where('id',$type_id)->pluck('title')->first();

                $origin = DB::table('variation_options')->where(['variation_id'=>$type_variation_id,'title'=>$type_title])->pluck('origin')->first();

                $type_variation_option_id = Variation_option::where(['variation_id'=>$type_variation_id,'origin'=>$origin])->pluck('id')->first();

                $data = [ 'product_id'=>$row->product_id,'variation_id'=>$type_variation_id,
                    'variation_option_id'=> $type_variation_option_id,
                ];

                $check = Product_variation_option::where($data);
                if($check->count() <1){
                    Product_variation_option::create($data);
                }else $check->update($data);

            }else $type_id = $type_title= $type_variation_id = $type_variation_option_id = '';

            if($row->feature_id !=null){
                $feature_variation_id = 5;
                $feature_id = $row->feature_id;
                $feature_title = \DB::table('features')->where('id',$feature_id)->pluck('title')->first();

                $origin = DB::table('variation_options')->where(['variation_id'=>$feature_variation_id,'title'=>$feature_title])->pluck('origin')->first();

                $feature_variation_option_id = Variation_option::where(['variation_id'=>$feature_variation_id,'origin'=>$origin])->pluck('id')->first();

                $data = [ 'product_id'=>$row->product_id,'variation_id'=>$feature_variation_id, 'variation_option_id'=> $feature_variation_option_id ];

                // echo 'variation_id: 3, title: '.$fabric_title.', option_id:'.$fabric_variation_option_id.' <br/>';

                $check = Product_variation_option::where($data);
                if($check->count() <1){
                    Product_variation_option::create($data);
                }else $check->update($data);
            }else $feture_id = $feture_title= $feture_variation_id = $feture_variation_option_id = '';

            // echo 'product id: '.$row->product_id.', ';
            // echo 'color id: '.$color_id.', color-variation-id: '.$color_variation_id.', option id: '.$color_variation_option_id.'('.$color_title.') =  ';
            // echo 'size id: '.$size_id.', size-variation-id: '.$size_variation_id.', option id: '.$size_variation_option_id.'('.$size_title.') = ';

            // if($fabric_id !=null){
            //     echo 'type id: '.$fabric_id.', fabric-variation-id: '.$fabric_variation_id.', option id: '.$fabric_variation_option_id.'('.$fabric_title.')<br/>';
            // }else echo '<br/>';

            // if($type_id !=null){
            //     echo 'type id: '.$type_id.', type-variation-id: '.$type_variation_id.', option id: '.$type_variation_option_id.'('.$type_title.')<br/>';
            // }else echo '<br/>';

        }
        // exit;
    }
    function product_combinations(){
        $proV = DB::table('product_variants')->get();
        $color = $size = $type = $string = '';
        $proIds = array();
       foreach($proV as $pv){
            if($pv->color_id !=null){
               $colorName ='colors';
               $color = strtolower(DB::table('colors')->where('id',$pv->color_id)->pluck('title')->first());
            }else $color = $colorName = '';

            if($pv->size_id !=null){
                $sizeName ='sizes';
                $size = strtolower(DB::table('sizes')->where('id',$pv->size_id)->pluck('title')->first());
            }else $size = $sizeName = '';

            if($pv->fabric_id !=null){
                $fabricName ='fabrics';
                $fabric = strtolower(DB::table('fabrics')->where('id',$pv->fabric_id)->pluck('title')->first());
            }else $fabric = $fabricName ='';

            if($pv->type_id !=null){
                $typeName ='types';
                $type = strtolower(DB::table('types')->where('id',$pv->type_id)->pluck('title')->first());
            }else $type = $typeName = '';

            if($pv->feature_id !=null){
                $featureName ='features';
                $feature = strtolower(DB::table('features')->where('id',$pv->fabric_id)->pluck('title')->first());
            }else $feature = $featureName = '';


            // $string .= Variation_option::where('id',$id)->pluck('origin')->first();

           $proIds[] = $pv->product_id;
           $string = $color.'~'.$size.'~'.$fabric.'~'.$type.'~'.$feature;
           $string2 = $color.$size.$fabric.$type.$feature;

           if(substr($string, -1) =='~'){
                $string = rtrim($string, "~");
           }

            $names = $colorName.'~'.$sizeName.'~'.$fabricName.'~'.$typeName.'~'.$featureName;
            if(substr($names, -1) =='~'){
                $names = rtrim($names, "~");
            }

            $unique_string = str_split(str_replace(',','',str_replace(' ','-',strtolower($string2))));
            sort($unique_string);

           $data = [
               'product_id'=>$pv->product_id,
               'sku'=>rand(),
               'combine_variation'=>$names,
               'combination_string'=>str_replace(' ','-',$string),
               'unique_string'=>implode($unique_string),
               'qty'=>$pv->qty,
               'barcode'=>rand()
           ];
           echo $names.' = '. str_replace(' ','-',$string).'='.implode($unique_string).'<br/>';
           $check = Product_combination::where(['product_id'=>$pv->product_id,'unique_string'=>implode($unique_string)]);

           if($check->count() <1){
                Product_combination::create($data);
           }

       }

    }



    function get_color_product(){
        $response = Http::get('https://mbrellabd.com/get-data/nz-test/color_product');
        // dd($response->object());
        foreach($response->object() as $key=>$row){
            $title = DB::table('colors')->where(['id'=>$row->color_id])->pluck('title')->first();
            $variation_option_id = DB::table('variation_options')->where(['title'=>$title])->pluck('id')->first();
            // echo $title.', '.$variation_option_id.'<br/>';

            $data = [
                'variation_id'=>1, 'variation_option_id'=>$variation_option_id,
                'product_id'=>$row->product_id, 'thumbs'=>$row->thumbs , 'photo'=>$row->photo,'status'=>$row->status
            ];
            $check = Variation_option_photo::where(['variation_id'=>'1', 'variation_option_id'=>$variation_option_id, 'product_id'=>$row->product_id]);
            if($check->count() <1){
                Variation_option_photo::create($data);
            }else $check->update($data);
        }
        exit;
    }

    function city_zone(){
        $response = Http::get('https://mbrellabd.com/get-data/nz-test/city_zone');
        foreach($response->object() as $key=>$row){
            $data = [
                'zone_id'=>$row->zone_id, 'city_id'=>$row->city_id
            ];
            $check = City_zone::where($data);
            if($check->count() <1){
                City_zone::create($data);
            }else $check->update($data);
        }
    }

    function sliders(){
        $response = Http::get('https://mbrellabd.com/get-data/nz-test/sliders');
        foreach($response->object() as $key=>$row){
            $data = [
                'title'=>$row->title, 'photo'=>$row->photo, 'description'=>$row->description,
                'link'=>$row->link,'text_color'=>$row->text_color, 'sort_by'=>$row->sort_by, 'status'=>$row->status
            ];
            $check = Slider::where($data);
            if($check->count() <1){
                Slider::create($data);
            }else $check->update($data);
        }
    }

    function policy_types(){
        $response = Http::get('https://mbrellabd.com/get-data/nz-test/policy_types');
        foreach($response->object() as $key=>$row){
            $data = [
                'id'=>$row->id, 'title'=>$row->title, 'photo'=>$row->photo, 'slug'=>$row->slug, 'status'=>$row->status
            ];
            $check = Policy_type::where(['id'=>$row->id]);
            if($check->count() <1){
                Policy_type::create($data);
            }else $check->update($data);
        }
    }

    function policies(){
        $response = Http::get('https://mbrellabd.com/get-data/nz-test/policies');
        foreach($response->object() as $key=>$row){
            $data = [
                'id'=>$row->id, 'policy_type_id'=>$row->policy_type_id, 'title'=>$row->title, 'photo'=>$row->photo, 'description'=>$row->description,
                'visibility'=>$row->visibility, 'status'=>$row->status
            ];
            $check = Policy::where($data);
            if($check->count() <1){
                Policy::create($data);
            }else $check->update($data);
        }
    }

    function show_rooms(){
        $response = Http::get('https://mbrellabd.com/get-data/nz-test/show_rooms');
        foreach($response->object() as $key=>$row){
            $data = [
               'title'=>$row->title, 'photo'=>$row->photo, 'description'=>$row->description,
                'phone'=>$row->phone, 'location'=>$row->location, 'status'=>$row->status
            ];
            $check = Show_room::where($data);
            if($check->count() <1){
                Show_room::create($data);
            }else $check->update($data);
        }
    }

    function social_media(){
        $response = Http::get('https://mbrellabd.com/get-data/nz-test/social_media');
        foreach($response->object() as $key=>$row){
            $data = [ 'id'=>$row->id, 'media_name'=>$row->media_name, 'media_link'=>$row->media_link, 'media_icon'=>$row->media_icon,'status'=>$row->status];
            $check = Social_media::where('id',$row->id);
            if($check->count() <1){
                Social_media::create($data);
            }else $check->update($data);
        }
    }

    function quick_services(){
        $response = Http::get('https://mbrellabd.com/get-data/nz-test/quick_services');
        foreach($response->object() as $key=>$row){

            $data = [
                'id'=>$row->id, 'title'=>$row->title, 'photo'=>$row->photo,
                'description'=>$row->description,'type'=>$row->type,'type_info'=>$row->type_info, 'status'=>$row->status
            ];
            $check = Quick_service::where('id',$row->id);
            if($check->count() <1){
                Quick_service::create($data);
            }else $check->update($data);
        }
    }

}
