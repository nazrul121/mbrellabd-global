


<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"> <h5>DHL - Box setup</h5> 
                <div class="card-header-right">
                    <a href="<?php echo e(route('common.dhl-zone-price-setup')); ?>" class="btn btn-outline-info">Zone setup</a>
                </div>
            </div>

            <div class="card-body">
                <?php $groups = \App\Models\Inner_group::leftJoin('child_groups', 'inner_groups.id', '=', 'child_groups.inner_group_id')
                    ->join('groups', 'inner_groups.group_id', '=', 'groups.id')
                    ->where('groups.status', '1')
                    ->select(
                        'child_groups.id as child_id',
                        'inner_groups.id as inner_id',
                        'groups.id as group_id',
                        'child_groups.title as child_title',
                        'inner_groups.title as inner_title',
                        'groups.title as group_title'
                    )
                    ->where('inner_groups.status', '1')
                    ->orderBy('groups.title', 'DESC')
                    ->get();

                    $smallWeight = 1.5;
                    $largeWeight = 3.5;
                ?>
                <?php if(session('success')): ?>
                    <p class="alert p-3 text-center alert-success"><i class="fas fa-check"></i> <?php echo e(session('success')); ?></p>
                <?php endif; ?>

                <form action="<?php echo e(route('common.update-dhl-setup')); ?>" method="post"> <?php echo csrf_field(); ?>

                    <table class="table table-hover">
                        <thead>
                            <th>#</th><th> Groups</th> <th>G. Name</th> <th>P. Name</th>
                            <th>Small cap.</th>  <th>Large cap.</th>  <th>FlyerSmall cap.</th>  <th>FlyerLarge cap.</th> <th>HS code</th>
                        </thead>
                        <tbody>
                            <?php
                                $grouped = $groups->groupBy('group_title');
                                $key =1;
                            ?>
                    
                            <?php $__currentLoopData = $grouped; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $groupTitle => $innerGroups): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php $__currentLoopData = $innerGroups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $innerGroup): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?> <!-- Passing $key directly -->
                                    <?php if($innerGroup->group_title == $groupTitle): ?>
                                        <!-- Hidden Inputs -->
                                        <input type="hidden" name="small_weight[]" value="<?php echo e($smallWeight); ?>">
                                        <input type="hidden" name="large_weight[]" value="<?php echo e($largeWeight); ?>">
                                        <input type="hidden" name="flyer_small_weight[]" value="">
                                        <input type="hidden" name="flyer_large_weight[]" value="">
                            
                                        <tr>
                                            <td><?php echo e($key); ?></td>
                                            <td><?php echo e($groupTitle); ?></td>
                                            <td><?php echo e($innerGroup->inner_title); ?></td>
                                            <td><?php echo e($innerGroup->child_title ?? 'N/A'); ?></td>
                                            <?php
                                                // Prepare $pwData based on the presence of child_title
                                                $pwData = ['group_id' => $innerGroup->group_id, 'inner_group_id' => $innerGroup->inner_id];
                                                if ($innerGroup->child_title != 'N/A') {
                                                    $pwData['child_group_id'] = $innerGroup->child_id;
                                                }
                            
                                                // Fetch boxRow and hsCode based on $pwData
                                                $boxRow = DB::table('dhl_boxes')->where($pwData)->first();
                                                $hsCode = DB::table('product_weights')->where($pwData)->pluck('hs_code')->first();
                                            ?>
                            
                                            <!-- Hidden Inputs for Groups -->
                                            <input type="hidden" name="groups[]" value="<?php echo e($innerGroup->group_id); ?>">
                                            <input type="hidden" name="inner_groups[]" value="<?php echo e($innerGroup->inner_id); ?>">
                                            <input type="hidden" name="child_groups[]" value="<?php echo e($innerGroup->child_id); ?>">
                            
                                            <!-- Quantity Inputs -->
                                            <td><input type="text" name="small_qty[]" value="<?php echo e($boxRow->small_qty ?? ''); ?>"></td>
                                            <td><input style="width:100px" type="text" name="large_qty[]" value="<?php echo e($boxRow->large_qty ?? ''); ?>"></td>
                                            <td><input style="width:100px" type="text" name="flyer_small_qty[]" value="<?php echo e($boxRow->flyer_small_qty ?? ''); ?>"></td>
                                            <td><input style="width:100px" type="text" name="flyer_large_qty[]" value="<?php echo e($boxRow->flyer_large_qty ?? ''); ?>"></td>
                                            <td><input style="width:100px" type="text" name="hs_code[]" value="<?php echo e($hsCode ?? ''); ?>"></td>
                                        </tr>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                
                        </tbody>
                    </table>
                    <button class="btn btn-primary float-md-right"><i class="fa fa-edit"></i> Update</button>
                </form>
                <p class="text-cente">SmallBox weight: <b><?php echo e($smallWeight); ?></b>, LargeBox weight: <b><?php echo e($largeWeight); ?></b></p>
            </div>
        </div>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('common.layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /var/www/laravelapp/resources/views/common/courier/dhl/index.blade.php ENDPATH**/ ?>