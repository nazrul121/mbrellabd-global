
@extends('common.layouts')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5>Size chirts</h5>
                <div class="card-header-right">
                    @if (check_access('create-size-chirt'))
                        <button type="button" class="btn btn-outline-primary addModal"><i class="feather icon-plus"></i> Add New</button> @endif

                </div>
            </div>

            <div class="card-body">
                <div class="row">
                    @foreach ($photos as $item)
                    <div class="col-sm-3 col-md-2 col-lg-2 p-2 row{{ $item->id }}">
                        <img class="chirtImg" data-id="{{ $item->id }}" src="{{ url('storage/'.$item->photo) }}" alt="{{ $item->title }}" style="width:100%;cursor:pointer;" title="{{ $item->title }}">
                    </div>
                    @endforeach
                    @if($photos->count()<1)
                        <p class="alert alert-warning text-center">No size-chirt added yet.  <button type="button" class="btn btn-sm btn-info addModal"><i class="feather icon-plus"></i> Add New</button></p>
                    @endif
                </div>
                <div class="row mt-5">
                    <div class="paginate">
                        {{$photos->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('common.size-chirt.modal')

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
