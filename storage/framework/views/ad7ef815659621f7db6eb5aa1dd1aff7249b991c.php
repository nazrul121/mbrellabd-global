
<form action="<?php echo e(route('common.season.search',$season->id)); ?>"> <?php echo csrf_field(); ?>
    <div class="form-group row">
       <div class="col-md-4">
            <label for="">main Group</label>
            <select name="group" class="form-control">
                <option value="">Choose group</option>
                <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($item->id); ?>"><?php echo e($item->title); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
       </div>

       <div class="col-md-4">
            <label for="">Sub Groups</label>
            <select name="inner_group" class="form-control">
                <option value="">Choose sub-group</option>
            </select>
       </div>

       <div class="col-md-4">
            <label for="">Child Group</label>
            <select name="child_group" class="form-control">
                <option value="">Choose child group</option>
            </select>
       </div>
    </div>

    <div class="itemArea">
        <div class="products"></div>
    </div>
</form>

<?php echo e($season->id); ?>

<input type="hidden" name='season_id' value="<?php echo e($season->id); ?>">

<style>
    .imgZoom{
        position: absolute; left: 82%;
        z-index: 999;top: 20%;
        background: white;
    }
</style>
<script>
    $(function(){
        $('[name=group]').on('change', function(){
            let id = $(this).val();
            $("[name=inner_group]").html(''); $("[name=child_group]").html('');

            $.ajax({ url:url+"/common/main2sub-categories/<?php echo e($season->id); ?>/"+ id, method:"get",
                success:function(data){
                    $("[name=inner_group]").append('<option value="">Choose sub-group</option>');
                    $("[name=child_group]").append('<option value="">Choose child-group</option>');
                    $.each(data, function(index, value){
                        $("[name=inner_group]").append('<option value="'+value.id+'">'+value.title+'</option>');
                    });
                }
            });

            $.get(url+"/common/catalog/season/get-product/<?php echo e($season->id); ?>/group/"+id, function(data, status){
                $('.itemArea').css('height','100vh');
                $('.itemArea').css('overflow-y','scroll');
                $('.products').html(data);
            });

            // let season = $('[name=season_id]').val();
            // $.get(url+"/common/catalog/season/update-group-season/"+season+'/'+id);

        });

        $('[name=inner_group]').on('change', function(){
            let id = $(this).val();
            $("[name=child_group]").html('');

            $.ajax({ url:url+"/common/sub2child-categories/<?php echo e($season->id); ?>/"+ id, method:"get",
                success:function(data){
                    $("[name=child_group]").append('<option value="">Choose child-group</option>');
                    $.each(data, function(index, value){
                        $("[name=child_group]").append('<option value="'+value.id+'">'+value.title+'</option>');
                    });
                }
            });
            let group_id = $('[name=group]').val();
            $.get(url+"/common/catalog/season/get-product/<?php echo e($season->id); ?>/inner-group/"+id, function(data, status){
                $('.products').html(data);
                $('.itemArea').css('height','100vh');
                $('.itemArea').css('overflow-y','scroll');
            });

            // let season = $('[name=season_id]').val();
            // $.get(url+"/common/catalog/season/update-sub-group-season/"+season+'/'+id, function(data, status){});
        });
        

        $('[name=child_group]').on('change', function(){
            let id = $(this).val();
            $.get(url+"/common/catalog/season/get-product/<?php echo e($season->id); ?>/child-group/"+id, function(data, status){
                $('.products').html(data);
                $('.itemArea').css('height','100vh');
                $('.itemArea').css('overflow-y','scroll');
            });
            // let season = $('[name=season_id]').val();
            // $.get(url+"/common/catalog/season/update-child-group-season/"+season+'/'+id);
        })
    })
</script>
<?php /**PATH D:\xampp-php-8.2\htdocs\laravelapp\resources\views/common/season/search.blade.php ENDPATH**/ ?>