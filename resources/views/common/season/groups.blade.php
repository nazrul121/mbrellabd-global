<form class="row" action="{{ route('common.season.groups',$season->id) }}">@csrf
    <div class="col-md-12" >
        <?php
            $group_ids = \App\Models\Group_season::where('season_id',$season->id)->select('group_id')->distinct('group_id')->get()->toArray();
            $categories = \App\Models\Group::whereIn('id',$group_ids)->get(); ?>

        @if(COUNT($group_ids)>0)
            <label>Check from Categories [ <code>Allow visibility for customers</code> ]</label>
        @else <label>[ <code>No products are selected under the season yet</code> ]</label> @endif
        <br><br>

        <div class="form-group">
            @foreach ($categories as $key1=>$cat)
                <div class="checkbox checkbox-info checkbox-fill d-inline">
                    <?php $gStatus = \DB::table('group_season')->where(['group_id'=>$cat->id,'season_id'=>$season->id])->pluck('status')->first();?>

                    <input type="checkbox" class="category" @if($gStatus=='1')checked @endif name="category_ids[]" id="cat{{ $cat->id }}" value="{{ $cat->id }}">
                    <label for="cat{{ $cat->id }}" class="cr text-info">{{ $cat->title }}</label>
                </div>
                @php
                    $innerGroup_ids = \App\Models\Inner_group_season::where(['season_id'=>$season->id, 'group_id'=>$cat->id])->select('inner_group_id')->distinct('inner_group_id')->get()->toArray();
                    $sub_categories = \App\Models\Inner_group::whereIn('id',$innerGroup_ids)->get();
                @endphp
                @foreach ($sub_categories as $key2=>$sub)
                    <div class="col-sm-10 offset-1 ">
                        <div class="checkbox mb-1 checkbox-primary checkbox-fill d-inline">
                            <?php $subStatus = \DB::table('inner_group_season')->where(['inner_group_id'=>$sub->id,'season_id'=>$season->id])->pluck('status')->first();?>

                            <input type="checkbox" class="sub_category" data-cat="{{ $cat->id }}" @if($subStatus=='1')checked @endif name="sub_category_ids[]" id="sub{{ $cat->id.$sub->id }}" value="{{ $sub->id }}">
                            <label for="sub{{ $cat->id.$sub->id }}" class="cr text-primary">{{ $sub->title }}</label>
                        </div>
                    </div>

                    @php
                        $childGroup_ids = \App\Models\Child_group_season::where(['season_id'=>$season->id, 'inner_group_id'=>$sub->id])->select('child_group_id')->distinct('child_group_id')->get()->toArray();
                        $child_categories = \App\Models\Child_group::whereIn('id',$childGroup_ids)->get();
                    @endphp
                    @if ($child_categories->count()>0)
                        @foreach ($child_categories as $key3=>$child)
                            <div class="col-sm-8 offset-1 "> &nbsp; &nbsp; &nbsp;
                                <div class="checkbox mb-1 checkbox-secondary checkbox-fill d-inline">
                                    <?php $childStatus = \DB::table('child_group_season')->where(['child_group_id'=>$child->id,'season_id'=>$season->id])->pluck('status')->first();?>

                                    <input type="checkbox" class="child_category" @if($childStatus == '1')checked @endif  data-cat="{{ $cat->id }}" data-sub="{{ $sub->id }}" name="child_category_ids[]" id="child{{ $cat->id.$sub->id.$child->id }}" value="{{ $child->id }}">
                                    <label for="child{{ $cat->id.$sub->id.$child->id }}" class="cr text-secondary">{{ $child->title }}</label>
                                </div>
                            </div>
                        @endforeach
                    @endif
                @endforeach
            @endforeach
        </div>
        <div class="form-group">
            @if(COUNT($group_ids)>0) <button class="btn btn-info float-right" name="updateMenu" value="save"> Save Menu visibility</button> @endif
        </div>
    </div>
</form>


<script>
    $(function(){
        $('.sub_category').on('click',function(){
            let group = $(this).data('cat');
            $('#cat'+group).attr('checked',true);
            $('#cat'+group).val(group);
        });

        $('.child_category').on('click',function(){
            let group = $(this).data('cat');
            let sub = $(this).data('sub');
            $('#cat'+group).attr('checked',true);
            $('#cat'+group).val(group);

            $('#sub'+group+sub).attr('checked',true);
            $('#sub'+group).val(sub);
        });
    })
</script>
