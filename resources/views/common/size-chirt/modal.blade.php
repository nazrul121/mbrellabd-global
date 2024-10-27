<div class="modal fade"  id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="POST" class="modal-content" name="form-example-1" enctype="multipart/form-data" action="{{ route('common.size-chirt.create') }}">@csrf

            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Create size-chirt</h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="add_result"></div>
                @include('common.size-chirt.form')
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save chirt</button>
            </div>
        </form>
    </div>
</div>


  <!-- Modal -->
  <div class="modal fade" id="photoModal" tabindex="-1" role="dialog" aria-labelledby="photoLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="position: absolute;width: 26px;top: -9px;background: red;z-index: 6;right: -10px;">
            <span aria-hidden="true">&times;</span>
          </button>
        <div class="modal-body text-center">
            <img src="" class="largeImg" style="max-width:100%">
            @if (check_access('delete-size-chirt'))
            <button type="button" class="btn btn-danger mt-4 deleteChirt">Delete size-chirt</button> @endif
        </div>

      </div>
    </div>
  </div>
