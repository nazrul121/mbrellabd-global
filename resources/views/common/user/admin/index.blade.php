
@extends('common.layouts')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5>Customers dataTable</h5>
                <div class="card-header-right">
                    <button type="button" class="btn btn-outline-primary addModal"><i class="feather icon-plus"></i> Add New</button>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover bg-white" style="width:100%">
                        <thead>
                            <tr><th>ID</th> <th>photo</th>
                                <th style="width:10%">First Name</th> <th>Last Name</th>
                                <th>Role</th><th>Address</th> <th>Status</th> <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('common.user.admin.modal')
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
            "language": { processing: '<img src="'+url+'/storage/images/ajax-loader.gif">'},
            ajax: "{{route('common.admin')}}",
            order: [ [0, 'desc'] ],
            columns: [
                {data: 'id'},
                {data: 'photo', orderable: false, searchable: false},
                {data: 'first_name'},{data: 'last_name'}, {data: 'position'},

                {data: 'address'},
                {data: 'status', orderable: false, searchable: false},
                {data: 'modify', orderable: false, searchable: false, class:'text-right'}
            ]
        });


        $('.table').on('click','.edit',function(){
            $('#editModal').modal('show'); $('.edit_result').html('');
            let id = $(this).attr('id');

            $.ajax({
                url: url+"/common/user/admin/single-item/"+id,
                type: 'get', dataType: 'json',
                success: function (data) {
                    $('.userArea').slideUp('first');
                    $('[name=first_name]').val(data.first_name);
                    $('[name=last_name]').val(data.last_name);
                    $('[name=address]').val(data.address);$('#id').val(data.id);
                    $('[name=position]').val(data.position);
                    $('#editForm').attr('action', url+'/common/user/admin/update/'+id);

                    if(data.sex == 'male'){
                        $('input.sex[value="male"]').prop('checked', true);
                    } if(data.sex == 'female'){
                        $('input.sex[value="female"]').prop('checked', true);
                    } if(data.sex == 'other'){
                        $('input.sex[value="other"]').prop('checked', true);
                    }

                    if(data.is_super == 1){
                        $('[name=is_super]').prop('checked', true);
                        $('[name="is_super"]').val(data.is_super);
                    }else{
                        $('[name="is_super"]').val(data.is_super);
                        $('[name=is_super]').prop('checked', false);
                    }

                    if(data.has_permission == 1){
                        $('input.has_permission[value="1"]').prop('checked', true);
                    }else{
                        $('input.has_permission[value="0"]').prop('checked', true);
                    }

                    $('#editModal .oldPhoto').val(data.photo)

                    $('#editModal .setPhoto').attr('src', url+'/storage/'+data.photo)
                }
            });
        })

        $('.addModal').on('click', function(){
            $('#addModal').modal('show'); $('#addForm').trigger("reset");
            $('.add_result').html(''); $('#output').attr('src', url+'/storage/images/user.jpg');
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
                    url: url+"/common/user/admin/delete/"+id+"",
                    dataType:"json",
                    success:function(data){
                        if(data.error) alert(data.error);
                        if(data.success) $('.table').DataTable().ajax.reload();
                    }
                });
            }
        });

        $('.table').on('click', '.login' ,function(e){
            $('#supRepresent').modal('show');
            let id = $(this).attr('id');
            $.get( url+'/common/user/admin/login-info/'+id,  function (data, textStatus, jqXHR) {  // success callback
                $('.showLogin').html(data);
            });
        });

    });

</script>

<script>
    // show uploaded photo
    var loadFile = function(event) {
      var reader = new FileReader();
      reader.onload = function(){
        var output =( document.getElementById)('output');
        output.src = reader.result;
      };
      reader.readAsDataURL(event.target.files[0]);
    };
</script>
@endpush
