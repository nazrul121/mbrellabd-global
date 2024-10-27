<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-capitalize" id="addModalLabel">Add new product into the order</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
           <form action="{{ route('common.add-item-into-order',$order->id) }}" method="post" id="addToCart">@csrf
            <input type="text" class="form-control " name="product" id="title" placeholder="search proudct with Name">
            <div id="product_list" style="max-height:300px;overflow-y:scroll" ></div>
            <input type="hidden" name="product_id">
            <select name="variation" class="form-control mt-4 mb-4">
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



<div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="addressModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-capitalize" id="addressModalLabel">Edit address</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
           <form action="{{ route('common.update-order-address',$order->id) }}" method="post" id="addAddress">@csrf
                <div class="shippingForm" style="display:none"> @include('common.order.edit.shipping-address') </div>
                <div class="billingForm" style="display:none"> @include('common.order.edit.billing-address') </div>
                <input type="hidden" name="type">
                <input type="hidden" name="id">
                <button class="btn btn-info float-right mr-0"><b class="fa fa-edit text-white"></b> Edit address</button>
            </form>
        </div>
      </div>
    </div>
</div>
