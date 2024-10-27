<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="editModalLable" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="width:100%;background:#d1ecf0;">
                <h4>Edit photo</h4>
                <button type="button" class="close-modal close-photo-modal"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="editResult"></div>
                <form class="modal-body" id="editVPhotoForm" method="post" enctype="multipart/form-data" action="{{ route('common.update-variation-photo',$product->id) }}">@csrf
                    <input type="hidden" name="old_photo" >
                    <input type="hidden" name="variation_id">
                    <input type="hidden" name="option_id">
                    @include('common.product.variation-photo.form')
                    <div class="input-field float-right">
                        <button type="submit" class="btn btn-info mb-2 mt-2"> Update photo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>



<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLable" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="width:100%;background:#d1ecf0;">
                <h4>Add photos</h4>
                <button type="button" class="close-modal close-photo-modal"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="addResult"></div>
                <form class="modal-body" id="addForm" method="post" action="{{ route('common.upload-variation-photo',$product->id) }}" enctype="multipart/form-data">@csrf
                    @include('common.product.variation-photo.form')
                    <div class="input-field float-right">
                        <button type="submit" class="btn btn-info mb-2 mt-2"> Upload photo</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

