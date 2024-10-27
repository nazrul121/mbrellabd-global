<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg mt-5" role="document">
    <form class="modal-content" id="addForm" method="post" action="{{ route('customer.save-address', app()->getLocale()) }}">@csrf
        <div class="modal-header">
            <h5 class="modal-title" id="addModalTitle">Create new address</h5>
        </div>
        <div class="modal-body">
            <div class="add_result"></div>
            @include('customer.address.form')
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary close" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success" >Save Data</button>
        </div>
    </form>
    </div>
</div>


<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="addModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg mt-5" role="document">
    <form class="modal-content" id="editForm" method="post" >@csrf
        <div class="modal-header">
            <h5 class="modal-title" id="addModalTitle">Edit address</h5>
        </div>
        <div class="modal-body">
            <div class="edit_result"></div>
            @include('customer.address.form')
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary close" data-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-success" >Update Data</button>
        </div>
    </form>
    </div>
</div>


