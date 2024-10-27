<div class="modal fade"  id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="addForm" class="modal-content" action="{{ route('common.campaign.create') }}" method="post" enctype="multipart/form-data"> @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Create campaign</h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="add_result"></div>
                @include('common.ad.campaign.form')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Data</button>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="editForm" class="modal-content" method="post" enctype="multipart/form-data"> @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit campaign</h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="edit_result"></div>
                @include('common.ad.campaign.form')
                <input type="hidden" name="id">
                <input type="hidden" class="oldPhoto" name="oldPhoto">
                <input type="hidden" class="oldVideo" name="oldVideo">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update Data</button>
            </div>
        </form>
    </div>
</div>

