
@extends('common.layouts')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5>My profile [{{ Auth::user()->user_type->title }}]</h5> </div>

            @if (Session::has('message'))
            <div class="col-md-12">
                <div class="alert @if(Session::has('success')) alert-success @else alert-danger @endif alert-dismissible fade show" role="alert">
                <h5> <strong> @if(Session::has('success'))Success :  {{ Session::get('success') }}
                    @else Warning: {{ Session::get('alert') }}  @endif
                </strong> </h5>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
            </div> @endif

            <form class="card-body" method="post" enctype="multipart/form-data" action="{{ route('staff.update-profile') }}">@csrf

                @include('staff.profile.form')
                <button type="submit" class="btn btn-primary float-right"> <i class="feather icon-edit"></i>  Update Profile</button>
            </form>
        </div>
    </div>

</div>
@endsection


@push('scripts')
<link rel="stylesheet" href="{{ asset('back2') }}/plugins/data-tables/css/datatables.min.css">
<script src="{{ asset('back2') }}/plugins/data-tables/js/datatables.min.js"></script>
<script src="{{ asset('back2') }}/js/pages/tbl-datatable-custom.js"></script>

<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {



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



    });

</script>
@endpush
