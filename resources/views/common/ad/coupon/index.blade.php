@extends('common.layouts')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5>Coupon dataTables</h5>
                <div class="card-header-right">
                    <select class="text-info p-2 type">
                        <option>Coupone type wise view</option>
                        @foreach ($coupon_types as $type)
                            <option @if(request()->type==$type->id)selected @endif value="{{ $type->id }}">{{ $type->title }}</option>
                        @endforeach
                    </select> &nbsp;
                    <div class="btn-group card-option step2">
                        @if(check_access('create-coupons'))
                        <a class="addModal btn btn-info" href="#!"><i class="feather icon-plus"></i> Add New</a> @endif

                    </div>

                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover bg-white" style="width:100%">
                        <thead>
                            <tr><th>ID</th> <th>Coupon title</th><th>Code</th> <th>Assigned to</th><th>Cost</th>
                                <th>Expiry date</th> <th>Status</th> <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('common.ad.coupon.modal')
</div>
@endsection


@push('scripts')
<link rel="stylesheet" href="{{ asset('back2') }}/plugins/data-tables/css/datatables.min.css">
<script src="{{ asset('back2') }}/plugins/data-tables/js/datatables.min.js"></script>
<script src="{{ asset('back2') }}/js/pages/tbl-datatable-custom.js"></script>

<link rel="stylesheet" href="{{ asset('back2') }}/plugins/bootstrap-datetimepicker/css/bootstrap-datepicker3.min.css">
<script src="{{ asset('back2') }}/plugins/bootstrap-datetimepicker/js/bootstrap-datepicker.min.js"></script>
<script src="{{ asset('back2') }}/js/pages/ac-datepicker.js"></script>

<style>
    .datepicker>.datepicker-days { display: block;}
    ol.linenums {  margin: 0 0 0 -8px;}
</style>

<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {
        $('.type').on('change', function(){
            window.location = "{{ route('common.coupon') }}?type="+$(this).val();
        });

        $(function () { table.ajax.reload(); });

        let table = $('.table').DataTable({
            processing: true,serverSide: true,
            "language": { processing: '<img src="/storage/images/ajax-loader.gif">'},
            ajax: "{{route('common.coupon')}}",
            order: [ [0, 'desc'] ],
            columns: [
                {data: 'coupon_type_id', orderable: false, searchable: false},
                {data: 'title'}, {data: 'coupon_code'}, {data: 'assignedTo', orderable: false, searchable: false},
                {data: 'cost'},{data: 'expiry_date'},
                {data: 'status', orderable: false, searchable: false},
                {data: 'modify', orderable: false, searchable: false, class:'text-right'}
            ]
        });

        $('.table').on('click','.edit',function(){
            $('#editModal').modal('show'); $('.edit_result').html('');
            let id = $(this).attr('id');
            $.ajax({
                url: url+"/common/ad/coupon/single-item/"+id,
                type: 'get', dataType: 'json',
                success: function (data) {
                    $('[name=title]').val(data.title);$('#id').val(data.id);
                    $('[name=coupon_code]').val(data.coupon_code);
                    $('[name=expiry_date]').val(data.expiry_date);
                    $('[name=cost]').val(data.cost);
                    $('[name=description]').val(data.description);
                    $('#editForm').attr('action','/common/ad/coupon/update/'+id);
                    if(data.is_validate == 1){
                        $('input.status[value="1"]').prop('checked', true);
                    }else $('input.status[value="0"]').prop('checked', true);

                    $("[name=type] option").each(function(){
                        if ($(this).val() == data.coupon_type_id)  $(this).attr("selected",true);
                    });
                }
            });
        })

        $('.addModal').on('click', function(){
            $('#addModal').modal('show'); $('#addForm').trigger("reset");
            $('.add_result').html('');
        })

        $("#addForm").submit(function(event) {
            event.preventDefault();
            $("[type='submit']").html(' Loading...');$('.add_result').html('');
            $("[type='submit']").prop('disabled',true);
            var form = $(this);var url = form.attr('action');
            var html = '';
            $.ajax({
                url:url, method:"post", data: new FormData(this),
                contentType: false,cache:false, processData: false,
                dataType:"json",
                success:function(data){
                    if(data.errors) {
                        html = '<div class="alert alert-warning alert-dismissible fade show" role="alert"><strong class="text-danger">Warning! <br/> </strong>';
                        for(var count = 0; count < data.errors.length; count++)
                        { html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span></button>' + data.errors[count] + '</p>';break;}
                        html += '</div>';
                    }
                    if(data.success){
                        html = '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong class="text-info">Success! </strong> ' + data.success +'</div>';
                        $('.table').DataTable().ajax.reload();
                        setTimeout(function() { $('#addModal').modal('hide');}, 1000);
                    }
                    $("[type='submit']").text('Save Data');
                    $("[type='submit']").prop('disabled',false);
                    $('.add_result').html(html);
                }
            });
        });

        $("#editForm").submit(function(event) {
            event.preventDefault();
            $("[type='submit']").html('Loading...');
            $("[type='submit']").prop('disabled',true);
            var form = $(this);var url = form.attr('action');
            var html = '';
            $.ajax({
                url:url, method:"post", data: new FormData(this),
                contentType: false,cache:false, processData: false,
                dataType:"json",
                success:function(data){
                    if(data.errors) {
                        html = '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong class="text-danger">Warning! </strong>';
                        for(var count = 0; count < data.errors.length; count++)
                        { html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span></button>' + data.errors[count] + '</p>';break;}
                        html += '</div>';
                    }
                    if(data.success){
                        html = '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong class="text-info">Success! </strong> ' + data.success + '</div>';
                        $('.table').DataTable().ajax.reload();
                        setTimeout(function() { $('#editModal').modal('hide');}, 1000);
                    }
                    $("[type='submit']").text('Update Data');
                    $("[type='submit']").prop('disabled',false);
                    $('.edit_result').html(html);
                }
            });
        });

        $('.table').on('click', '.delete' ,function(e){
            if(confirm('Are you sure to remove the record permanently?? --- There is no Undo option')){
                let id = $(this).attr('id')
                $.ajax({
                    url:"/common/ad/coupon/delete/"+id+"",
                    dataType:"json",
                    success:function(data){
                        if(data.error) alert(data.error);
                        if(data.success) $('.table').DataTable().ajax.reload();
                    }
                });
            }
        });

        $('.table').on('click', '.assign' ,function(e){
            $('#customerModal').modal('show');
            let id = $(this).attr('id');
            $.get(url+'/common/ad/coupon/customer/'+id,  function (data, textStatus, jqXHR) {  // success callback
                $('.showCustomers').html(data);
            });
        });

    });

</script>
@endpush
