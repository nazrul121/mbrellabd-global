
@extends('common.layouts')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5>Common Size guird for all product</h5>
                <div class="card-header-right">
                    @if (check_access('create-size-chirt-pdf'))
                    <button type="button" class="btn btn-outline-primary addModal"><i class="feather icon-plus"></i> Add New</button> @endif
                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    @if ($message = Session::get('success'))
                    <div class="alert alert-success alert-block col-12">
                        <button type="button" class="close" data-dismiss="alert">×</button> <strong>{{ $message }}</strong>
                    </div>
                    @endif
                    <?php $sizeChirt = \DB::table('general_infos')->where('field','size-chirt')->pluck('value')->first();?>
                    @if($sizeChirt) <iframe width="100%" height="800" src="/{{ $sizeChirt }}" frameborder="0"></iframe>

                    @else <p class="alert alert-warning text-center col-12">No size guird upload yet. Please a PDF of product size-guird</p>@endif

                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade"  id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form method="post" action="{{ route('common.upload-size-chirt-for-all') }}" class="modal-content"  enctype="multipart/form-data" >@csrf
            <div class="modal-header">
                <h5 class="modal-title" id="addModalLabel">Create size-chirt</h5>
                <button type="button" class="close-modal close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <strong>Whoops!</strong> There were some problems with your input.
                        <ul> @foreach ($errors->all() as $error)<li>{{ $error }}</li> @endforeach </ul>
                    </div>
                @endif
                <div class="input-field">
                    <label class="active">Upload PDF</label>
                    <input type="file" name="pdf_file" class="form-control"  accept=".pdf">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary close-modal" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-primary">Save chirt</button>
            </div>
        </form>
    </div>
</div>


@endsection


@push('scripts')
<link rel="stylesheet" href="{{ asset('back2/uploader') }}/image-uploader.min.css">
<script type="text/javascript" src="{{ asset('back2/uploader') }}/image-uploader.min.js"></script>
<style>
    .chirtImg{
        transition: all .4s ease;
        -webkit-transition: all .4s ease;
    }
    .chirtImg:hover{
        transform: scale(1.1);
        transition: all .4s ease;
        -webkit-transition: all .4s ease;
    }
</style>
<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {
        $('.input-images-1').imageUploader();

        $('.addModal').on('click', function(){
            $('#addModal').modal('show'); $('.add_result').html('');
        })

        $('.chirtImg').on('click',function(){
            $('#photoModal').modal('show');
            let src = $(this).attr('src');
            let id = $(this).data('id');

            $('.largeImg').attr('src',src);
            $('.deleteChirt').attr('data-id',id)
        });

        $('.deleteChirt').on('click' ,function(e){
            if(confirm('Are you sure to remove the record permanently?? --- There is no Undo option')){
                let id = $(this).data('id');
                $.ajax({
                    url: url+"/common/catalog/category/size-chirt/delete/"+id+"",
                    dataType:"json",
                    success:function(data){
                        if(data.error) alert(data.error);
                        if(data.success) {
                            $('#photoModal').modal('hide');
                            location.reload()
                        }
                    }
                });
            }
        });

    });

</script>

@endpush
