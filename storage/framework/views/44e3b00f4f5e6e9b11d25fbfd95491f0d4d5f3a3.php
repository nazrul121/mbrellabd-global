<?php
$categories = \DB::table('groups')->get();
?>
<form class="form-inline"><?php echo csrf_field(); ?>
    <div class="form-group mx-sm-3 mb-2">
        <select name="category_id" class="form-control">
            <option value="">Choose Group</option>
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option <?php if(request()->get('category_id')==$cat->id): ?>selected <?php endif; ?> value="<?php echo e($cat->id); ?>"><?php echo e($cat->title); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </select>
    </div>
    <div class="form-group mx-sm-3 mb-2">
        <select name="sub_category_id" class="form-control">
            <option value="">Choose Sub-category</option>
            <?php if(request()->get('category_id')){
                $sub_cats = \DB::table('inner_groups')->where('group_id', request()->get('category_id'))->get();
            }else $sub_cats = array();?>
            <?php $__currentLoopData = $sub_cats; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option <?php if(request()->get('sub_category_id')==$sub->id): ?>selected <?php endif; ?> value="<?php echo e($sub->id); ?>"><?php echo e($sub->title); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

        </select>
    </div>
    <div class="form-group mx-sm-3 mb-2">
        <select name="child_category_id" class="form-control">
            <option value="">Choose Child-category</option>
            <?php if(request()->get('sub_category_id')){
                $child_groups = \DB::table('child_groups')->where('inner_group_id', request()->get('sub_category_id'))->get();
            }else $child_groups = array();?>
            <?php $__currentLoopData = $child_groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option <?php if(request()->get('child_category_id')==$child->id): ?>selected <?php endif; ?> value="<?php echo e($child->id); ?>"><?php echo e($child->title); ?></option>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>


        </select>
    </div>
    <div class="form-group mx-sm-3 mb-2">
        <select name="design_code" class="form-control">
            <option value="">Design Year</option>
            <?php $year = 2015; // staring year ?>
            <?php for($i = $year; $year <= date('Y') +1; $year++): ?>
                <option <?php if(request()->get('design_code')==$year): ?>selected <?php endif; ?> value="<?php echo e($year); ?>"><?php echo e($year); ?></option>
            <?php endfor; ?>
        </select>
    </div>
    <button type="submit" class="btn btn-primary mb-2">Search</button>
</form>
<?php /**PATH D:\xampp-php-8.2\htdocs\laravelapp\resources\views/common/product/search-form.blade.php ENDPATH**/ ?>