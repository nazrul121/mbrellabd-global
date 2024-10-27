<table class="table">
    <tr> <th>Product Info</th></tr>
    @foreach ($products as $product)
        <?php $check = \App\Models\Product_promotion::where(['product_id'=>$product->id,'promotion_id'=>$promotion->id,'status'=>'1']);?>
        @if($is_variation=='yes')
            <?php $product_combination = $product->product_combinations()->select('id','combination_string','qty')->get();?>
            @foreach ($product_combination as $comb)
            <tr class="">
                <td class="p-0 pt-2">
                    <label for="pp{{ $comb->id }}" class="pl-3">
                        <input type="checkbox" class="product" data-comb="{{ $comb->id }}" style="width:25px;height:30px;"
                        @if($check->count()>0)disabled @endif {{ $checkUncheck }} id="pp{{ $comb->id }}" name="product_ids[]" value="{{ $product->id }}">

                        <div style="position:relative;float:right;font-size:17px;padding-left:1em;padding-right:1em;margin-top:-6px;">  {{ $product->title }}
                            <input type="checkbox" style="width:25px;height:25px;" class="v{{ $comb->id }}" name="combination_id[]" value="{{ $comb->id }}"  
                            onclick="return false;"/>

                            @foreach (explode('~',$comb->combination_string) as $string)
                                <?php $option = \App\Models\Variation_option::where('origin',$string)->select('variation_id')->first();?>
                                @if( $option ==null) Nazrul -- {{ $string }} -- {{ $product->id }}--
                                @else
                                    <small class="badge badge-secondary">{{ $option->variation->title }}: {{ $string }}</small>
                                @endif
                            @endforeach
                            <small><b>Qty: </b>{{ $comb->qty }}</small>
                            <img src="{{ $product->thumbs }}" style="height:35px">
                        </div>
                    </label>
                    @if($check->count()>0)  @endif
                </td>
            </tr>
            @endforeach
        @else
            <tr class="">
                <td class="p-0 pt-2">
                    <label for="proudct{{ $product->id }}">
                        <input type="checkbox" style="width:25px;height:25px;" @if($check->count()>0)disabled checked @endif {{ $checkUncheck }} id="proudct{{ $product->id }}" name="product_ids[]" value="{{ $product->id }}">
                        <div style="position:relative;float:right;font-size:17px;padding-left:1em;padding-right:1em;margin-top:-6px;">
                            {{ $product->title }} :  {{ $product->design_code }} <img src="{{ $product->thumbs }}" style="height:40px">
                        </div>
                    </label>

                </td>
            </tr>
        @endif
    @endforeach
</table>

<script>
    $(function(){
        $('.product').on('change',function(){
            let id =$(this).val();
            let combo_id =$(this).data('comb');
            if($(this).prop("checked") == true){
                // $('.v'+v_id).prop("disabled",false);
                $('.v'+combo_id).prop("checked",true);
            }
            else if($(this).prop("checked") == false){
                $('.v'+combo_id).prop("checked",false);
                if(combo_id == ''){
                    $('.discount_in'+id+' option:selected').prop('selected', false);
                    $('.discount_value'+id).val(null);
                }else{
                    $('.discount_in'+combo_id+' option:selected').prop('selected', false);
                    $('.discount_value'+combo_id).val(null);
                }
            }
        });
    })
</script>
