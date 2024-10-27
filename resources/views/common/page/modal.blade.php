<div class="modal fade" id="coverModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="addForm" class="modal-content" action="{{ route('common.page-post.cover-photo', $page_post_type->id) }}" method="post" enctype="multipart/form-data"> @csrf
            <div class="modal-header">
                <h5 class="modal-title h4" id="myLargeModalLabel">Cover photo <b>{{ $page_post_type->title }}</b> page</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                @if($page_post_type->photo !=null)
                <img src="{{ url('storage/'.$page_post_type->photo) }}" style="max-width:100%;margin-bottom:2em">
                @endif
                <input type="hidden" name="oldPhoto" value="{{ $page_post_type->photo }}">
                <div class="form-group">
                    <label for="img">Upload cover photo [1900x400px]</label>
                    <input type="file" class="form-control" name="photo" id="img" placeholder="Upload cover photo" accept="image/png, image/gif, image/jpeg">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save Cover photo</button>
            </div>
        </form>
    </div>
</div>

