@extends('common.layouts')

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5>Product dataTable <b>{{ Request::segment(4) }}</b></h5>
                <div class="card-header-right">
                    @if(check_access('create-product-video'))
                     <a href="#" class="btn btn-outline-primary addModal"><span class="feather icon-plus"></span> Add Video</a>
                    @endif
                    <a class="btn btn-outline-info" href="{{ route('common.product') }}"><span class="feather icon-list"> Products table</span></a>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover bg-white" style="width:100%">
                        <thead>
                            <tr><th>ID</th> <th>Product title</th><th>Video</th>
                                <th>Status</th> <th>Actions</th></tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('common.product.video.modal')
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
            ajax: "{{route('common.product.videos', $product->id)}}",
            order: [ [0, 'desc'] ],
            columns: [
                {data: 'id'},
                {data: 'product', orderable: false, searchable: false},
                {data: 'videos', orderable: false, searchable: false},
                {data: 'status'},
                {data: 'modify', orderable: false, searchable: false, class:'text-right'}
            ]
        });

        $('.table').on('click','.edit',function(){
            $('#editModal').modal('show'); $('.edit_result').html('');
            let id = $(this).attr('id');
            $.ajax({
                url: url+"/common/catalog/product/videos/single-video/"+id,
                type: 'get', dataType: 'json',
                success: function (data) {
                    $('[name=video_link]').val(data.video_link);$('#id').val(data.id);
                    $('#editForm').attr('action', url+'/common/catalog/product/videos/update/'+id);
                    if(data.status == 1){
                        $('input.status[value="1"]').prop('checked', true);
                    }else $('input.status[value="0"]').prop('checked', true);

                    if(data.type == 'video'){
                        $('input.type[value="video"]').prop('checked', true);
                        $('.videoField').slideDown('fast');
                        $('.linkField').slideUp('fast');
                    }else{
                        $('.videoField').slideUp('fast');
                        $('.linkField').slideDown('fast');
                        $('input.type[value="link"]').prop('checked', true);
                    }

                    $('#editModal .old_video').val(data.video_link)

                    $('#editModal .setPhoto').attr('src',url+'/storage/'+data.photo)
                }
            });
        })


        $('.table').on('click','.video',function(){
            $('#showVideo').modal('show');
            let id = $(this).data('id');
            $.get( url+"/common/catalog/product/videos/show-one/"+id, function(data, status){
                $('#showVideo .showVideoInfo').html(data);

            });

        })

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
                    url: url+"/common/catalog/product/videos/delete/"+id+"",
                    dataType:"json",
                    success:function(data){
                        if(data.error) alert(data.error);
                        if(data.success) $('.table').DataTable().ajax.reload();
                    }
                });
            }
        });

    });

</script>
@endpush
