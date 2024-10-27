<!-- Modal -->
<div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-capitalize" id="orderModalLabel">Modal title</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body modalData"></div>
      </div>
    </div>
</div>

<!-- Modal for payment-->
<div class="modal fade" id="paymentModal" tabindex="-1" role="dialog" aria-labelledby="paymentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title text-capitalize" id="paymentModalLabel">Ask for a payment</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
        </div>

        <div class="modal-body"> <div class="paymentResult"></div> </div>

      </div>
    </div>
</div>

{{-- delivery process modal  --}}
<div class="modal fade" id="deliveryModal" tabindex="-1" role="dialog" aria-labelledby="deliveryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="deliveryModalLabel">Order status details</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body delivery_result"></div>
      </div>
    </div>
</div>

{{-- modal for preparing shipping bunlde --}}
<div class="modal fade" id="shipModal" tabindex="-1" role="dialog" aria-labelledby="shipLable" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div id="editForm" class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Review order and Next details</h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body ready2Ship"> </div>
            <div class="modal-footer"> </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="dhlModal" tabindex="-1" role="dialog" aria-labelledby="dhlModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-capitalize" id="dhlModalLabel">Order shipment to <b>DHL</b></h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body dhlDetails"></div>
    </div>
  </div>
</div>