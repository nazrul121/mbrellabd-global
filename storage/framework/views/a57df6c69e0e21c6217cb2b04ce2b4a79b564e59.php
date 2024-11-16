<?php $__env->startSection('title', 'My profile'); ?>

<?php $__env->startSection('content'); ?>

    <div class="accordion-item">
        <?php if(session()->get('message')): ?> 
            <p class="alert bg-5 text-success text-center"><?php echo e(session()->get('message')); ?></p>
        <?php endif; ?>

       
        <h2 class="accordion-header" id="headingOne">
            <button type="button" class="accordion-button" data-bs-toggle="collapse" data-bs-target="#collapseOne">Billing information</button>
        </h2>
        <div id="collapseOne" class="accordion-collapse collapse show" data-bs-parent="#myAccordion">
            <form class="card-body" action="<?php echo e(route('customer.update.account', app()->getLocale())); ?>" method="post"><?php echo csrf_field(); ?>
                <?php echo $__env->make('customer.includes.profile-form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

                <div class="row mt-4">
                    <div class="col-md-12">
                        <button class="btuton float-end btn-primary" type="submit"> <i class="fa fa-edit"></i> &nbsp; Update profile info</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script>
        $(function(){
            get_district(<?php echo e(Auth::user()->customer->division_id); ?>)
            get_cities(<?php echo e(Auth::user()->customer->district_id); ?>)

            setTimeout(function() {
                $('[name=district] option[value="<?php echo e(Auth::user()->customer->district_id); ?>"]').prop('selected', true);
                $('[name=city] option[value="<?php echo e(Auth::user()->customer->city_id); ?>"]').prop('selected', true);
            }, 500);
        })

        function get_district(id){
            $.ajax({
                url:url+"/get-districts/"+ id, method:"get",
                success:function(data){
                    $.each(data, function(index, value){
                        $("[name=district]").append('<option value="'+value.id+'">'+value.name+'</option>');
                    });
                }
            });
        }

        function get_cities(id){
            $.ajax({ url:url+"/get-cities/"+ id, method:"get",
                success:function(data){
                    $.each(data, function(index, value){
                        $("[name=city]").append('<option value="'+value.id+'">'+value.name+'</option>');
                    });
                }
            });
        }
    </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('customer.layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp-php-8.2\htdocs\laravelapp\resources\views/customer/profile.blade.php ENDPATH**/ ?>