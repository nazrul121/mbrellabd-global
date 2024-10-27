<!-- Modal description -->
<div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-body modalData"></div>
      </div>
    </div>
</div>


{{-- edit status modal  --}}
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editModalLabel">Edit Order status</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"> <span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
            <form id="editForm" method="post"> @csrf
                @include('common.order.status.form')
                <div class="form-group">
                    <button class="btn btn-secondary float-right mr-0" type="submit">Update Status</button>
                </div>
            </form>
        </div>
      </div>
    </div>
</div>
