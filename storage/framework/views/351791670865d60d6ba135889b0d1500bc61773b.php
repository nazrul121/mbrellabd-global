<table class="table">
    <tr style="position: absolute;background: white;width: 98%; z-index: 5;">
        <th><input type="checkbox" name="checkAll" style="width:25px;height: 25px;">
        <b style="position: relative;top:-5px"> All </b></th>
        <th>Product information
            <input type="submit" value="Save the items" class="btn btn-secondary btn-submit" disabled style="position:absolute;top:4px;right:0;"
            name="add_items">
        </th>
    </tr>
    <tbody>
        <tr> <td colspan="2"></td></tr><tr> <td colspan="2"></td></tr>

        <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <tr class="itemRow" data-id="<?php echo e($item->product->id); ?>">
            <?php
            if($type=='child-group'){
                $childSeasonID = \DB::table('child_group_season')->where('child_group_id',$id)->pluck('id')->first();
                $count = \DB::table('child_group_season_product')->where(['child_group_season_id'=>$childSeasonID,'product_id'=>$item->product->id])->count();

            }
            else if($type=='inner-group'){
                $innerSeasonID = \DB::table('inner_group_season')->where('inner_group_id',$id)->pluck('id')->first();
                $count = \DB::table('inner_group_season_product')->where(['inner_group_season_id'=>$innerSeasonID,'product_id'=>$item->product->id])->count();
            }
            else{
                $groupSeasonID = \DB::table('group_season')->where('group_id',$id)->pluck('id')->first();
                $count = \DB::table('group_season_product')->where(['group_season_id'=>$groupSeasonID,'product_id'=>$item->product->id])->count();
            }
       
            ?>

            <td style="padding: 0.05rem 0.75rem;">
                <input class="itemCheck" <?php if($count >0): ?>checked <?php endif; ?> type="checkbox" style="width:20px;height:20px;position:relative;top:9px;" name="ids[]" id="item<?php echo e($item->product->id); ?>"  value="<?php echo e($item->product->id); ?>">
            </td>
            <td class="p-2" style="padding: 0.05rem 0.75rem;">
                <img class="img<?php echo e($item->product->id); ?>" src="<?php echo e($item->product->thumbs); ?>" style="height:25px">
                <label for="item<?php echo e($item->product->id); ?>"> &nbsp; <?php echo e($item->product->id); ?>. <?php echo e($item->product->title); ?> </label>
            </td>
        </tr>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </tbody>
</table>

<img src="" class="imgZoom">

<script>
    $('.itemCheck').on('click',function(){
        if($('.itemCheck').is(":checked")){
            $('.btn-submit').attr('disabled',false);
        }else{
            $('.btn-submit').attr('disabled',true);
        }
    })

    $('[name=checkAll]').on('change',function() {
        if (this.checked) {
            $(".itemCheck").each(function() {
                this.checked=true;
            });
            $('.btn-submit').attr('disabled',false);
        } else {
            $(".itemCheck").each(function() {
                this.checked=false;
            });
            $('.btn-submit').attr('disabled',true);
        }
    });

    $('.itemRow').on('click',function(){
        let id = $(this).data('id');
        let src = $('.img'+id).attr('src');
        $('.imgZoom').attr('src',src);
    })

</script>
<?php /**PATH D:\xampp-php-8.2\htdocs\laravelapp\resources\views/common/season/select-product.blade.php ENDPATH**/ ?>