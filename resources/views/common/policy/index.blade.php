
@extends('common.layouts')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5><b>{{ Request::segment(4) }}</b> datdaTable</h5>
                <div class="card-header-right">
                    @if(check_access('create-policy'))
                    <a href="{{ route('common.policy.create',$policy_type->slug) }}" class="btn btn-outline-primary "><i class="feather icon-plus"></i> Add New</a>@endif

                    <a href="#" class="btn btn-outline-secondary coverPhoto"><i class="feather icon-camera"></i> Cover photo</a>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover bg-white" style="width:100%">
                        <thead>
                            <tr><th>ID</th> <th>Image</th> <th>Policy title</th><th>Description</th><th>Status</th> <th>Actions</th></tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="coverModal" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="addForm" class="modal-content" action="{{ route('common.policy.cover-photo', $policy_type->id) }}" method="post" enctype="multipart/form-data"> @csrf
            <div class="modal-header">
            <h5 class="modal-title h4" id="myLargeModalLabel">Cover photo <b>{{ $policy_type->title }}</b> page</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                @if($policy_type->photo !=null)
                <img src="{{ url('storage/'.$policy_type->photo) }}" style="max-width:100%;margin-bottom:2em">
                @endif
                <input type="hidden" name="oldPhoto" value="{{ $policy_type->photo }}">
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


@endsection


@push('scripts')
<link rel="stylesheet" href="{{ asset('back2') }}/plugins/data-tables/css/datatables.min.css">
<script src="{{ asset('back2') }}/plugins/data-tables/js/datatables.min.js"></script>
<script src="{{ asset('back2') }}/js/pages/tbl-datatable-custom.js"></script>


<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {

        $(function () { table.ajax.reload(); });

        let table = $('.table').DataTable({
            processing: true,serverSide: true,
            "language": { processing: '<img src="'+url+'/storage/images/ajax-loader.gif">'},
            ajax: "{{route('common.policy',$policy_type->slug)}}",
            order: [ [0, 'desc'] ],
            columns: [
                {data: 'id'},
                {data: 'photo', orderable: false, searchable: false},
                {data: 'title'}, {data: 'description'},
                {data: 'status', orderable: false, searchable: false},
                {data: 'modify', orderable: false, searchable: false, class:'text-right'}
            ]
        });

        $('.table').on('click', '.delete' ,function(e){
            if(confirm('Are you sure to remove the record permanently?? --- There is no Undo option')){
                let id = $(this).attr('id')
                $.ajax({
                    url: url+"/common/page-post/policy/delete/"+id+"",
                    dataType:"json",
                    success:function(data){
                        if(data.error) alert(data.error);
                        if(data.success) $('.table').DataTable().ajax.reload();
                    }
                });
            }
        });

        $('.coverPhoto').on('click' ,function(e){
           $('#coverModal').modal('show');
        });

    });

</script>
@endpush
