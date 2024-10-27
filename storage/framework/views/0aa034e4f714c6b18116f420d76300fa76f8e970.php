<div class="filter-sort-wrapper d-flex justify-content-between flex-wrap">
    <div class="collection-title-wrap d-flex align-items-end">
        <h2 class="collection-title heading_24 mb-0"><?php echo e($title); ?>

            <?php if($class =='promoDetails'): ?>
                &nbsp; &nbsp; &nbsp; <small class="promoDetails"> .... details</small>
            <?php endif; ?>
        </h2>
    </div>
    <div class="filter-sorting">
        <div class="collection-sorting position-relative d-none d-lg-block">
            <select name="sortBy" id="sortBy" class="form-select block w-full text-heading text-sm font-semibold cursor-pointer product-sorting border-0">
                <option value="" selected disabled>Sorting Options</option>
                
                <option value="newest" <?php if(request()->sorting=='newest'): ?>selected <?php endif; ?> >Sort by: Newest</option>
                <option value="oldest" <?php if(request()->sorting=='oldest'): ?>selected <?php endif; ?> >Sort by: Oldest</option>
                <option value="low-price" <?php if(request()->sorting=='low-price'): ?>selected <?php endif; ?> >Price (Low to High)</option>
                <option value="high-price" <?php if(request()->sorting=='high-price'): ?>selected <?php endif; ?> >Price (High to Low)</option>
            </select>
        </div>
        <div class="filter-drawer-trigger mobile-filter d-flex align-items-center d-lg-none">
            <span class="mobile-filter-icon me-2">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                    viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                    stroke-linecap="round" stroke-linejoin="round" class="icon icon-filter">
                    <polygon points="22 3 2 3 10 12.46 10 19 14 21 14 12.46 22 3"></polygon>
                </svg>
            </span>
            <span class="mobile-filter-heading">Filter and Sorting</span>
        </div>
    </div>
</div>



<?php /**PATH D:\xampp-php-8.2\htdocs\laravelapp\resources\views/includes/product/sorting.blade.php ENDPATH**/ ?>