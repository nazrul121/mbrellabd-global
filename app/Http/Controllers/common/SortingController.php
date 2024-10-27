<?php

namespace App\Http\Controllers\common;

use App\Http\Controllers\Controller;
use App\Models\Child_group;
use App\Models\Group;
use App\Models\Inner_group;
use App\Models\Slider;
use Illuminate\Http\Request;

class SortingController extends Controller
{
    function ordering(){
        return view('common.category.ordering');
    }

    function group_ordering(Request $request){
        for($i=0; $i<count($request->page_id_array); $i++){
            Group::where('id',$request->page_id_array[$i])->update([ 'sort_by'=>$i ]);
        }
    }

    function inner_group_ordering(Request $request){
     
        for($i=0; $i<count($request->page_id_array); $i++){
            Inner_group::where('id',$request->page_id_array[$i])->update([ 'sort_by'=>$i ]);
        }
    }

    function child_group_ordering(Request $request){
        for($i=0; $i<count($request->page_id_array); $i++){
            Child_group::where('id',$request->page_id_array[$i])->update([ 'sort_by'=>$i ]);
        }
    }

    function slider_ordering(Request $request){
        for($i=0; $i<count($request->page_id_array); $i++){
            Slider::where('id',$request->page_id_array[$i])->update([ 'sort_by'=>$i ]);
        }
    }

}
