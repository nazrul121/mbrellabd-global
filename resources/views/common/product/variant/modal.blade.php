<div class="modal fade"  id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="addForm" class="modal-content" action="{{ route('common.variant.create') }}" method="post" enctype="multipart/form-data"> @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Create new Variation</h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="add_result"></div>
                @include('common.product.variant.form')
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
                <h5 class="modal-title" id="editModalLabel">Edit Variation</h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="edit_result"></div>
                @include('common.product.variant.form')
                <input type="hidden" name="id">
                <input type="hidden" class="oldPhoto" name="oldPhoto">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update Data</button>
            </div>
        </form>
    </div>
</div>



<div class="modal fade" id="addExtModal" tabindex="-1" role="dialog" aria-labelledby="eaddExtModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="addExtForm" class="modal-content" method="post" enctype="multipart/form-data" action="{{ route('common.variant-option.create') }}"> @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="addExtModalLabel"></h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="addExt_result"></div>
                @include('common.product.variant.option.form')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Update Data</button>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="showVOModal" tabindex="-1" role="dialog" aria-labelledby="eaddExtModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <form id="" class="modal-content" >
            <div class="modal-header">
                <h5 class="modal-title" id="showVOLabel"></h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body" id="showVO"> </div>

        </form>
    </div>
</div>


