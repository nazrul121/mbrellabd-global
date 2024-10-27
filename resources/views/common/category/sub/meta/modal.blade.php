<div class="modal fade"  id="addMetaModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="addMetaForm" class="modal-content" action="{{ route('common.inner-group-meta.create') }}" method="post" enctype="multipart/form-data"> @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Add new meta info</h5>
                <button type="button" class="colseAddMeta" ><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="add_result"></div>
                @include('common.category.sub.meta.form')
                <input type="hidden" name="inner_group_id" value="{{ $inner_group->id }}">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary colseAddMeta">Close</button>
                <button type="submit" class="btn btn-primary">Save Data</button>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" id="editMetaModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="editMetaForm" class="modal-content" action="#" method="post" enctype="multipart/form-data"> @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Meta information</h5>
                <button type="button" class="close-modal colseEditMeta" ><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="edit_result"></div>
                @include('common.category.sub.meta.form')
                <input type="hidden" name="inner_group_id" value="{{ $inner_group->id }}">
                <input type="hidden" name="id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary colseEditMeta">Close</button>
                <button type="submit" class="btn btn-primary">Update Data</button>
            </div>
        </form>
    </div>
</div>

