
@extends('common.layouts')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5>Employee dataTable</h5>
                <div class="card-header-right">
                    @if(check_access('create-staff'))
                    <button type="button" class="btn btn-outline-primary addModal"><i class="feather icon-plus"></i> Add New</button>@endif
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover bg-white" style="width:100%">
                        <thead>
                            <tr><th>ID</th> <th>Image</th> <th style="width:10%">Employee Name</th><th> </th>
                                <th>Position</th>  <th>Salary</th>
                                <th>Address</th> <th>Status</th> <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('common.user.employee.modal')
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

        $(function () { table.ajax.reload(); });

        let table = $('.table').DataTable({
            processing: true,serverSide: true,
            "language": { processing: '<img src="/storage/images/ajax-loader.gif">'},
            ajax: "{{route('common.employee')}}",
            order: [ [0, 'desc'] ],
            columns: [
                {data: 'id'},
                {data: 'photo', orderable: false, searchable: false},
                {data: 'first_name'},{data: 'last_name'},
                {data: 'position'},{data: 'salary'},{data: 'address'},
                {data: 'status', orderable: false, searchable: false},
                {data: 'modify', orderable: false, searchable: false, class:'text-right'}
            ]
        });


        $('.table').on('click','.edit',function(){
            $('#editModal').modal('show'); $('.edit_result').html('');
            let id = $(this).attr('id');
            // window.open( url+"/common/user/employee/single-item/"+id );
            $.ajax({
                url: url+"/common/user/employee/single-item/"+id,
                type: 'get', dataType: 'json',
                success: function (data) {
                    $('.userArea').slideUp('first');
                    $('[name=first_name]').val(data.first_name);
                    $('[name=last_name]').val(data.last_name); $('#id').val(data.id);
                    $('[name=address]').val(data.address);
                    $('[name=position]').val(data.position);
                    $('[name=salary]').val(data.salary);
                    $('#editForm').attr('action','/common/user/employee/update/'+id);

                    if(data.sex == 'male'){
                        $('input.sex[value="male"]').prop('checked', true);
                    } if(data.sex == 'female'){
                        $('input.sex[value="female"]').prop('checked', true);
                    } if(data.sex == 'other'){
                        $('input.sex[value="other"]').prop('checked', true);
                    }

                    if(data.status == '1'){
                        $('input.status[value="1"]').prop('checked', true);
                    }else $('input.status[value="0"]').prop('checked', true);

                    $("[name=employee_category] option").each(function(){
                        if ($(this).val() == data.staff_type_id)  $(this).attr("selected",true);
                    });




                    $('#editModal .oldPhoto').val(data.photo)
                    $('#editModal .setPhoto').attr('src','/storage/'+data.photo)
                }
            });
        })

        $('.addModal').on('click', function(){
            $('#addModal').modal('show'); $('#addForm').trigger("reset");
            $('.add_result').html(''); $('#output').attr('src','/storage/images/user.jpg');
            $('.userArea').slideDown('first');
        })

        $("#addForm").submit(function(event) {
            event.preventDefault();
            document.getElementById("addForm").scrollIntoView( {behavior: "smooth" })

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
            document.getElementById("editForm").scrollIntoView( {behavior: "smooth" })

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
                        html = '<div class="alert alert-warning alert-dismissible fade show" role="alert"><strong class="text-danger">Warning! </strong>';
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
                    url:"/common/user/employee/delete/"+id+"",
                    dataType:"json",
                    success:function(data){
                        if(data.error) alert(data.error);
                        if(data.success) $('.table').DataTable().ajax.reload();
                    }
                });
            }
        });

        $('.table').on('click', '.loginInfo' ,function(e){
            $('#loginModal').modal('show');
            let id = $(this).attr('id');
            $.get('/common/user/employee/login-info/'+id,  function (data, textStatus, jqXHR) {  // success callback
                $('.showLogin').html(data);
            });
        });

        $('.table').on('click','.access', function(){
            $('#permissionModal').modal('show');
            let id = $(this).attr('id');
            $.get( url+"/common/user/user-permissions/"+id, function( data ) {
                $('.showPermission').html(data);
            });
        })


    });

</script>

@endpush
