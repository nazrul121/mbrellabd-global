
<div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="addressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-capitalize" id="addressModalLabel">Create customer and address</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
           <form action="<?php echo e(route('common.order.save-address')); ?>" method="post" id="addressForm"><?php echo csrf_field(); ?>

                <div class="billingForm"> <?php echo $__env->make('common.order.create.billing-address', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> </div>

                <div class="shippingForm"> <?php echo $__env->make('common.order.create.shipping-address', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> </div>

                <input type="hidden" name="type">
                <input type="hidden" name="id">
                <button class="btn btn-info float-right mr-0" type="submit"><b class="fa fa-edit text-white"></b> Continue order</button>
            </form>
        </div>
      </div>
    </div>
</div>



<div class="modal fade" id="itemModal" tabindex="-1" role="dialog" aria-labelledby="itemModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-capitalize" id="itemModalLabel">Add product to Cart</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
            <form action="<?php echo e(route('common.order.create')); ?>" method="get" id="addToCart"><?php echo csrf_field(); ?>
                <input type="text" class="form-control " name="product" id="title" placeholder="search proudct with Name">
                <div id="product_list" style="max-height:300px;overflow-y:scroll" ></div>
                <input type="hidden" name="product_id">
                <select name="combination" class="form-control mt-4 mb-4">
                    <option value="">Choose Variation</option>
                </select>

                

                <div class="row">
                    <div class="col-md-5 text-right"><label class="pt-2 " for="">Quantity</label></div>
                    <div class="col-md-7"><input type="number" name="qty" value="1" min="1" class="form-control"></div>
                </div>
                <button class="btn btn-primary pr-4 mr-0 float-right mt-3 addToCartBtn" type="submit"  disabled><i class="feather icon-arrow-down"></i> add to order</button>
            </form>
        </div>
      </div>
    </div>
</div>


<?php /**PATH /var/www/laravelapp/resources/views/common/order/create/modal.blade.php ENDPATH**/ ?>