<?php
    $title =''; $type='';
    
    dd(Request::segment(2));

    if(Request::segment(2)=='group'){
        $g_slug = Request::segment(3);
        $group = \App\Models\Group::where(['slug'=>$g_slug,'status'=>'1'])->select('id','title')->first();
        if($group !=null){
            $categories = \App\Models\Inner_group::where(['status'=>'1', 'group_id'=>$group->id])->select('id','title','slug')->orderBy('sort_by')->get();
            $title = $group->title;
        }else $categories = array();

        $type='group';
    }
    else if(Request::segment(2)=='group-in'){
        $sub_slug = Request::segment(3);
        $inner = \App\Models\Inner_group::where(['slug'=>$sub_slug,'status'=>'1'])->select('id','title')->first();
        if($inner ==null) $categories = (object)[];
        else{
            $categories = \App\Models\Child_group::where(['status'=>'1', 'inner_group_id'=>$inner->id])->select('id','title','slug')->select('id','title','slug')->orderBy('sort_by')->get();
            $title = $inner->title;
        }
        $type='inner';

    }else if(Request::segment(2)=='child-in'){
        $sub_slug = Request::segment(3);
        $child = \App\Models\Child_group::where(['slug'=>$sub_slug,'status'=>'1'])->select('id','title','inner_group_id')->first();
        $child ==null;
        // if($child ==null) $categories = (object)[];
        // else{  $categories = \App\Models\Child_group::where('id','!=', $child->id)->where('inner_group_id',$child->inner_group_id)->select('id','title','slug')->get(); }
        $title = $child->title;
        $type='child';
    }


    else if(Request::segment(2)=='season-group'){
        $sub_slug = Request::segment(4);
        $group = \App\Models\Group::where(['slug'=>$sub_slug,'status'=>'1'])->select('id','title')->first();
        if($group ==null)  $categories = (object)[];
        else{ $categories = \App\Models\Inner_group::where(['status'=>'1', 'group_id'=>$group->id])->select('id','title','slug')->orderBy('sort_by')->get(); }
        $title = $group->title;
        $type='season-group';
    }
    else if(Request::segment(2)=='season-group-in'){
        $sub_slug = Request::segment(4);
        $inner = \App\Models\Inner_group::where(['slug'=>$sub_slug,'status'=>'1'])->select('id','title')->first();
        if($inner ==null) $categories = (object)[];
        else{ $categories = \App\Models\Child_group::where(['status'=>'1', 'inner_group_id'=>$inner->id])->select('id','title','slug')->orderBy('sort_by')->get(); }
        $title = $inner->title;
        $type='season-inner';
    }

    else if(Request::segment(2)=='season-child-in'){
        $sub_slug = Request::segment(4);
        $child = \App\Models\Child_group::where(['slug'=>$sub_slug,'status'=>'1'])->select('id','title','inner_group_id')->first();

        if($child ==null) $categories = (object)[];
        else{ $categories = \App\Models\Child_group::where(['status'=>'1', 'inner_group_id'=>$child->inner_group_id])->select('id','title','slug')->orderBy('sort_by')->get(); }
        $title = $child->inner_group->title;
        $type = 'season-child';
    }
    else{
        $type='top-category';
        $title = 'Top - Categories';
        $categories = \App\Models\Inner_group::where(['is_top'=>'1', 'status'=>'1'])->select('id','title','slug')->orderBy('sort_by')->get();
    }













                    // if(Request::segment(1)=='group'){
                    //     $group_id = \App\Models\Group::where(['slug'=>Request::segment(2),'status'=>'1'])->pluck('id')->first();
                    //     $product_ids = \App\Models\Group_product::where(['status'=>'1', 'group_id'=>$group_id])->select('product_id')->distinct('product_id')->get()->toArray();
                    //     $color_ids = \App\Models\Product_variant::whereIn('product_id',$product_ids)->distinct('color_id')->select('color_id')->get()->toArray();
                    //     $colors = \App\Models\Color::whereIn('id',$color_ids)->select('id','title','code')->get();
                    // }
                    // else if(Request::segment(1)=='group-in'){
                    //     $group_id = \App\Models\Inner_group::where(['slug'=>Request::segment(2),'status'=>'1'])->pluck('id')->first();
                    //     $product_ids = \App\Models\Inner_group_product::where(['status'=>'1', 'inner_group_id'=>$group_id])->select('product_id')->distinct('product_id')->get()->toArray();
                    //     $color_ids = \App\Models\Product_variant::whereIn('product_id',$product_ids)->distinct('color_id')->select('color_id')->get()->toArray();
                    //     $colors = \App\Models\Color::whereIn('id',$color_ids)->select('id','title','code')->get();
                    // }
                    // else if(Request::segment(1)=='child-in'){
                    //     $group_id = \App\Models\Child_group::where(['slug'=>Request::segment(2),'status'=>'1'])->pluck('id')->first();
                    //     $product_ids = \App\Models\Child_group_product::where(['status'=>'1', 'child_group_id'=>$group_id])->select('product_id')->distinct('product_id')->get()->toArray();
                    //     $color_ids = \App\Models\Product_variant::whereIn('product_id',$product_ids)->distinct('color_id')->select('color_id')->get()->toArray();
                    //     $colors = \App\Models\Color::whereIn('id',$color_ids)->select('id','title','code')->get();
                    // }
                    // //season
                    // else if(Request::segment(1)=='season-group'){
                    //     $season = \App\Models\Season::where(['slug'=>Request::segment(2),'status'=>'1'])->select('id')->first();
                    //     $group_id = \App\Models\Group::where('slug',Request::segment(3))->pluck('id')->first();
                    //     $group_season = \App\Models\Group_season::where(['group_id'=>$group_id,'season_id'=>$season->id])->pluck('id')->first();
                    //     $product_ids = \App\Models\Group_season_product::where(['status'=>'1', 'group_season_id'=>$group_season])->select('product_id')->distinct('product_id')->get()->toArray();
                    //     $color_ids = \App\Models\Product_variant::whereIn('product_id',$product_ids)->distinct('color_id')->select('color_id')->get()->toArray();
                    //     // dd($color_ids);
                    //     $colors = \App\Models\Color::whereIn('id',$color_ids)->select('id','title','code')->get();

                    // }
                    // else if(Request::segment(1)=='season-group-in'){
                    //     $season = \App\Models\Season::where(['slug'=>Request::segment(2),'status'=>'1'])->select('id')->first();
                    //     $inner_id = \App\Models\Inner_group::where('slug',Request::segment(3))->pluck('id')->first();
                    //     $inner_group_season = \App\Models\Inner_group_season::where(['inner_group_id'=>$inner_id,'season_id'=>$season->id])->pluck('id')->first();
                    //     $product_ids = \App\Models\Inner_group_season_product::where(['status'=>'1', 'inner_group_season_id'=>$inner_group_season])->select('product_id')->distinct('product_id')->get()->toArray();
                    //     $color_ids = \App\Models\Product_variant::whereIn('product_id',$product_ids)->distinct('color_id')->select('color_id')->get()->toArray();
                    //     $colors = \App\Models\Color::whereIn('id',$color_ids)->select('id','title','code')->get();
                    // }

                    // else if(Request::segment(1)=='season-child-in'){
                    //     $season = \App\Models\Season::where(['slug'=>Request::segment(2),'status'=>'1'])->select('id')->first();
                    //     $child_id = \App\Models\Child_group::where('slug',Request::segment(3))->pluck('id')->first();
                    //     $child_group_season = \App\Models\Child_group_season::where(['child_group_id'=>$child_id,'season_id'=>$season->id])->pluck('id')->first();
                    //     $product_ids = \App\Models\Child_group_season_product::where(['status'=>'1', 'child_group_season_id'=>$child_group_season])->select('product_id')->distinct('product_id')->get()->toArray();
                    //     $color_ids = \App\Models\Product_variant::whereIn('product_id',$product_ids)->distinct('color_id')->select('color_id')->get()->toArray();
                    //     $colors = \App\Models\Color::whereIn('id',$color_ids)->select('id','title','code')->get();
                    // }
                    // else{
                    //     $colors = \App\Models\Color::select('id','title','code')->orderBy('title','ASC')->get();
                    // }
                
