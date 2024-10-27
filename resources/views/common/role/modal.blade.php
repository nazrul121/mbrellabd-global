<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">

        <?php $permissionType = \DB::table('settings')->where('type','staff-permission-type')->pluck('value')->first();?>

        <form id="" class="modal-content" method="post" action="{{ route('common.set-access-type') }}"> @csrf
            <div class="modal-header">
                <h5 class="modal-title" id="loginModalLabel">Permission types</h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <div class="custom-control custom-radio">
                        <input type="radio" id="customRadio1" name="accessType" value="role-base" class="custom-control-input" @if($permissionType=='role-base')checked @endif>
                        <label class="custom-control-label" for="customRadio1">User type/role Base</label>
                    </div>
                    <div class="custom-control custom-radio">
                        <input type="radio" id="customRadio2" name="accessType" value="staff-individual" class="custom-control-input" @if($permissionType=='staff-individual')checked @endif>
                        <label class="custom-control-label" for="customRadio2">Or Staff individual access</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Update Settings</button>
            </div>
        </form>
    </div>
</div>


<div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-labelledby="viewModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"> <b id="viewModalLabel"></b> permissions</h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body showAllPermission"> </div>
        </div>
    </div>
</div>
