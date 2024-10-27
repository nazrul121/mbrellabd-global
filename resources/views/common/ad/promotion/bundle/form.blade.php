<?php $categories = \App\Models\Group::orderBy('title')->get();?>

<div class="container">
    <p class="alert alert-info">Promotion : <b>{{ $promotion->title }}</b> - {{ $promotion->promotion_type->title }}
        <button class="btn btn-sm btn-secondary viewBundles float-right mr-0">View bundles of the promotion</button>
        <button class="btn btn-sm btn-secondary viewselected float-right mr-0" style="display:none">Selected proudcts</button>
    </p>

    <form class="mb-5" id="addToBundle" action="{{ route('common.temp-bundles',$promotion->id) }}" method="get"> @csrf
        <div class="col-md-12"></div>
        <div class="row text-right ">
            <label for="" class="col-md-1 col-form-label">Group</label>
            <div class="col-sm-2">
                <select class="form-control" name="category_id" required>
                    <option value="">Choose Group</option>
                    @foreach ($categories as $type)
                    <option value="{{ $type->id }}">{{ $type->title }}</option>
                    @endforeach
                </select>
            </div>

            <label for="" class="col-form-label col-sm-1">Sub Group</label>
            <div class="col-sm-2">
                <select class="form-control" name="sub_category_id"> </select>
            </div>

            <label for="" class="col-form-label col-sm-1">Child Group</label>
            <div class="col-sm-2">
                <select class="form-control" name="child_category_id"> </select>
            </div>
            <div class="col-sm-2">
                <button class="btn btn-primary" type="submit" disabled><i class="feather icon-arrow-down"></i> add</button>
            </div>
        </div>
        <div class="col-md-12 showProducts mt-4"></div>
    </form>
    <div class="bundleItems"></div>

</div>


<script>
    $(function(){
        $( ".bundleItems" ).load( "/common/ad/promotion/temp-bundles/{{ $promotion->id }}");

        $("#addToBundle").submit(function(e) {
            e.preventDefault();
            var form = $(this); var actionUrl = form.attr('action');
            $.ajax({
                type: "get",    url: actionUrl, data: form.serialize(),
                success: function(data) {
                    $('.addBundle').html(data);
                    $('.showProducts').html('')
                }
            });
        });

        //get sub-categories
        $('[name=category_id]').on('change',function(){
            $("[name=sub_category_id]").html('<option value="">Choose sub-groups</option>');
            if($(this).val().length >0){
                sub_categories( $(this).val() );
                get_category_products( $(this).val() );
            }else{
                $("[name=sub_category_id]").html('');
                $('.showProducts').html('');
            }
        });

        // get child-category
        $('[name=sub_category_id]').on('change',function(){
            $("[name=child_category_id]").html('<option value="">Choose Child-groups</option>');
            if($(this).val().length >0){
                child_categories($(this).val());
                get_inner_category_products( $(this).val() );
            }else $("[name=child_category_id]").html('');
        });

        $('[name=child_category_id]').on('change',function(){
            if($(this).val().length >0){
                get_child_category_products( $(this).val() );
            }
        });
    })


    function sub_categories(cat_id){
        if(cat_id=='all') return false;
        $.ajax({ url:"/get-sub-categories/"+ cat_id, method:"get",
            success:function(data){
                $.each(data, function(index, value){
                    $("[name=sub_category_id]").append('<option value="'+value.id+'">'+value.title+'</option>');
                });
            }
        });
    }

    function child_categories(sub_cat){
        $.ajax({ url:"/get-child-categories/"+ sub_cat, method:"get",
            success:function(data){
                $.each(data, function(index, value){
                    $("[name=child_category_id]").append('<option value="'+value.id+'">'+value.title+'</option>');
                });
            }
        });
    }


    function get_category_products(cat_id){
        $('.showProducts').html('<h4 class="text-warning p-3">Working...</h4>');
        $('[type=submit]').attr('disabled',true);
        $.ajax({ url:"/common/get-group-products/"+ cat_id+'/{{ $promotion->id }}/-/yes', method:"get",
            success:function(data){
                $('.showProducts').html(data);
                $('[type=submit]').attr('disabled',false);
            }
        });
    }

    function get_inner_category_products(cat_id){
        $('.showProducts').html('<h4 class="text-warning p-3">Working...</h4>');
        $('[type=submit]').attr('disabled',true);
        let group_id = $('[name=category_id]').val();
        $.ajax({ url:"/common/get-inner-group-products/"+ cat_id+'/{{ $promotion->id }}/-/yes', method:"get",
            data: {group_id:group_id},
            success:function(data){
                $('.showProducts').html(data);
                $('[type=submit]').attr('disabled',false);
            }
        });
    }

    function get_child_category_products(cat_id){
        $('.showProducts').html('<h4 class="text-warning p-3">Working...</h4>');
        $('[type=submit]').attr('disabled',true);
        let group_id = $('[name=category_id]').val();
        let innerGroup_id = $('[name=sub_category_id]').val();


        $.ajax({ url:"/common/get-child-group-products/"+ cat_id+'/{{ $promotion->id }}/-/yes', method:"get",
            data: {group_id:group_id, inner_group_id: innerGroup_id},
            success:function(data){
                $('.showProducts').html(data);
                $('[type=submit]').attr('disabled',false);
            }
        });
    }
</script>


