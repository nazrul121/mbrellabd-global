
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
                            <tr><th>ID</th> <th>Logo</th> <th style="width:10%">Company Name</th>
                                <th>Supplied</th>  <th>Area</th>
                                <th>Address</th> <th>Status</th> <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('common.user.supplier.modal')
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
            ajax: "{{route('common.supplier')}}",
            order: [ [0, 'desc'] ],
            columns: [
                {data: 'id'},
                {data: 'logo', orderable: false, searchable: false},
                {data: 'company_name'},
                {data: 'products',orderable: false, searchable: false},
                {data: 'area', orderable: false, searchable: false},
                {data: 'address'},
                {data: 'status', orderable: false, searchable: false},
                {data: 'modify', orderable: false, searchable: false, class:'text-right'}
            ]
        });


        $('.table').on('click','.edit',function(){
            $('#editModal').modal('show'); $('.edit_result').html('');
            let id = $(this).attr('id');

            $.ajax({
                url: url+"/common/user/supplier/single-item/"+id,
                type: 'get', dataType: 'json',
                success: function (data) {
                    $('.userArea').slideUp('first');
                    $('[name=company_name]').val(data.company_name); $('#id').val(data.id);
                    $('[name=address]').val(data.address);
                    $('#editForm').attr('action', url+'/common/user/supplier/update/'+id);

                    get_districts(data.division_id);
                    get_cities(data.district_id);

                    $("[name=division] option").each(function(){
                        if ($(this).val() == data.division_id)  $(this).attr("selected",true);
                    });

                    setTimeout(function(){
                        show_selected_district(data.district_id);
                        show_selected_city(data.city_id);
                    }, 1000)

                    $('#editModal .oldPhoto').val(data.logo)

                    $('#editModal .setPhoto').attr('src', url+'/storage/'+data.logo)
                }
            });
        })

        $('.addModal').on('click', function(){
            $('#addModal').modal('show'); $('#addForm').trigger("reset");
            $('.add_result').html(''); $('#output').attr('src', url+'/storage/images/thumbs_photo.png');
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
                    url:url+"/common/user/supplier/delete/"+id+"",
                    dataType:"json",
                    success:function(data){
                        if(data.error) alert(data.error);
                        if(data.success) $('.table').DataTable().ajax.reload();
                    }
                });
            }
        });

        $('.table').on('click', '.representative' ,function(e){
            $('#supRepresent').modal('show');
            let id = $(this).attr('id');
            // $.get('/common/user/supplier/login-info/'+id,  function (data, textStatus, jqXHR) {  // success callback
                // $('.showRepresentative').html(data);
            // });
        });

        // get districts
        $('[name=division]').on('change',function(){
            get_districts($(this).val())
        });

         // get cities
        $('[name=district]').on('change',function(){
            get_cities($(this).val())
        });

    });

</script>

<script>
    // select choosen district on edit operation
    function show_selected_district(id){
        $("[name=district] option").each(function(){
            if ($(this).val() == id)  $(this).attr("selected",true);
        });
    }

    function show_selected_city(id){
        $("[name=city] option").each(function(){
            if ($(this).val() == id)  $(this).attr("selected",true);
        });
    }

    function get_districts(divition){
        $("[name=district]").html('<option value="">District selection</option>');
        $("[name=district]").html(''); $("[name=city]").html('');
        $.ajax({ url: url+"/get-districts/"+ divition, method:"get",
            success:function(data){
                $.each(data, function(index, value){
                    $("[name=district]").append('<option value="'+value.id+'">'+value.name+'</option>');
                });
            }
        });
    }

    function get_cities(district){
        $("[name=city]").html('<option value="">City selection</option>');
        $("[name=city]").html('');
        $.ajax({ url: url+"/get-cities/"+ district, method:"get",
            success:function(data){
                $.each(data, function(index, value){
                    $("[name=city]").append('<option value="'+value.id+'">'+value.name+'</option>');
                });
            }
        });
    }

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
