
@extends('common.layouts')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5>Seasons dataTable</h5>
                <div class="card-header-right">
                    <button type="button" class="btn btn-outline-primary addModal"><i class="feather icon-plus"></i> Add New</button>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover bg-white" style="width:100%">
                        <thead>
                            <tr><th>ID</th> <th>Country for</th> <th>Image</th> <th>Season title</th><th>Description</th><th>Status</th> <th>Actions</th></tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('common.season.modal')
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
            ajax: "{{route('common.season')}}",
            order: [ [0, 'desc'] ],
            columns: [
                {data: 'id'},
                {data: 'country', orderable: false, searchable: false},
                {data: 'photo', orderable: false, searchable: false},
                {data: 'title'}, {data: 'description'},
                {data: 'status', orderable: false, searchable: false},
                {data: 'modify', orderable: false, searchable: false, class:'text-right'}
            ]
        });

        $('.table').on('click','.edit',function(){
            $('#editModal').modal('show'); $('.edit_result').html('');
            let id = $(this).attr('id');
            $('.groupArea').slideUp();  $('.groupLabel').slideUp();
            $.ajax({
                url: url+"/common/catalog/season/single-item/"+id,
                type: 'get', dataType: 'json',
                success: function (data) {
                    $('[name=title]').val(data.title);$('#id').val(data.id);
                    $('[name=description]').val(data.description);

                    $('[name=slug]').val(data.slug);
                    $('[name=meta_title]').val(data.meta_title);
                    $('[name=meta_description]').val(data.meta_description);

                    
                    $('#editForm').attr('action', url+'/common/catalog/season/update/'+id);
                    if(data.status == 1){
                        $('input.status[value="1"]').prop('checked', true);
                    }else $('input.status[value="0"]').prop('checked', true);

                    setTimeout(() => {
                        $.each(data.country, function(key, value) {
                            $('input.lang[value="'+value.country_id+'"]').prop('checked', true);
                        });
                    }, 200);

                    $('#editModal .oldPhoto').val(data.photo)
                    $('#editModal .setPhoto').attr('src', url+'/storage/'+data.photo)
                }
            });
        })

        $('.addModal').on('click', function(){
            $('#addModal').modal('show'); $('#addForm').trigger("reset");
            $('.add_result').html(''); $('#output').attr('src', url+'/storage/images/thumbs_photo.png');
            $('.groupArea').slideDown(); $('.groupLabel').slideDown();
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
                        html = '<div class="alert alert-warning alert-dismissible fade show" role="alert"><strong class="text-danger">Warning! </strong>';
                        for(var count = 0; count < data.errors.length; count++)
                        { html += data.errors[count] + '</div>';break;}
                      
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
                        html = '<div class="alert alert-warning alert-dismissible fade show" role="alert"><strong class="text-danger">Warning! </strong>';
                        for(var count = 0; count < data.errors.length; count++)
                        { html +=  data.errors[count] + '</div>';break;}
    
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
                    url: url+"/common/catalog/season/delete/"+id+"",
                    dataType:"json",
                    success:function(data){
                        if(data.error) alert(data.error);
                        if(data.success) $('.table').DataTable().ajax.reload();
                    }
                });
            }
        });

        $('.table').on('click', '.addItem' ,function(e){
            let id = $(this).attr('id')
            $('#itemModal').modal('show');
            $.get(url+"/common/catalog/season/search/"+id, function(data, status){
                $('.items').html(data)
            });
        });

        $('.table').on('click', '.groups' ,function(e){
            let id = $(this).attr('id')
            $('#categoryModal').modal('show');
            $.get( url+"/common/catalog/season/groups/"+id, function(data, status){
                $('.categories').html(data)
            });

        });

        $('[name=title]').on('keyup', function() {
            var title = $(this).val().toLowerCase().trim();
            // Remove invalid characters
            title = title.replace(/[^a-z0-9\s-]/g, '');
            // Replace spaces with dashes
            title = title.replace(/\s+/g, '-');
            // Replace multiple dashes with a single dash
            title = title.replace(/-{2,}/g, '-');
            $('[name=slug]').val(title);
        });

    });

</script>

@endpush
