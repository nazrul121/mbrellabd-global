@extends('common.layouts')
@section('title', 'Access labels / User-types')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5>Access labels / Login user types</h5>

            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover bg-white" style="width:100%">
                        <thead>
                            <tr><th>ID</th> <th>Title</th> <th style="width:10%">User<sup>s</sup></th> <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="permissionModal" tabindex="-1" role="dialog" aria-labelledby="permissionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <form class="modal-content" method="post" action="{{ route('common.user-type-permission') }}" enctype="multipart/form-data"> @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="permissionModalLabel">Access label permissions</h5>
                    <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body" style="height:90vh;overflow-y:scroll;"><div class="showPermission"> Working...</div></div>
                <div class="modal-footer" style="height:10vh;">
                    <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary updatePermission" disabled>Update Permission</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')

<link rel="stylesheet" href="{{ asset('back2') }}/plugins/data-tables/css/datatables.min.css">
<link rel="stylesheet" href="{{ asset('back2') }}/plugins/multi-select/css/multi-select.css">
<script src="{{ asset('back2') }}/plugins/data-tables/js/datatables.min.js"></script>
<script src="{{ asset('back2') }}/js/pages/tbl-datatable-custom.js"></script>

<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {
        $('.table').on('click','.access', function(){
            $('#permissionModal').modal('show');
            let id = $(this).attr('id');
            $.get( url+"/common/user/user-type-permissions/"+id, function( data ) {
                $('.showPermission').html(data);
            });
        })


        $(function () { table.ajax.reload(); });
        let table = $('.table').DataTable({
            processing: true,serverSide: true,
            "language": { processing: '<img src="'+url+'/storage/images/ajax-loader.gif">'},
            ajax: "{{route('common.user-types')}}",
            order: [ [0, 'desc'] ],
            columns: [
                {data: 'id'},{data: 'title'},
                {data: 'users', orderable: false, searchable: false},
                {data: 'modify', orderable: false, searchable: false, class:'text-right'}
            ]
        });

    });



</script>

@endpush
