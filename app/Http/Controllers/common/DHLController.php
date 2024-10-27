<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\DHL_box;
use App\Models\Product;
use App\Models\Product_weight;
use App\Models\Group_product;
use App\Models\Inner_group_product;
use App\Models\Child_group_product;
use App\Models\Country;
use App\Models\Dhl_zone_price;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

use Symfony\Component\String\UnicodeString;
use Illuminate\Support\Str;
use DB;

class DHLController extends Controller
{
    function index(Request $request){
        return view('common.courier.dhl.index');
    }
 
    public function update(Request $request){
        $dhl_boxex = [];
        foreach($request->groups as $key=>$group){
            // echo $group.' - '.$request->inner_groups[$key].' - '.$request->child_groups[$key];

            if(!$request->child_groups[$key]){
                $childId = null;
                $check = DHL_box::where(['group_id'=>$group,'inner_group_id'=>$request->inner_groups[$key]]);
            }else{
                $childId = $request->child_groups[$key];
                $check = DHL_box::where(['group_id'=>$group,'inner_group_id'=>$request->inner_groups[$key], 'child_group_id'=>$childId ]);
            } 

            $data = [ 
                'group_id'=>$group,
                'inner_group_id'=>$request->inner_groups[$key],
                'child_group_id'=>$childId,
                'small_qty'=>$request->small_qty[$key],
                'small_weight'=>$request->small_weight[$key],
                'large_qty'=>$request->large_qty[$key],
                'large_weight'=>$request->large_weight[$key],
                'flyer_small_qty'=>$request->flyer_small_qty[$key],
                'flyer_small_weight'=>$request->flyer_small_weight[$key],
                'flyer_large_qty'=>$request->flyer_large_qty[$key],
                'flyer_large_weight'=>$request->flyer_large_weight[$key],
            ];

            if($check->count() <1){
                $dhlBox= DHL_box::create($data);
            }else{
                $check->update($data);
                $dhlBox= $check->first();
            }

           
            if($request->large_weight[$key] !='' && $request->large_qty[$key] !=''){
                $groupProduct = Group_product::where('group_id',$group)->select('product_id','group_id')->get();
                foreach($groupProduct as $gp){
                    $inner = DB::table('inner_groups')->where('id',$request->inner_groups[$key])->select('id','title')->first();
                    
                    if($childId !=null){
                        $child = DB::table('child_groups')->where('id',$childId)->first();
                        $childTitle = $child->title;
                        $checkData = ['product_id'=>$gp->product_id,'group_id'=>$gp->group_id, 'inner_group_id'=>$request->inner_groups[$key],'child_group_id'=>$childId];

                    }else{
                        $childTitle = 'N/A';
                        $checkData = ['product_id'=>$gp->product_id,'group_id'=>$gp->group_id, 'inner_group_id'=>$request->inner_groups[$key]];
                    } 
                    
                  
                    // if($gp->group->title =='M-Décor'){
                        echo $gp->product_id.'='.$gp->group->title.' = '.$inner->title.' = '.$childTitle.' = '.round($request->large_weight[$key] / $request->large_qty[$key] , 2).'/'.round($request->large_weight[$key] / $request->large_qty[$key], 1);
                    
                        echo ' larage cap: '.$request->large_qty[$key].', large Weight: '.$request->large_weight[$key].'<br/>';
    
                        $checkPW = Product_weight::where($checkData);
                        if($checkPW->count() <1){
                            Product_weight::create([
                                'product_id'=>$gp->product_id,
                                'group_id'=>$gp->group_id, 'inner_group_id'=>$inner->id, 'child_group_id'=>$childId,
                                'gross_weight'=>round($request->large_weight[$key] / $request->large_qty[$key] , 1),
                                'vol_weight'=>round($request->large_weight[$key] / $request->large_qty[$key], 2),
                                'hs_code'=>$request->hs_code[$key]
                            ]);
                        }else{
                            $checkPW->update([
                                'gross_weight'=>round($request->large_weight[$key] / $request->large_qty[$key] , 1),
                                'vol_weight'=>round($request->large_weight[$key] / $request->large_qty[$key], 2),
                                'hs_code'=>$request->hs_code[$key]
                            ]);
                        }
                    // }
                    
                }
            }
           
            
            // if($request->large_weight[$key] !='' && $request->large_qty[$key] !=''){
            //     $innerGroupProduct = Inner_group_product::where('inner_group_id',$request->inner_groups[$key])->select('product_id','inner_group_id')->get();
            //     foreach($innerGroupProduct as $gp){
            //         $checkPW = Product_weight::where($checkData);
            //         if($checkPW->count() <1){
            //             Product_weight::create([
            //                 'product_id'=>$gp->product_id,
            //                 'group_id'=>$gp->group_id, 'inner_group_id'=>$inner->id, 'child_group_id'=>$childId,
            //                 'gross_weight'=>round($request->large_weight[$key] / $request->large_qty[$key] , 1),
            //                 'vol_weight'=>round($request->large_weight[$key] / $request->large_qty[$key], 2),
            //                 'hs_code'=>$request->hs_code[$key]
            //             ]);
            //         }else{
            //             $checkPW->update([
            //                 'gross_weight'=>round($request->large_weight[$key] / $request->large_qty[$key] , 1),
            //                 'vol_weight'=>round($request->large_weight[$key] / $request->large_qty[$key], 2),
            //                 'hs_code'=>$request->hs_code[$key]
            //             ]);
            //         }
            //     }
            // }

            // if($childId && ($request->large_weight[$key] !='' && $request->large_qty[$key] !='')){
            //     $childGroupProduct = Child_group_product::where('child_group_id',$childId)->select('product_id','child_group_id')->get();
            //     foreach($childGroupProduct as $gp){
            //         $checkPW = Product_weight::where($checkData);
            //         if($checkPW->count() <1){
            //             Product_weight::create([
            //                 'product_id'=>$gp->product_id,
            //                 'group_id'=>$gp->group_id, 'inner_group_id'=>$inner->id, 'child_group_id'=>$childId,
            //                 'gross_weight'=>round($request->large_weight[$key] / $request->large_qty[$key] , 1),
            //                 'vol_weight'=>round($request->large_weight[$key] / $request->large_qty[$key], 2),
            //                 'hs_code'=>$request->hs_code[$key]
            //             ]);
            //         }else{
            //             $checkPW->update([
            //                 'gross_weight'=>round($request->large_weight[$key] / $request->large_qty[$key] , 1),
            //                 'vol_weight'=>round($request->large_weight[$key] / $request->large_qty[$key], 2),
            //                 'hs_code'=>$request->hs_code[$key]
            //             ]);
            //         }
            //     }
            // }

            
            
            
        }
        
        return back()->with(['success'=>'The DHL Box setup has been udpated successfully!']);
    }

