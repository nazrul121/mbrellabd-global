
@extends('common.layouts')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5>Courier Companies</h5>
                <div class="card-header-right">
                    @if(check_access('create-courier-company'))
                    <button type="button" class="btn btn-outline-primary addModal"><i class="feather icon-plus"></i> Add New</button> @endif

                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover bg-white" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th><th>Logo</th><th>Company Name</th>
                                <th>Bundle<sub>s</sub> </th> <th>Location</th><th>Status</th> <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('common.courier.companies.modal')
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
            ajax: "{{route('common.couriers')}}",
            order: [ [0, 'desc'] ],
            columns: [
                {data: 'id'}, {data:'logo'}, {data: 'name'},
                {data: 'bundles', orderable: false, searchable: false},{data: 'location'},
                {data: 'status', orderable: false, searchable: false},
                {data: 'modify', orderable: false, searchable: false, class:'text-right'}
            ]
        });

        $('.addModal').on('click', function(){
            $('#addModal').modal('show'); $('#addForm').trigger("reset");
            $('.add_result').html(''); $('#output').attr('src',url+'/storage/images/thumbs_photo.png');
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
                        // setTimeout(function() { $('#addModal').modal('hide');}, 1000);
                    }
                    $("[type='submit']").text('Save Data');
                    $("[type='submit']").prop('disabled',false);
                    $('.add_result').html(html);
                }
            });
        });


        $('.table').on('click','.edit',function(){
            $('#editModal').modal('show'); $('.edit_result').html('');
            let id = $(this).attr('id');
            $.ajax({
                url: url+"/common/courier/companies/single-company/"+id,
                type: 'get', dataType: 'json',
                success: function (data) {
                    $('[name=name]').val(data.name);
                    $('[name=address]').val(data.location);
                    $('[name=amount]').val(data.amount);
                    $('#id').val(data.id);

                    $('#editForm').attr('action', url+'/common/courier/companies/update/'+id);
                    if(data.status == 1){
                        $('input.status[value="1"]').prop('checked', true);
                    }else $('input.status[value="0"]').prop('checked', true);

                    if(data.commission_in == 'percentage'){
                        $('input.commission_in[value="percentage"]').prop('checked', true);
                    }else $('input.commission_in[value="fix_amount"]').prop('checked', true);

                    $('#editModal .oldPhoto').val(data.logo)
                    $('#editModal .setPhoto').attr('src',url+'/storage/'+data.logo)
                }
            });
        })


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

    });


    $('.table').on('click','.representative',function(){
        $('#representativeModal').modal('show'); $('.representative_result').html('');
        $('#representativeModalLabel').text('Company Representatives');
        let id = $(this).attr('id');
        $.get( "/common/courier/companies/company-representatives/"+id, function( data ) {
            // $( ".representative_result" ).html( data );
            if(data ==''){
                $( ".representative_result").append( '<p class="alert alert-danger">No data found</p>' );
            }else{
                $.each( data , function( i, v ){
                    $( ".representative_result").append( '<p class="alert alert-secondary">'+v.name+' - Phone: '+v.phone+'</p>' );
                });
            }
        });
    })

    $('.table').on('click','.zone',function(){
        $('#representativeModal').modal('show'); $('.representative_result').html('');
        $('#representativeModalLabel').text('Company zones');
        let id = $(this).attr('id');  $( ".representative_result" ).html( '' );

        $.get( "/common/courier/companies/zones/"+id, function( data ) {
            if(data ==''){
                $( ".representative_result").append( '<p class="alert alert-danger">No data found</p>' );
            }else{
                $.each( data , function( i, v ){
                    $( ".representative_result").append( '<p class="alert alert-info">'+v.name+' - Charage: '+v.delivery_cost+'</p>' );
                });
            }
            // console.log(data);
        });

    })



</script>
<script>
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
