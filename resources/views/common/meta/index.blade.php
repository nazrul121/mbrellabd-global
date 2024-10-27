
@extends('common.layouts')

@section('content')
    <div class="card">
        <div class="card-header bg-white">
            <h4 class="card-title pt-2">Meta data for <b>pages</b>
                <div class="float-right pt-0">
                    <button type="button" class="btn btn-outline-primary addModal"><i class="feather icon-plus"></i> Add New</button> 
                </div>
            </h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="table-responsive">
                        <table class="table" id="meta-table">
                            <thead>
                                <tr>
                                   <th>PageFor</th> <th>Meta type</th><th>Description</th> <th>Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div> 
        </div>
    </div>

    @include('common.meta.modal')

@endsection


@push('scripts')
    <link rel="stylesheet" href="{{ asset('back2') }}/plugins/data-tables/css/datatables.min.css">
    <script src="{{ asset('back2') }}/plugins/data-tables/js/datatables.min.js"></script>
    <script src="{{ asset('back2') }}/js/pages/tbl-datatable-custom.js"></script>

    <script>
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

        $(document).ready(function () {

            $(function () { table.ajax.reload(); });

            let table = $('#meta-table').DataTable({
                processing: true,serverSide: true,
                "language": { processing: '<img src="/storage/images/ajax-loader.gif">'},
                ajax: "{{route('common.meta')}}",
                order: [ [0, 'desc'] ],
                columns: [
                    {data: 'pageFor'},{data: 'type'},{data: 'description'},
                    {data: 'modify', orderable: false, searchable: false, class:'text-right'}
                ]
            });

            $('.addModal').on('click', function(){
                $('#addModal').modal('show'); $('#addForm').trigger("reset");
                $('#addForm').attr('action','/common/save-meta');
                $('#addModalLabel').text('Create meta');
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
                            { html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span></button>' + data.errors[count];break;}
                            html += '</div>';
                        }
                        if(data.success){
                            html = '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong class="text-info">Success! </strong> ' + data.success +'</div>';
                            $('#meta-table').DataTable().ajax.reload();
                            setTimeout(function() { $('#addModal').modal('hide');}, 1000);
                        }
                        $("[type='submit']").text('Save Data');
                        $("[type='submit']").prop('disabled',false);
                        $('.add_result').html(html);
                    }
                });
            });

            $('#meta-table').on('click','.edit',function(){
                $('#addModal').modal('show');
                $('#addModalLabel').text('Update meta')

                $("[type='submit']").html('Loading...');$('.add_result').html('');
                $("[type='submit']").prop('disabled',true);
                var form = $(this);
                var url = form.attr('action');
                var html = '';

                let id = $(this).attr('id');
                $.ajax({
                    url:"/common/single-meta/"+id, type: 'get', dataType: 'json',
                    success: function (data) {
                        $('[name=description]').val(data.description);
                        $('[name=pageFor]').val(data.pageFor);
                        $('#id').val(data.id);

                        setTimeout(function() {
                            $("[name=keywords] option").each(function(){
                                if ($(this).val() == data.type)  $(this).attr("selected",true);
                            });
                        }, 500);
                   
                        $('#addForm').attr('action','/common/update-meta/'+id);
                        $("[type='submit']").text('Update Data');
                        $("[type='submit']").prop('disabled',false);

                        $('.add_result').html(html);
                    }
                });
            })


            $('#meta-table').on('click', '.delete' ,function(e){
                if(confirm('Are you sure to remove the record permanently?? --- There is no Undo option')){
                    let id = $(this).attr('id')
                    $.ajax({
                        url: "/common/delete-meta/"+id,
                        dataType:"json",
                        success:function(data){
                            if(data.error) alert(data.error);
                            if(data.success) $('#meta-table').DataTable().ajax.reload();
                        }
                    });
                }
            });

        });

    </script>
@endpush