    function single_row_update(Request $request){
        // dd($request->all());
        if(!$request->child_group){
            $childId = null;
            $check = DHL_box::where(['group_id'=>$request->group,'inner_group_id'=>$request->inner_group]);
        }else{
            $childId = $request->child_group;
            $check = DHL_box::where(['group_id'=>$request->group,'inner_group_id'=>$request->inner_group, 'child_group_id'=>$childId ]);
        } 

    
        $data = [ 
            'group_id'=>$request->group,
            'inner_group_id'=>$request->inner_group,
            'child_group_id'=>$childId,
            'small_qty'=>$request->small_qty,
            'small_weight'=>$request->small_weight,
            'large_qty'=>$request->large_qty,
            'large_weight'=>$request->large_weight,
            'flyer_small_qty'=>$request->flyer_small_qty,
            'flyer_small_weight'=>$request->flyer_small_weight,
            'flyer_large_qty'=>$request->flyer_large_qty,
            'flyer_large_weight'=>$request->flyer_large_weight,
        ];

        if($check->count() <1){
            $dhlBox= DHL_box::create($data);
        }else{
            // echo 'DHL_boxes updated';
            $check->update($data);
            $dhlBox= $check->first();
        }

        // dd($check->get(), $request->all());

        if($request->large_weight !='' && $request->large_qty !=''){
            $groupProduct = Group_product::where('group_id',$request->group)->select('product_id','group_id')->get();
            foreach($groupProduct as $gp){
                $inner = DB::table('inner_groups')->where('id',$request->inner_group)->select('id','title')->first();
                
                if($childId !=null){
                    $child = DB::table('child_groups')->where('id',$childId)->first();
                    $childTitle = $child->title;
                    $checkData = ['product_id'=>$gp->product_id,'group_id'=>$gp->group_id, 'inner_group_id'=>$request->inner_group,'child_group_id'=>$childId];

                }else{
                    $childTitle = 'N/A';
                    $checkData = ['product_id'=>$gp->product_id,'group_id'=>$gp->group_id, 'inner_group_id'=>$request->inner_group];
                } 
                

                $checkPW = Product_weight::where($checkData);
                if($checkPW->count() <1){
                    Product_weight::create([
                        'product_id'=>$gp->product_id,
                        'group_id'=>$gp->group_id, 'inner_group_id'=>$inner->id, 'child_group_id'=>$childId,
                        'gross_weight'=>round($request->large_weight / $request->large_qty , 1),
                        'vol_weight'=>round($request->large_weight / $request->large_qty, 2),
                        'hs_code'=>$request->hs_code
                    ]);
                }else{
                    $checkPW->update([
                        'gross_weight'=>round($request->large_weight / $request->large_qty , 1),
                        'vol_weight'=>round($request->large_weight / $request->large_qty, 2),
                        'hs_code'=>$request->hs_code
                    ]);
                }
                
            }
        }

        return 'done';


    }






