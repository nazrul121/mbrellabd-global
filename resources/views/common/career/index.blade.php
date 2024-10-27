
@extends('common.layouts')

@section('content')
<div class="row">
    <div class="col-12">

        <div class="card">
            <div class="card-header"><h5>Career dataTables</h5>
                <div class="card-header-right">
                    @if(check_access('create-career'))
                    <button type="button" class="btn btn-outline-primary addModal"><i class="feather icon-plus"></i> Add New</button> @endif
                </div>
            </div>

            <div class="card-body">
                @if (Session::has('success'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert"><strong class="text-success">Success!  </strong>   
                        {{ Session::get('success') }}
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span></button></p>
                    </div>
                @endif
                <div class="table-responsive">
                    <table class="table table-hover bg-white careerTable" style="width:100%">
                        <thead>
                            <tr><th>ID</th><th>Country for</th> <th>Title</th><th>Applicants</th><th>Last Date</th> <th>Description</th> <th>Status</th> <th>Actions</th> </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('common.career.modal')
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

        let table = $('.careerTable').DataTable({
            processing: true,serverSide: true,
            "language": { processing: '<img src="'+url+'/storage/images/ajax-loader.gif">'},
            ajax: "{{route('common.career')}}",
            order: [ [0, 'desc'] ],
            columns: [
                {data: 'id'},
                {data: 'country', orderable: false, searchable: false},
                {data: 'title'},
                {data: 'applicants', orderable: false, searchable: false},
                {data: 'last_date'},
                {data: 'description', orderable: false, searchable: false},
                {data: 'status', orderable: false, searchable: false},
                {data: 'modify', orderable: false, searchable: false, class:'text-right'}
            ]
        });
        
        $('.careerTable').on('click', '.applicants', function(){
            var id = $(this).attr('id');
            $('#applicantModal').modal('show');
            // window.open(url+'/common/career/applicants/'+id);
            $.get(url+'/common/career/applicants/'+id, function(data){
                $('.applicant_info').html(data);
            })
        });

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
                        $('.careerTable').DataTable().ajax.reload();
                        setTimeout(function() { $('#addModal').modal('hide');}, 1000);
                    }
                    $("[type='submit']").text('Save Data');
                    $("[type='submit']").prop('disabled',false);
                    $('.add_result').html(html);
                }
            });
        });


        $('.careerTable').on('click', '.delete' ,function(e){
            if(confirm('Are you sure to remove the record permanently?? --- There is no Undo option')){
                let id = $(this).attr('id')
                $.ajax({
                    url: url+"/common/career/delete/"+id+"",
                    dataType:"json",
                    success:function(data){
                        if(data.error) alert(data.error);
                        if(data.success) $('.careerTable').DataTable().ajax.reload();
                    }
                });
            }
        });

    });

</script>

@endpush
