
@extends('common.layouts')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5>Country information table</h5>
                <div class="card-header-right">
                    @if(check_access('create-currency'))
                    <button type="button" class="btn btn-outline-primary addModal"><i class="feather icon-plus"></i> Add New</button>
                    <a href="{{ route('common.dollar') }}" class="btn btn-outline-warning"><i class="feather icon-dollar"></i> Dollar conversion rates</a>
                    @endif
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover bg-white" style="width:100%">
                        <thead>
                            <tr>
                                <th>Country</th> <th>Short Name</th><th>Flag</th> <th>Currency value</th>
                                <th>Is default</th> <th>Status</th> <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('common.setting.currency.modal')
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
            ajax: "{{route('common.currency')}}",
            order: [ [0, 'desc'] ],
            columns: [
                {data: 'name'},{data: 'short_name'},
                {data: 'symbol', orderable: false, searchable: false,},
                {data: 'value', orderable: false, searchable: false,},
                {data: 'is_default', orderable: false, searchable: false},
                {data: 'status', orderable: false, searchable: false},
                {data: 'modify', orderable: false, searchable: false, class:'text-right'}
            ]
        });


        $('.table').on('click','.edit',function(){
            $('#editModal').modal('show'); $('.edit_result').html('');
            let id = $(this).attr('id');
            $("[name=countries]").attr("selected", false);
            $.ajax({
                url: url+"/common/settings/currency/single-item/"+id,
                type: 'get', dataType: 'json',
                success: function (data) {
                    // console.log(data.name+'-'+data.short_name)
                    $('[name=short_name]').val(data.short_name);
                    $('[name=name]').val(data.name);
                    
                    $('[name=currency_symbol]').val(data.currencySymbol);
                    $('[name=currency_value]').val(data.currencyValue);
                    $('[name=currency_code]').val(data.currency_code);
                    $('[name=phone_code]').val(data.phone_code);
                    $('[name=zone]').val(data.zone);
                    
                    $('.flag').attr('src',data.flag);
                    $('#id').val(data.id);

                    if(data.status == 1){
                        $('input.status[value="1"]').prop('checked', true);
                    }else $('input.status[value="0"]').prop('checked', true);

                    // alert(data.is_default)

                    $('#editForm').attr('action', url+'/common/settings/currency/update/'+id);

                    // if(data.is_default!='1'){
                    //     if(data.nature == 'multiply'){
                    //         $('input.nature[value="multiply"]').prop('checked', true);
                    //     }else  $('input.nature[value="divide"]').prop('checked', true);
                    //     $('.formula').slideDown();
                    // }
                    // else $('.formula').slideUp();

                    setTimeout(function() {
                        $("[name=countries] option[value='"+data.name+'-'+data.short_name+"']").attr("selected", true);
                    },400);
                }
            });
        })

        $('.addModal').on('click', function(){
            $('#addModal').modal('show'); $('#addForm').trigger("reset");
            $('.add_result').html(''); $('#output').attr('src', url+'/storage/images/thumbs_photo.png');
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
                        html = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong class="text-danger">Warning! <br/> </strong>';
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
                        html = '<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong class="text-danger">Warning! </strong>';
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
                    url: url+"/common/settings/currency/delete/"+id+"",
                    dataType:"json",
                    success:function(data){
                        if(data.error) alert(data.error);
                        if(data.success) $('.table').DataTable().ajax.reload();
                    }
                });
            }
        });

        $('[name=countries]').on('change', function(){
            var country = $(this).val();
            var parts = country.split('-');
            var name = parts[0];
            var shortName = parts[1];
            $('[name=name]').val(name);
            $('[name=short_name]').val(shortName);
        })

    });

</script>

@endpush
