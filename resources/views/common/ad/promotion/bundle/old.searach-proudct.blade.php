<div class="row">
    <div class="col-md-3 text-right pt-2">
        <label for="">Search product by Name/Code</label> <br> <hr> <br>
        <button class="btn btn-sm btn-secondary viewBundles">View bundles of the promotion</a>
    </div>
    <form class="col-md-8" id="bundleForm" action="{{ route('common.temp-bundles',$promotion->id) }}">@csrf
        <div class="input-group mb-3">
            <input type="text" class="form-control " name="product" id="title" placeholder="search proudct with Name">
            <div class="input-group-append">
            <button class="btn btn-primary" type="button" id="addToBundle"><i class="feather icon-arrow-down"></i> add</button>
            </div>
        </div>
        <div id="product_list" style="max-height:300px;overflow-y:scroll" ></div>
        <input type="hidden" name="product_id">
        <input type="hidden" name="promotion_id" value="{{ $promotion->id }}">

        <div class="bundleItems row"> </div>
    </form>
</div>


<script>
    $(document).ready(function () {
        $( ".bundleItems" ).load( "/common/ad/promotion/temp-bundles/{{ $promotion->id }}");

        $('#title').on('keyup', function(){
            var query = $(this).val();
            if(query.length >1){
                $.ajax({
                    url:"{{ route('common.highlight.searach-products') }}",
                    type:'GET', data:{'name':query},
                    success:function (data) { $('#product_list').html(data);}
                })
            }else $('#product_list').html('');
        });

        $(document).on('click', 'li', function(){
            var value = $(this).text();
            var id = $(this).data('id');
            $('[name=product_id]').val(id);
            $('#title').val(value);
            $('#product_list').html("");
            $("[type='submit']").prop('disabled',false);
        });


        $(document).on("click", "#addToBundle", function(){
            var action = $('#bundleForm').attr('action');
            var product_id = $('[name=product_id]').val();
            var promotion_id = $("[name=promotion_id]").val();

            $.get(action,{product_id:product_id, promotion_id:promotion_id}, function( data ) {
                $('.bundleItems').html(data);
                $('#title').val('');
                $('[name=product_id]').val('');
            });
        });

        $('.viewBundles').on('click',function(){
            $('.promotionBundles').slideDown();
            $('.addBundle').slideUp();
        })

    });


</script>
