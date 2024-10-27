<?php
$categories = \DB::table('groups')->get();
?>
<form class="form-inline">@csrf
    <div class="form-group mx-sm-3 mb-2">
        <select name="category_id" class="form-control">
            <option value="">Choose Group</option>
            @foreach ($categories as $cat)
                <option @if(request()->get('category_id')==$cat->id)selected @endif value="{{ $cat->id }}">{{ $cat->title }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group mx-sm-3 mb-2">
        <select name="sub_category_id" class="form-control">
            <option value="">Choose Sub-category</option>
            <?php if(request()->get('category_id')){
                $sub_cats = \DB::table('inner_groups')->where('group_id', request()->get('category_id'))->get();
            }else $sub_cats = array();?>
            @foreach ($sub_cats as $sub)
                <option @if(request()->get('sub_category_id')==$sub->id)selected @endif value="{{ $sub->id }}">{{ $sub->title }}</option>
            @endforeach

        </select>
    </div>
    <div class="form-group mx-sm-3 mb-2">
        <select name="child_category_id" class="form-control">
            <option value="">Choose Child-category</option>
            <?php if(request()->get('sub_category_id')){
                $child_groups = \DB::table('child_groups')->where('inner_group_id', request()->get('sub_category_id'))->get();
            }else $child_groups = array();?>
            @foreach ($child_groups as $child)
                <option @if(request()->get('child_category_id')==$child->id)selected @endif value="{{ $child->id }}">{{ $child->title }}</option>
            @endforeach


        </select>
    </div>
    <div class="form-group mx-sm-3 mb-2">
        <select name="design_code" class="form-control">
            <option value="">Design Year</option>
            <?php $year = 2015; // staring year ?>
            @for ($i = $year; $year <= date('Y') +1; $year++)
                <option @if(request()->get('design_code')==$year)selected @endif value="{{ $year }}">{{ $year }}</option>
            @endfor
        </select>
    </div>
    <button type="submit" class="btn btn-primary mb-2">Search</button>
</form>
