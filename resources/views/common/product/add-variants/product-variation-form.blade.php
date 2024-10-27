<p class="alert alert-info text-primary"> {{$product_combination->product->title}} - Combination-id: {{$product_combination->id}} </p>


<div class="combinationResult"></div>
<form>
    @foreach (explode('~',$product_combination->combination_string) as $string)
    <?php $vo = \App\Models\Variation_option::where('origin',$string)->select('id','title','variation_id')->first();?>
        <p id="vo{{$vo->id}}"><b> {{ $vo->variation->title}}: </b> {{ $vo->title }}
        <strong class="btn btn-sm btn-warning pt-1 ml-5 removeVO" data-vo_id="{{$vo->id}}" data-v_id="{{$vo->variation_id}}" data-product_id="{{$product_combination->product_id}}">x</strong> <br/> </p>
    @endforeach
    <input type="hidden" value="{{$product_combination->id}}" id="combination_id">
</form>
<br/>

<script>
 $(function(){
     $('.removeVO').on('click', function(){
        let vo_id = $(this).data('vo_id');
        let v_id = $(this).data('v_id');
        let product_id = $(this).data('product_id');
        let combination_id = $('#combination_id').val();

        $.get( url+"/common/catalog/product/update-product-variation/"+combination_id, {variation_id:v_id, option_id:vo_id, product_id:product_id}, function( data, status ) {
            if(data.success){
                $('.varientTable').DataTable().ajax.reload();
                $('#vo'+vo_id).remove();
                $(".combinationResult").html(data.success);
                setTimeout(function () {
                    $('#editVariationModal').modal('hide');
                }, 1000);

            }else{
                $(".combinationResult").html(data.warning);
            }
        });
     })
 })
</script>
