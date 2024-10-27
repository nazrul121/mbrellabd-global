<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Slider;
use App\Models\Season;
use App\Models\Promotion;
use App\Models\Highlight;
use App\Models\Inner_group_season;
use App\Models\Child_group_season;
use App\Models\Inner_group;
use App\Models\Child_group;
use App\Models\Country;


class FrontController extends Controller
{
    
    function countries(){
        $country = Country::where(['status'=>'1'])
            ->select('id','name','short_name','code', 'flag')->get();
        return response()->json($country);
    }

    function home_slider(){
        $sliders = Slider::where(['status'=>'1'])
            ->select('id', 'title', 'photo','text_color')
            ->orderBy('sort_by')->get();
        return response()->json($sliders);
    }

    function promotions(){
        $promotions = Promotion::where(['status'=>'1'])
            ->select('id', 'title', 'bg_color','text_color','photo')
            ->get();
        return response()->json($promotions);
    }

    public function seasons(){
        $groups = Season::where(['status'=>'1'])
            ->select('id','title','photo')
            ->withCount('products')
            ->with('groups:id,title,photo')
            ->get();
        return response()->json($groups);
    }

    function seasonInnerGroups($seasonId, $groupId){
        $groupIds = Inner_group_season::where(['group_id'=>$groupId,'season_id'=>$seasonId, 'status'=>'1'])
            ->select('inner_group_id')->distinct()
            ->get()->toArray();
        $groups = Inner_group::whereIn('id',$groupIds)->select('id','title','photo')->get();

        return response()->json($groups);
    }

    function seasonChildGroups($seasonId, $innerGId){
        $groupIds = Child_group_season::where(['inner_group_id'=>$innerGId,'season_id'=>$seasonId, 'status'=>'1'])
            ->select('child_group_id')->distinct()
            ->get()->toArray();
        $groups = Child_group::whereIn('id',$groupIds)->select('id','title','photo')->get();

        return response()->json($groups);
    }

    

    function highlights($number = 8) {
        $highlists = Highlight::where(['status' => '1'])
            ->select('id', 'title', 'sub_title', 'photo')
            ->with(['products' => function($query) use ($number) {
                $query->limit($number);
            }])
            ->get();
        return response()->json($highlists);
    }
    


}
