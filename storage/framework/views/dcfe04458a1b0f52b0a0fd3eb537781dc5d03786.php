<div class="filter-widget">
    <div class="filter-header faq-heading heading_18 d-flex align-items-center justify-content-between border-bottom" data-bs-toggle="collapse" data-bs-target="#filter-collection">
        <?php if(strlen($title) >25): ?><?php echo e(substr($title, 0, 25)); ?> ... <?php else: ?> <?php echo e($title); ?> <?php endif; ?>
        <span class="faq-heading-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" class="icon icon-down">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </span>
    </div>
    <div id="filter-collection" class="accordion-collapse collapse show">
        <ul class="filter-lists list-unstyled mb-0">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="filter-item">
                <label class="filter-label checkCategory">
                    <input type="checkbox" name="group_id"  value="<?php echo e($type.'-'.$cat->id.'-'.$cat->slug); ?>"
                    <?php if(request()->get('category') !='' && strpos( str_replace(' ','-',request()->get('category') ), $type.'-'.$cat->id.'-'.$cat->slug ) !== false): ?> checked <?php endif; ?> <?php echo e($child_selected); ?> />
                    <span class="filter-checkbox rounded me-2"></span>
                    <span class="filter-text"><?php echo e($cat->title); ?></span>
                </label>
            </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div>
</div>

<?php $__currentLoopData = $variations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$variation): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
<div class="filter-widget">
    <div class="filter-header faq-heading heading_18 d-flex align-items-center justify-content-between border-bottom"
        data-bs-toggle="collapse" data-bs-target="#filter-availability<?php echo e($key); ?>"> <?php echo e($variation->title); ?>

        <span class="faq-heading-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" class="icon icon-down">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </span>
    </div>
 
    <div id="filter-availability<?php echo e($key); ?>" class="accordion-collapse collapse show">
        <ul class="filter-lists list-unstyled mb-0 variantScroll">
            <?php $__currentLoopData = $variation->variation_options()->orderBy('title','asc')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key2=>$row): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $checkVo = \App\Models\Product_variation_option::where('variation_option_id',$row->id)->whereIn('product_id',$product_ids);?>
                <?php if($checkVo->count()>0): ?>
                
                <li class="filter-item">
                    <label class="filter-label colors">
                        <input type="checkbox" name="color_input" <?php if(str_contains(request()->get('color'), $row->title)): ?>checked <?php endif; ?> value="<?php echo e($row->id.'-'.$row->title); ?>" />
                        <span class="filter-checkbox rounded me-2" style="<?php if(strpos(strtolower(strtolower($variation->title)),'color') !== false): ?>
                            border:2px solid <?php if(strpos(strtolower($row->title),'white') !== false || strpos(strtolower($row->code),'#fff') !== false): ?> <?php echo e($row->code); ?> <?php else: ?> #113c41; <?php endif; ?>
                            background: <?php if(strpos(strtolower($row->title),'white') !== false || strpos(strtolower($row->code),'#fff') !== false): ?>#113c41 <?php else: ?> <?php echo e($row->code); ?> <?php endif; ?>
                        <?php endif; ?>" ></span>
                        <span class="filter-text" style=" color: <?php if(strpos(strtolower($row->title),'white') !== false || strpos(strtolower($row->code),'#fff') !== false): ?>#113c41 <?php else: ?> <?php echo e($row->code); ?> <?php endif; ?>"><?php echo e($row->title); ?></span>
                    </label>
                </li>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>
    </div> 
</div>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>



<div class="filter-widget mb-2">
    <div class="filter-header faq-heading heading_18 d-flex align-items-center justify-content-between border-bottom" data-bs-toggle="collapse" data-bs-target="#filter-price" aria-expanded="true">
        Price Range ( <?php echo e(session('user_currency')->currencySymbol); ?> ) <span class="faq-heading-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-down">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </span>
    </div>
    <div id="filter-price" class="accordion-collapse collapse show">
        <div class="filter-price d-flex align-items-center justify-content-between">
            <div class="filter-field">
                <input class="field-input minNumber" type="number" placeholder="<?php echo e(Session::get('user_currency')->currencySymbol); ?> 0" min="0" value="<?php echo e(number_format( (float)$min, 2)); ?>" max="2000.00">
            </div>
            <div class="filter-separator px-3">To</div>
            <div class="filter-field">
                <input class="field-input maxNumber" type="number" min="0" placeholder="<?php echo e(Session::get('user_currency')->currencySymbol.$max); ?>" value="<?php echo e(number_format( (float)$max, 2)); ?>" max="2000.00">
            </div>
        </div>
        <br>
        <a href="#" class="priceHref"> <button class="position-relative btn-atc">Search with price</button></a>
    </div>
</div>
<?php /**PATH D:\xampp-php-8.2\htdocs\laravelapp\resources\views/includes/product/filter.blade.php ENDPATH**/ ?>