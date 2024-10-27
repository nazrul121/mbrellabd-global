<div class="modal"  id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="addForm" class="modal-content" method="post" action="{{route('common.save-meta')}}" enctype="multipart/form-data"> @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Create news</h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="add_result"></div>
                @include('common.meta.form')
                <input type="hidden" name="id" id="id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Data</button>
            </div>
        </form>
    </div>
</div>
