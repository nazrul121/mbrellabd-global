@extends('common.layouts')

@section('title', $promotion_type->title)

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5><b>{{$promotion_type->title}}</b> dataTable</h5>
                <div class="card-header-right">
                    @if(check_access('create-promotion'))
                    <a href="#!" class="btn btn-primary addModal"><i class="feather icon-plus"></i> Add New</a>@endif
                </div>
            </div>
            @php
                $percentage = 40; $original = 893;
                $discount = ($percentage / 100) * $original;
                $total = $original - ($original * ($percentage/100));
                // echo 'Discount form '.$original.' of '.$percentage.'% is : '.$discount.' less<br/>';
                // echo 'New price: '.$total.'<br/>';

                // $discountAmount = 1275 ;
                // $count1 = $discountAmount / $original;
                // $count2 = $count1 * 100;
                // $count = round($count2, 2);
                // echo 'Discount of '.$discountAmount.' From '.$original.' is: '.$count.' %';
            @endphp

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover bg-white promotionTable" style="width:100%">
                        <thead>
                            <tr><th>ID</th><th>Country for</th> <th>Image</th><th>Headline</th><th>Dates</th><th>Description</th><th>Status</th> <th>Actions</th></tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('common.ad.promotion.modal')
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
<link rel="stylesheet" href="{{ asset('back2') }}/css/timepicker.min.css">
<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {
        $('[name=start_time]').timepicker();
        $('[name=end_time]').timepicker();

        $(function () { table.ajax.reload(); });

        let table = $('.promotionTable').DataTable({
            processing: true,serverSide: true,
            "language": { processing: '<img src="'+url+'/storage/images/ajax-loader.gif">'},
            ajax: "{{route('common.promotion',$promotion_type->id)}}",
            order: [ [0, 'desc'] ],
            columns: [
                {data: 'id'},
                {data: 'country', orderable: false, searchable: false},
                {data: 'photo', orderable: false, searchable: false},
                {data: 'title'}, {data: 'dates'},{data: 'description'},
                {data: 'status', orderable: false, searchable: false},
                {data: 'modify', orderable: false, searchable: false, class:'text-right'}
            ]
        });

        $('.promotionTable').on('click','.edit',function(){
            $('#editModal').modal('show'); $('.edit_result').html('');
            let id = $(this).attr('id');
            $.ajax({
                url: url+"/common/ad/promotion/single-item/"+id,
                type: 'get', dataType: 'json',
                success: function (data) {
                    $('[name=title]').val(data.title);$('#id').val(data.id);
                    $('[name=start_date]').val(data.start_date);
                    $('[name=start_time]').val(data.start_time);

                    $('[name=end_date]').val(data.end_date);
                    $('[name=end_time]').val(data.end_time);
                    $('[name=description]').val(data.description);
                    $('#editForm').attr('action', url+'/common/ad/promotion/update/'+id);
                    $('[name=bg_color]').val(data.bg_color);
                    $('[name=bg_color]').css('background',data.bg_color);

                    $('[name=text_color]').val(data.text_color);
                    $('[name=text_color]').css('background',data.text_color);

                    if(data.status == 1){
                        $('input.status[value="1"]').prop('checked', true);
                    }else $('input.status[value="0"]').prop('checked', true);

                    if(data.expiry_visibility == 'show'){
                        $('input.expire_visibility[value="show"]').prop('checked', true);
                    }else $('input.expire_visibility[value="hide"]').prop('checked', true);

                    $('#editModal .oldPhoto').val(data.photo)
                    $('#editModal .setPhoto').attr('src',url+'/storage/'+data.photo)

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
                        $('.promotionTable').DataTable().ajax.reload();
                        setTimeout(function() { $('#addModal').modal('hide');}, 1000);
                    }
                    if(data.warning){
                        html = '<div class="alert alert-warning alert-dismissible fade show" role="alert"><strong class="text-info">Warning! </strong> ' + data.warning +'</div>';
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
                        $('.promotionTable').DataTable().ajax.reload();
                        setTimeout(function() { $('#editModal').modal('hide');}, 1000);
                    }
                    $("[type='submit']").text('Update Data');
                    $("[type='submit']").prop('disabled',false);
                    $('.edit_result').html(html);
                }
            });
        });

        $('.promotionTable').on('click', '.delete' ,function(e){
            if(confirm('Are you sure to remove this promotion permanently?? --- There is no Undo option')){
                let id = $(this).attr('id')
                $.ajax({
                    url:url+"/common/ad/promotion/delete/"+id+"",
                    dataType:"json",
                    success:function(data){
                        if(data.error) alert(data.error);
                        if(data.success) $('.promotionTable').DataTable().ajax.reload();
                    }
                });
            }
        });

        $('.promotionTable').on('click', '.assign' ,function(e){
            $('#extendModal').modal('show');
            $('.showExtends').html('Loading data...');
            let id = $(this).attr('id');
            $.get(url+'/common/ad/promotion/form/'+id,  function (data, textStatus, jqXHR) {  // success callback
                $('.showExtends').html(data);
            });
        });

        $('.promotionTable').on('click', '.products' ,function(e){
            $('#productModal').modal('show');
            $('.showProducts').html('Loading data...');
            let id = $(this).attr('id');
            $.get(url+'/common/ad/promotion/products/'+id,  function (data, textStatus, jqXHR) {  // success callback
                $('.showProducts').html(data);
            });
        });


    });
</script>
@endpush
