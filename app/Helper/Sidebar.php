<?php
    $filterWith = \App\Models\Setting::where('type','filter-variation')->pluck('value')->first();

    $title = $promo_colors = ''; $type=''; $groups = $innerGroups = $childGroups = array();
    $child_selected = '';

    if(request()->segment(2)=='group'){
        $g_slug = request()->segment(3);
        $group = \App\Models\Group::where(['slug'=>$g_slug,'status'=>'1'])->select('id','title', 'display_name')->first();
        if($group !=null){
            $categories = \App\Models\Inner_group::where(['status'=>'1', 'group_id'=>$group->id])->select('id','title','slug')->orderBy('sort_by')->get();
            if($group->display_name==null) $title = $group->title;
            else $title = $group->display_name;
        }else $categories = array();

        $type='group';
    }
    else if(request()->segment(2)=='group-in'){
        $sub_slug = request()->segment(3);
        $inner = \App\Models\Inner_group::where(['slug'=>$sub_slug,'status'=>'1'])->select('id','title')->first();
        if($inner ==null) $categories = (object)[];
        else{
            $categories = \App\Models\Child_group::where(['status'=>'1', 'inner_group_id'=>$inner->id])->select('id','title','slug','display_name')->select('id','title','slug')->orderBy('sort_by')->get();
        
            if($inner->display_name==null) $title = $inner->title;
            else $title = $inner->display_name;
        }
        $type='inner';

    }else if(request()->segment(2)=='child-in'){
        $sub_slug = request()->segment(3);
        $child = \App\Models\Child_group::where(['slug'=>$sub_slug,'status'=>'1'])->select('id','title','inner_group_id');
        $categories = $child->get();
        // if($child ==null) $categories = (object)[];
        // else{  $categories = \App\Models\Child_group::where('id','!=', $child->id)->where('inner_group_id',$child->inner_group_id)->select('id','title','slug')->get(); }
        
        if($child->first()->display_name==null) $title = $child->first()->title;
        else $title = $child->first()->display_name;
        $type='child';
        $child_selected = 'checked';
    }

    // season
    else if(request()->segment(2)=='season-items'){
        $season = \App\Models\Season::where(['slug'=>request()->segment(3),'status'=>'1'])->select('id','title')->first();
        $group_ids = \App\Models\Product_season::where('season_id',$season->id)->select('group_id')->distinct('group_id')->get()->toArray();
        $categories = \App\Models\Group::whereIn('id',$group_ids)->where(['status'=>'1'])->select('id','title','slug')->get();
        $title =  $season->title;
        $type='seasonItems';
    }

    else if(request()->segment(2)=='season-group'){
        $season = \App\Models\Season::where(['slug'=>request()->segment(3),'status'=>'1'])->select('id')->first();
        $sub_slug = request()->segment(4);
        $group = \App\Models\Group::where(['slug'=>$sub_slug,'status'=>'1'])->select('id','title')->first();

        if($group ==null)  $categories = (object)[];
        else{
            $inner_group_season = \App\Models\Inner_group_season::where(['group_id'=>$group->id,'season_id'=>$season->id])->select('inner_group_id')->distinct('inner_group_id')->get()->toArray();
            $categories = \App\Models\Inner_group::whereIn('id',$inner_group_season)->where('status','1')->select('id','title','slug')->orderBy('sort_by')->get();
            $title = $group->title;
        }

        $title =  $season->title;
        $type='seasonGroup';
    }
    else if(request()->segment(2)=='season-group-in'){
        $season = \App\Models\Season::where(['slug'=>request()->segment(3),'status'=>'1'])->select('id')->first();
        $sub_slug = request()->segment(4);
        $innerGroup = \App\Models\Inner_group::where(['slug'=>$sub_slug,'status'=>'1'])->select('id','title')->first();

        $child_group_season = \App\Models\Child_group_season::where(['inner_group_id'=>$innerGroup->id,'season_id'=>$season->id])->select('child_group_id')->distinct('child_group_id')->get()->toArray();
        if($innerGroup ==null)  $categories = (object)[];
        else{ $categories = \App\Models\Child_group::whereIn('id',$child_group_season)->where('status','1')->select('id','title','slug')->orderBy('sort_by')->get(); }
        $title = $innerGroup->title;
        $type='seasonInner';
    }

    else if(request()->segment(2)=='season-child-in'){
        $season = \App\Models\Season::where(['slug'=>request()->segment(3),'status'=>'1'])->select('id')->first();
        $sub_slug = request()->segment(4);
        $childGroup = \App\Models\Child_group::where(['slug'=>$sub_slug,'status'=>'1'])->select('id','title')->first();

        $child_group_season = \App\Models\Child_group_season::where(['child_group_id'=>$childGroup->id,'season_id'=>$season->id])->select('child_group_id')->distinct('child_group_id')->get()->toArray();
        if($childGroup ==null)  $categories = (object)[];
        else{ $categories = \App\Models\Child_group::whereIn('id',$child_group_season)->where('status','1')->select('id','title','slug')->orderBy('sort_by')->get(); }
        $title = $childGroup->title;
        $type='seasonChild';
    }

    //promotion
    else if(request()->segment(2)=='promo-items'){
        $slug = request()->segment(3);
        $promo = \App\Models\Promotion::where('slug',$slug)->select('id')->first();
        $title = 'Categories';
        $product_ids = \App\Models\Product_promotion::where('promotion_id',$promo->id)->select('product_id')->distinct('product_id')->get()->toArray();
        $group_ids = \App\Models\Group_product::whereIn('product_id',$product_ids)->select('group_id')->distinct('group_id')->get()->toArray();
        $groups = \App\Models\Group::whereIn('id',$group_ids)->select('id','title','slug')->get();
        $variations = get_variations($product_ids, $filterWith);
    }
    else if(request()->segment(2)=='highlight-products'){
        $highlight = \App\Models\Highlight::where('id',request()->segment(3))->select('id','title')->first();
        $title = $highlight->title.' products';
    }


    else{
        $type='top-category';
        $title = 'Top - Categories';
        $categories = \App\Models\Inner_group::where(['is_top'=>'1', 'status'=>'1'])->select('id','title','slug')->orderBy('sort_by')->get();
    }






    if(request()->segment(1)=='group'){
        $group_id = \App\Models\Group::where(['slug'=>request()->segment(2),'status'=>'1'])->pluck('id')->first();
        $product_ids = \App\Models\Group_product::where(['status'=>'1', 'group_id'=>$group_id])->select('product_id')->distinct('product_id')->get()->toArray();
        $variations = get_variations($product_ids, $filterWith);

    }
    else if(request()->segment(1)=='group-in'){
        $group_id = \App\Models\Inner_group::where(['slug'=>request()->segment(2),'status'=>'1'])->pluck('id')->first();
        $product_ids = \App\Models\Inner_group_product::where(['status'=>'1', 'inner_group_id'=>$group_id])->select('product_id')->distinct('product_id')->get()->toArray();

        $variations = get_variations($product_ids, $filterWith);

    }
    else if(request()->segment(1)=='child-in'){
        $group_id = \App\Models\Child_group::where(['slug'=>request()->segment(2),'status'=>'1'])->pluck('id')->first();
        $product_ids = \App\Models\Child_group_product::where(['status'=>'1', 'child_group_id'=>$group_id])->select('product_id')->distinct('product_id')->get()->toArray();
        $variations = get_variations($product_ids, $filterWith);
    }


    //season
    else if(request()->segment(1)=='season-items'){
        $product_ids = \App\Models\Product_season::where(['status'=>'1', 'season_id'=>$season->id])->select('product_id')->distinct('product_id')->get()->toArray();
        $variations = get_season_variations($product_ids, $filterWith);
    }
    else if(request()->segment(1)=='season-group'){
        $season = \App\Models\Season::where(['slug'=>request()->segment(2),'status'=>'1'])->select('id')->first();
        $group_id = \App\Models\Group::where('slug',request()->segment(3))->pluck('id')->first();
        $product_ids = \App\Models\Product_season::where(['season_id'=>$season->id, 'group_id'=>$group_id])->select('product_id')->distinct('product_id')->get()->toArray();

        $variations = get_season_variations($product_ids, $filterWith);
    }
    else if(request()->segment(1)=='season-group-in'){
        $season = \App\Models\Season::where(['slug'=>request()->segment(2),'status'=>'1'])->select('id')->first();

        $inner_id = \App\Models\Inner_group::where('slug',request()->segment(3))->pluck('id')->first();
        $product_ids = \App\Models\Product_season::where(['season_id'=>$season->id,'inner_group_id'=>$inner_id])->select('product_id')->distinct('product_id')->get()->toArray();
        $variations = get_variations($product_ids, $filterWith);
    }

    else if(request()->segment(1)=='season-child-in'){
        $season = \App\Models\Season::where(['slug'=>request()->segment(2),'status'=>'1'])->select('id')->first();
        $child_id = \App\Models\Child_group::where('slug',request()->segment(3))->pluck('id')->first();
        // $child_group_season = \App\Models\Child_group_season::where(['child_group_id'=>$child_id,'season_id'=>$season->id])->pluck('id')->first();
        $product_ids = \App\Models\Product_season::where(['season_id'=>$season->id,'child_group_id'=>$child_id])->select('product_id')->distinct('product_id')->get()->toArray();
        $variations = get_variations($product_ids, $filterWith);
    }
    else{
        $product_ids =\App\Models\Product::where('status','1')->select('id')->get()->toArray();
        $variations = get_variations($product_ids, $filterWith);
    }


 function get_variations($product_ids, $filterWith){
    $variationOption_ids = \App\Models\Product_variation_option::whereIn('product_id',$product_ids)->distinct('variation_option_id')->select('variation_option_id')->get();
    $vIds = array();
    // dd($variationOption_ids);
    foreach($variationOption_ids as $vo){
        // echo $vo->variation_option_id.' ';
        if (in_array($vo->variation_option->variation_id, explode(',',$filterWith)) ){
            $vIds[] = array('variation_id'=>$vo->variation_option->variation_id);
        }
    }

    // dd(array_unique($vIds, SORT_REGULAR));
    $variations = \App\Models\Variation::whereIn('id',array_unique($vIds, SORT_REGULAR))->get();
    // dd($variations);
    return $variations;
 }



 function get_season_variations($product_ids, $filterWith){
     //not done
    $variationOption_ids = \App\Models\Product_variation_option::whereIn('product_id',$product_ids)->distinct('variation_option_id')->select('variation_option_id')->get();
    $vIds = array();
    foreach($variationOption_ids as $vo){
        if (in_array($vo->variation_option->variation_id, explode(',',$filterWith)) ){
            $vIds[] = array('variation_id'=>$vo->variation_option->variation_id);
        }
    }
    // dd(array_unique($vIds, SORT_REGULAR));
    $variations = \App\Models\Variation::whereIn('id',array_unique($vIds, SORT_REGULAR))->get();
    return $variations;
 }
