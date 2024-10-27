<table class="table">
    <tr> <th>Product Info</th></tr>
    <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <?php $check = \App\Models\Product_promotion::where(['product_id'=>$product->id,'promotion_id'=>$promotion->id,'status'=>'1']);?>
        <?php if($is_variation=='yes'): ?>
            <?php $product_combination = $product->product_combinations()->select('id','combination_string','qty')->get();?>
            <?php $__currentLoopData = $product_combination; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <tr class="">
                <td class="p-0 pt-2">
                    <label for="pp<?php echo e($comb->id); ?>">
                        <input type="checkbox" class="product" data-comb="<?php echo e($comb->id); ?>" style="width:30px;height:30px"
                         <?php if($check->count()>0): ?>disabled <?php endif; ?> <?php echo e($checkUncheck); ?>

                        id="pp<?php echo e($comb->id); ?>" name="product_ids[]" value="<?php echo e($product->id); ?>">

                        <div style="top:1px;position:relative;float:right;font-size:17px;padding-left:1em;padding-right:1em;">  <?php echo e($product->title); ?>

                            <input type="checkbox" style="width:15px;height:15px;" class="v<?php echo e($comb->id); ?>" name="combination_id[]" value="<?php echo e($comb->id); ?>"  
                            onclick="return false;"/>

                            <?php $__currentLoopData = explode('~',$comb->combination_string); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $string): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $option = \App\Models\Variation_option::where('origin',$string)->select('variation_id')->first();?>
                                <?php if( $option ==null): ?> Nazrul -- <?php echo e($string); ?> -- <?php echo e($product->id); ?>--
                                <?php else: ?>
                                    <small class="badge badge-secondary"><?php echo e($option->variation->title); ?>: <?php echo e($string); ?></small>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <small><b>Qty: </b><?php echo e($comb->qty); ?></small>
                            <img src="<?php echo e($product->thumbs); ?>" style="height:40px">
                        </div>
                    </label>
                    <?php if($check->count()>0): ?>  <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
            <tr class="">
                <td class="p-0 pt-2">
                    <label for="proudct<?php echo e($product->id); ?>">
                        <input type="checkbox" style="width:30px;height:30px" <?php if($check->count()>0): ?>disabled checked <?php endif; ?> <?php echo e($checkUncheck); ?> id="proudct<?php echo e($product->id); ?>" name="product_ids[]" value="<?php echo e($product->id); ?>">
                        <div style="top:1px;position:relative;float:right;font-size:17px;padding-left:1em;padding-right:1em;">
                            <?php echo e($product->title); ?> :  <?php echo e($product->design_code); ?> <img src="<?php echo e($product->thumbs); ?>" style="height:40px">
                        </div>
                    </label>

                </td>
            </tr>
        <?php endif; ?>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
<?php /**PATH /var/www/laravelapp/resources/views/common/ad/promotion/flat/show-products.blade.php ENDPATH**/ ?>