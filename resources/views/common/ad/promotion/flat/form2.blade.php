@extends('common.layouts')

@section('title', $promotion->title)

@section('content')
<?php 
    $promotionCountry = $promotion->countries()->select('country_id')->distinct()->pluck('country_id')->toArray();
    $catIds = \App\Models\Country_group::whereIn('country_id',$promotionCountry)->select('group_id')->distinct()->pluck('group_id')->toArray();
    $categories =  \App\Models\Group::whereIn('id',$catIds)->orderBy('title')->get();
?>

<form class="mb-5" id="addSummary" action="{{ route('common.promotion.save-form',$promotion->id) }}" method="post"> @csrf
    <div class="col-md-10 offset-1 add_summary"></div>
    <div class="row text-right" id="searchForm">
        <label for="" class="col-md-2 col-form-label text-md-left">
          <b class="float-right pt-2"> Product Group</b></label>
        <div class="col-sm-2">
            <select class="form-control" name="category_id">
                <option value="">Choose Group</option>
                @foreach ($categories as $type)
                <option value="{{ $type->id }}" @if(request()->category_id==$type->id)selected @endif>{{ $type->title }}</option>
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
        <div class="col-sm-1">
            <button class="btn btn-info go" type="button"> Go </button>
        </div>
    </div>

    <div class="row mt-5">
        <div class="col-sm-3 offset-2 text-right mt-2">
            <label class="form-label">
                <input type="radio" class="discount_in" name="discount_in" value="percent" checked> <span></span>
                <span>Discount in Percent</span>
            </label> &nbsp; &nbsp;
            <label class="form-label">
                <input type="radio" class="discount_in" name="discount_in" value="amount">
                <span></span><span>Discount in Fix amount</span>
            </label>
        </div>

        <label for="" class="col-sm-1 text-right col-form-label">Amount</label>

        <div class="col-sm-3">
            <div class="input-group">
                <input type="number" class="form-control" name="amount" placeholder="Discount percentage">
                <div class="input-group-append">
                <span class="input-group-text percentSymbol">%</span>
                </div>
            </div>
        </div>        
    </div>

    <table class="table table-hover bg-white productTbl mt-5" style="width:100%">
        <thead>
            <tr> <th>Product Info</th></tr>
        </thead>
        <tbody>
            @foreach ($products as $item)
                <?php $check = \App\Models\Product_promotion::where(['product_id'=>$item->id,'promotion_id'=>$promotion->id,'status'=>'1']);?>
                <tr class="">
                    <td class="p-0 pt-2">
                        <label for="proudct{{ $item->id }}" class="pl-3">
                            <input type="checkbox" @if($check->count()>0)disabled checked @endif style="width:25px;height:25px;" id="proudct{{ $item->id }}" name="product_ids[]" value="{{ $item->id }}">
                            <div style="position:relative;float:right;font-size:17px;padding-left:1em;padding-right:1em;margin-top:-4px;">
                                {{ $item->title }} : {{ $item->design_code }} <img src="{{ $item->thumbs }}" style="height:40px">
                            </div>
                        </label>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="row mt-3">
        <div class="col-md-12 text-right"><button type="submit" class="btn btn-info saveDiscount float-right mr-0"> Save Discount</button> </div>
    </div>
</form>
    
@endsection

@push('scripts')
    <script>
        $(function(){
            $('.go').on('click',function() {
                let currentUrl = window.location.href;

                var cat = $('[name=category_id]').val();
                var sub_cat = $('[name=sub_category_id]').val();
                var child_cat = $('[name=child_category_id]').val();

                // Update each parameter with new values (customize as needed)
                let newUrl = updateURLParameter(currentUrl, 'category_id', cat);  // Example: category_id = 10
                newUrl = updateURLParameter(newUrl, 'sub_category_id', sub_cat);  // Example: sub_category_id = 20
                newUrl = updateURLParameter(newUrl, 'child_category_id', child_cat);  // Example: child_category_id = 30

                // Reload the page with the new URL
                console.log(newUrl);
                window.location.href = newUrl;
            });
            
            $("#addSummary").submit(function(event) {
                event.preventDefault();
                $("[type='submit']").html(' Working...');
                $('.add_result').html('');
                $("[type='submit']").prop('disabled',true);

                var form = $(this);
                $.ajax({
                    type: form.attr('method'),
                    url: form.attr('action'),
                    data: form.serialize()
                }).done(function(data) {
                    $('html, body').animate({ scrollTop: 0 }, 'slow'); 
                    $(".saveDiscount").attr('disabled',false);
                    $(".saveDiscount").text('Save Discount');

                    if(data.warning){
                        $('.add_summary').html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Warning!</strong> '+data.warning+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">�</span></button></div>');
                    }
                    if(data.success){
                        $('.add_summary').html('<div class="alert alert-success alert-dismissible fade show" role="alert">'+data.success+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">�</span></button></div>');
                    }
                }).fail(function(data) {
                    alert('Execution failed. Please try again later!!');
                });
            });

            $('[name=discount_in]').on('change',function(){
                let type = $(this).val();
                if(type=='percent'){
                    $('[name=amount]').attr("placeholder", "Discount percentage");
                    $('.percentSymbol').text('%');
                }
                else {
                    $('[name=amount]').attr("placeholder", "Discount Amount");
                    $('.percentSymbol').text('Tk');
                }

            });

            //get sub-categories
            $('[name=category_id]').on('change',function(){
                $("[name=sub_category_id]").html('<option value="">Choose sub-groups</option>');
                if($(this).val().length >0){
                    sub_categories( $(this).val() );              
                }else{
                    $("[name=sub_category_id]").html('');
                    $('.showProducts').html('');
                }
            });

            // get child-category
            $('[name=sub_category_id]').on('change',function(){
                $("[name=child_category_id]").html('<option value="">Choose Child-groups</option>');
                if($(this).val().length >0){
                    child_categories( $(this).val() ); 
                }else $("[name=child_category_id]").html('');
                
            });

        })

        function updateURLParameter(url, param, value) {
            const regex = new RegExp('([?&])' + param + '=.*?(&|$)', 'i');
            const separator = url.indexOf('?') !== -1 ? '&' : '?';

            if (url.match(regex)) {
            return url.replace(regex, '$1' + param + '=' + value + '$2');
            } else {
            return url + separator + param + '=' + value;
            }
        }

        function sub_categories(cat_id){
            if(cat_id=='all') return false;
            $.ajax({ url:"/common/main2sub-categories-promotion/{{$promotion->id}}/"+ cat_id, method:"get",
                success:function(data){
                    $.each(data, function(index, value){
                        const selectedAttr = value.id == "{{ request()->sub_category_id }}" ? ' selected' : '';

                        $("[name=sub_category_id]").append('<option ' + selectedAttr + ' value="'+value.id+'">'+value.title+'</option>');
                    });
                }
            });
        }

        function child_categories(sub_cat){
            $.ajax({ url:"/common/sub2child-categories-promotion/{{$promotion->id}}/"+ sub_cat, method:"get",
                success:function(data){
                    $.each(data, function(index, value){
                        const selectedAttr = value.id == "{{ request()->child_category_id }}" ? ' selected' : '';
                        $("[name=child_category_id]").append('<option ' + selectedAttr + ' value="'+value.id+'">'+value.title+'</option>');
                    });
                }
            });
        }
    </script>

@if(request()->category_id)
    <script>
        setTimeout(() => {
            sub_categories("{{ request()->category_id }}")
        }, 200);

        setTimeout(() => {
            child_categories("{{ request()->sub_category_id }}")
        }, 400);
    </script>
@endif
@endpush