    public function zone_setup(Request $request){
        if ($request->draw) {
            $data = Dhl_zone_price::select('*');
    
            return DataTables::of($data)
                ->editColumn('modify', function($row){
                   $btn = '<button class="btn-info btn-sm edit" id="'.$row->id.'"> <i class="fa fa-edit text-white"></i></button> ';
                   return $btn;
                })
                ->editColumn('zone', function($row){
                    $zoneName = DB::table('countries')->where(['zone'=>$row->zone])->pluck('short_name')->first();

                    $name = '';
                    $check = DB::table('countries')->where('zone',$row->zone);
                    if ($check->count()>1){
                        foreach ($check->get() as $cnt){
                            $name .= $cnt->short_name.' ,';
                        }
                    }else $name = $zoneName;

                    return $row->zone.' - '.$name;
                 })
                 ->editColumn('kg', function($row){
                    return number_format($row->kg_from, 2).' - '.number_format($row->kg_to, 2);
                 })
                 ->editColumn('price', function($row){
                    return number_format($row->price, 2);
                 })
                ->rawColumns(['zone','kg','price','modify'])
                ->make(true);
        }
        $countries = Country::whereNotNull('zone')->groupBy('zone')->distinct()->get(['zone', 'name', 'short_name']);
        $dhl_zones = Country::whereNotNull('zone')->groupBy('zone')->distinct()->get();
        $kGs = DB::table('dhl_zone_prices')->distinct('kg_from')->select('kg_from')->get();
        return view('common.courier.dhl.zone-setup',compact('countries','dhl_zones','kGs'));
    }

    public function save_zone(Request $request){
        $this->fields();
      
        if(count( array_filter($request->zone) ) !=count( array_filter($request->price) )){
            return response()->json(['errors' => ['zone price does not match']]);
        }


        foreach($request->zone as $key=>$zone ){
            Dhl_zone_price::create([
                'zone'=>trim(explode('-',$zone)[0]),
                'kg_from'=>$request->weight_from,
                'kg_to'=>$request->weight_to,
                'price'=>$request->price[$key],
            ]);
        }

        return back()->with(['success' => 'Zone price has been saved successfully!']);
    }

    function single_zone($kg){
        $prices =  Dhl_zone_price::where('kg_from',$kg)->get();
        // dd($prices[0]->kg_to);
        $prices['kg_from'] = $kg;
        $prices['kg_to'] = $prices[0]->kg_to;
        return $prices;
    }


    public function update_zone(Request $request,  $kg){
        $this->fields();
    
        if(count( array_filter($request->zone) ) !=count( array_filter($request->price) )){
            return back()->with(['errors' => ['zone price does not match']]);
        }

        // dd($request->all());
        foreach($request->zone as $key=>$zone ){
            $data = [
                'zone'=>trim(explode('-',$zone)[0]),
                'kg_from'=>$request->weight_from,
                'kg_to'=>$request->weight_to,
            ];

            $check = Dhl_zone_price::where($data);
            $data['price'] = $request->price[$key];

            // dd($data, $check->count());
            if($check->count()>0) $check->update($data);
            else Dhl_zone_price::create($data);
        }

        return back()->with(['success' => 'Zone price hasn been updated successfully!']);
    }
    
    private function fields($id=null){
        return request()->validate([
            'zone'=>'required',
            'weight_from'=>'required',
            'weight_to'=>'required',
            'price'=>'required',
        ]);
    }


    public function destroy($kg){

        try {
            $query = Dhl_zone_price::where('kg_from',$kg)->delete();

            return response()->json(['success' => 'Zone price hasn been deleted successfully!']);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Deletion failed. Its may be the foreign key constrate error!!']);
        }
    }




}
