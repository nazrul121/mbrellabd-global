<div class="modal fade"  id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="addForm" class="modal-content" action="{{ route('common.promotion.create') }}" method="post" enctype="multipart/form-data"> @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Create <b>{{ $promotion_type->title }}</b> Promotion</h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="add_result"></div>
                @include('common.ad.promotion.form')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary"><i class="feather icon-save"></i> Save Data</button>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="editForm" class="modal-content" method="post" enctype="multipart/form-data"> @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Edit Promotion</h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="edit_result"></div>
                @include('common.ad.promotion.form')
                <input type="hidden" name="id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary"><i class="feather icon-check"></i>  Update Data</button>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" id="extendModal" tabindex="-1" role="dialog" aria-labelledby="customerModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width:80%" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="customerModalLabel" style="width:100%">Promotion details</h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"> x </span></button>
            </div>
            <div class="modal-body showExtends"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>




<div class="modal fade" id="productModal" tabindex="-1" role="dialog" aria-labelledby="productModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="max-width:75%" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="productModalLabel" style="width:100%">Promotion products
                    <button class="btn btn-light text-danger btn-sm removeProItems" disabled><i class="fas fa-trash mr-0"></i></button>
                </h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"> x </span></button>
            </div>
            <div class="modal-body showProducts"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
