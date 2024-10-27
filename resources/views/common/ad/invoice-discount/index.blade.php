@extends('common.layouts')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5>Invoice Discount dataTable</h5>
                <div class="card-header-right">
                    <a href="#" class="btn btn-outline-primary addModal"><span class="feather icon-plus"></span> Create discount</a>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover bg-white" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th><th>Country for</th><th>Image</th><th>Title</th> <th>Min. Order </th> <th>Discount</th> <th>Validity</th>
                                <th>num. of Invoice</th> <th>Status</th> <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('common.ad.invoice-discount.modal')
</div>
@endsection


@push('scripts')
<link rel="stylesheet" href="{{ asset('back2') }}/plugins/data-tables/css/datatables.min.css">
<script src="{{ asset('back2') }}/plugins/data-tables/js/datatables.min.js"></script>
<script src="{{ asset('back2') }}/js/pages/tbl-datatable-custom.js"></script>

<link rel="stylesheet" href="{{ asset('back2') }}/plugins/bootstrap-datetimepicker/css/bootstrap-datepicker3.min.css">
<script src="{{ asset('back2') }}/plugins/bootstrap-datetimepicker/js/bootstrap-datepicker.min.js"></script>
<script src="{{ asset('back2') }}/js/pages/ac-datepicker.js"></script>
<script src="{{ asset('back2') }}/js/timepicker.js"></script>
<style>
    .datepicker>.datepicker-days { display: block;}
    ol.linenums {  margin: 0 0 0 -8px;}
</style>

<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {

        $('[name=type]').on('change', function(){
            var type= $(this).val();
            if(type=='free-delivery'){
                $('.generalArea').slideUp();
                $('.productArea').slideUp();
            }else if(type=='product'){
                $('.generalArea').slideUp();
                $('.productArea').slideDown();
            }else{
                $('.generalArea').slideDown();
                $('.productArea').slideUp();
            }
        });

        $(function () { table.ajax.reload(); });

        let table = $('.table').DataTable({
            processing: true,serverSide: true,
            "language": { processing: '<img src="'+url+'/storage/images/ajax-loader.gif">'},
            ajax: "{{route('common.invoice-discount')}}",

            columns: [
                {data: 'id'},  
                {data: 'country', orderable: false, searchable: false},
                {data: 'photo'},
                {data: 'title'},{data:'min_order_amount'},
                {data: 'discounts',orderable: false, searchable: false},
                {data: 'validity',orderable: false, searchable: false},
                {data: 'invoice_number',orderable: false, searchable: false},
                {data: 'status', orderable: false, searchable: false},
                {data: 'modify', orderable: false, searchable: false, class:'text-right'}
            ]
        });

        $('.table').on('click','.edit',function(){
            $('#editModal').modal('show'); $('.edit_result').html('');
            let id = $(this).attr('id');
            $.ajax({
                url: url+"/common/ad/invoice-discount/single-item/"+id,
                type: 'get', dataType: 'json',
                success: function (data) {
                    $('[name=title]').val(data.title);
                    $('[name=min_order_amount]').val(data.min_order_amount);
                    $('[name=discount_value]').val(data.discount_value);
                    $('[name=start_date]').val(data.start_date);
                    $('[name=end_date]').val(data.end_date);  $('#id').val(data.id);
                    $('#editForm').attr('action',url+'/common/ad/invoice-discount/update/'+id);

                    if(data.type=='free-delivery'){
                        $('.generalArea').slideUp();
                        $('.productArea').slideUp();
                    }else if(data.type=='product'){
                        $('.generalArea').slideUp();
                        $('.productArea').slideDown();
                    }else{
                        $('.generalArea').slideDown();
                        $('.productArea').slideUp();
                    }

                    $('[name=type]').val(data.type);
                    if(data.status == 1){
                        $('input.status[value="1"]').prop('checked', true);
                    }else $('input.status[value="0"]').prop('checked', true);

                    if(data.discount_in=='percent'){
                        $('input.discount_in[value="percent"]').attr('checked', true);
                    }else{
                        $('input.discount_in[value="amount"]').attr('checked', true);
                    }

                    setTimeout(() => {
                        $.each(data.country, function(key, value) {
                            $('input.lang[value="'+value.country_id+'"]').prop('checked', true);
                        });
                    }, 200);
                }
            });
        })

        $('.addModal').on('click', function(){
            $('#addModal').modal('show'); $('#addForm').trigger("reset");
            $('.add_result').html(''); $('#output').attr('src', url+'/storage/images/thumbs_photo.png');
        })

        $("#addFormT").submit(function(event) {
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
                    url: url+"/common/catalog/product/highlight/delete/"+id+"",
                    dataType:"json",
                    success:function(data){
                        if(data.error) alert(data.error);
                        if(data.success) $('.table').DataTable().ajax.reload();
                    }
                });
            }
        });

        $('[name=discount_in]').on('change',function(){
            let type = $(this).val();
            if(type=='percent'){
                $('[name=discount_value]').attr("placeholder", "Discount percentage");
                $('.percentSymbol').text('%');
            }
            else {
                $('[name=discount_value]').attr("placeholder", "Discount Amount");
                $('.percentSymbol').text('Tk');
            }
            $('[name=discount_value]').val('');
        });

    });

</script>
@endpush
