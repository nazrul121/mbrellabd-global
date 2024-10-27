
@extends('common.layouts')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5>Child-category dataTable</h5>
                <div class="card-header-right">
                    <select name="category" style="padding: 10px;" class="border border-info rounded">
                        <option value="">Choose group</option>
                        @foreach ($categories as $group)
                            <option value="{{ $group->id }}" @if(request()->get('group')==$group->id)selected @endif >{{ $group->title }}</option>
                        @endforeach
                    </select>
                    <select name="sub_category" style="padding: 10px;" class="border border-info rounded"> </select>

                    @if(check_access('create-sub-category')==true)
                    <button type="button" class="btn btn-outline-primary addModal"><i class="feather icon-plus"></i> Add New</button>@endif
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <table class="table childTable table-hover bg-white" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th> <th>Country for</th><th>Image</th> <th>Name</th>
                                <th>Belongs To</th>
                                <th>Product Number</th> <th>Status</th> <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('common.category.child.modal')
</div>
@endsection


@php
    $innerGroup = request()->get('innerGroup');
    $url = '/common/catalog/category/child';

    if(request()->get('innerGroup')){
        $url = url('/common/catalog/category/child?innerGroup='.request()->get('innerGroup'));
    }
@endphp


@push('scripts')
<link rel="stylesheet" href="{{ asset('back2') }}/plugins/data-tables/css/datatables.min.css">
<script src="{{ asset('back2') }}/plugins/data-tables/js/datatables.min.js"></script>
<script src="{{ asset('back2') }}/js/pages/tbl-datatable-custom.js"></script>

<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {

        $(function () { table.ajax.reload(); });

        let table = $('.childTable').DataTable({
            processing: true,serverSide: true,
            "language": { processing: '<img src="'+url+'/storage/images/ajax-loader.gif">'},
            ajax: "{{ $url }}",
            order: [ [0, 'desc'] ],
            columns: [
                {data: 'id'},
                {data: 'country', orderable: false, searchable: false},
                {data: 'photo', orderable: false, searchable: false},
                {data: 'title'},
                {data: 'sub_category', orderable: false, searchable: false},
                {data: 'products', orderable: false, searchable: false},
                {data: 'status', orderable: false, searchable: false},
                {data: 'modify', orderable: false, searchable: false, class:'text-right'}
            ]
        });

        $('.childTable').on('click','.edit',function(){
            $('#editModal').modal('show'); $('.edit_result').html('');
            let id = $(this).attr('id');  $("[name=sub_category]").html('');
            $('input.lang').prop('checked', false);
            $.ajax({
                url: url+"/common/catalog/category/child/single-item/"+id,
                type: 'get', dataType: 'json',
                success: function (data) {
                    $('[name=title]').val(data.title);$('#id').val(data.id);
                    $('[name=description]').val(data.description);
                    $('[name=display_name]').val(data.display_name);

                    $("[name=sub_category]").append('<option value="'+data.inner_group_id+'">'+data.inner_group.title+'</option>');

                    //$("[name=category]").append('<option value="'+data.inner_group.group_id+' selected">'+data.inner_group.group_id+'</option>');
                    // $('[name=category] option:eq('++')').prop('selected', true);
                    $('#cat option[value="'+data.inner_group.group_id+'"]').prop('selected', true);

                    $('#editForm').attr('action',url+'/common/catalog/category/child/update/'+id);

                    if(data.status == 1){
                        $('input.status[value="1"]').prop('checked', true);
                    }else $('input.status[value="0"]').prop('checked', true);

                    var imageUrl = "/storage/"+data.photo;
                    $('#editModal .oldPhoto').val(data.photo)

                    setTimeout(() => {
                        $.each(data.country, function(key, value) {
                            $('input.lang[value="'+value.country_id+'"]').prop('checked', true);
                        });
                    }, 200);

                    $('#editModal .setPhoto').attr('src',url+'/storage/'+data.photo)
                }
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
                        $('.childTable').DataTable().ajax.reload();
                        // setTimeout(function() { $('#addModal').modal('hide');}, 1000);
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
                        $('.childTable').DataTable().ajax.reload();
                        setTimeout(function() { $('#editModal').modal('hide');}, 1000);
                    }
                    $("[type='submit']").text('Update Data');
                    $("[type='submit']").prop('disabled',false);
                    $('.edit_result').html(html);
                }
            });
        });

        $('.childTable').on('click', '.delete' ,function(e){
            if(confirm('Are you sure to remove the record permanently?? --- There is no Undo option')){
                let id = $(this).attr('id')
                $.ajax({
                    url:url+"/common/catalog/category/child/delete/"+id+"",
                    dataType:"json",
                    success:function(data){
                        if(data.error) alert(data.error);
                        if(data.success) $('.childTable').DataTable().ajax.reload();
                    }
                });
            }
        });

        //get sub-categories
        $('[name=category]').on('change',function(){
            $("[name=sub_category]").html('')
            $("[name=sub_category]").append('<option value="">Sub-category</option>')
            let id =  $(this).val();
            $.ajax({ url:url+"/common/group2sub-categories/"+ id, method:"get",
                success:function(data){
                    $.each(data, function(index, value){
                        $("[name=sub_category]").append('<option value="'+value.id+'">'+value.title+'</option>');
                    });
                }
            });
        });

        $('.childTable').on('click', '.meta' ,function(e){
            let id = $(this).attr('id');
            $('#metaModal').modal('show');
            $.get( url+"/common/catalog/child/meta/"+id, function(data, status){
            $('.metaResult').html(data);
            });
        });

        $('[name=sub_category]').on('change', function(){
            var innerGroup = $(this).val();
            var group = $('[name=category]').val();
            var url = new URL(window.location.href);
            url.searchParams.set('group',group);
            url.searchParams.set('innerGroup',innerGroup);
            window.location.href = url.href;
        })

    


    });

</script>

<?php 
if(request()->get('group')){  ?>
    
    <script>
        $("[name=sub_category]").append('<option value="">Sub-category</option>')
        $.ajax({ url:"{{ url('/common/group2sub-categories/'.request()->get('group')) }}", method:"get",
            success:function(data){
                $.each(data, function(index, value){
                    $("[name=sub_category]").append('<option value="'+value.id+'">'+value.title+'</option>');
                });
            }
        });
    </script>
    <?php
}
?>

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
