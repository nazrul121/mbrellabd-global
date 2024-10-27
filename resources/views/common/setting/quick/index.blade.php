@extends('common.layouts')

@section('content')

@if (Session::has('message'))
    <div class="card-body">
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <h5> <strong>   Action: <b class="feather icon-check"></b> {!! Session::get('message') !!}
            </strong> </h5>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
        </div>
    </div>  
@endif


<div class="row">
    <div class="col-sm-6">
        <div class="card">
            <div class="card-header"> <h5>Product settings</h5>  </div>
            <div class="card-body">   @include('common.setting.quick.product-form') </div>
        </div>
    </div>

    <div class="col-sm-6">
        <div class="card">
            <div class="card-header"> <h5>Blog photo sizing</h5>  </div>
            <div class="card-body">
                @include('common.setting.quick.blog-form')
            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="card">
            <div class="card-header pb-1"> <h5 class="text-warning">Add to Cart status</h5> 
                <button class="btn btn-warning btn-sm float-right p-1 addToCartLog" > Log View</button> 
            </div>
            <div class="card-body">
                @include('common.setting.quick.addToCart-form')
            </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card">
            <div class="card-header"> <h5>Order delivery cost option</h5>  </div>
            <div class="card-body"> @include('common.setting.quick.delivery-form') </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="card">
            <div class="card-header"> <h5>Product variation display</h5>  </div>
            <div class="card-body"> @include('common.setting.quick.variantView-from') </div>
        </div>
    </div>
    

    <div class="col-sm-8">
        <div class="card">
            <div class="card-header"> <h5>Home page` Slider photo sizing</h5>  </div>
            <div class="card-body"> @include('common.setting.quick.slider-form')   </div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card">
            <div class="card-header"> <h5>Category slide view</h5>  </div>
            <div class="card-body">
                @include('common.setting.quick.catView-form')
            </div>
        </div>
    </div>

    

    {{-- <div class="col-sm-12">
        <div class="card">
            <div class="card-header"> <h5>Product size guide</h5>  </div>
            <div class="card-body">
                @include('common.setting.quick.size-guide')
            </div>
        </div>
    </div> --}}

</div>

<div class="modal fade" id="addToCartLog" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="myModalLabel"><b>Add to cart</b> Logs</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body addToCartLogData"> </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        </div>
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

            $(function () { table.ajax.reload(); });

            let table = $('.metaTable').DataTable({
                processing: true,serverSide: true,
                "language": { processing: 'Loading...'},
                ajax: "{{route('common.addToCart-logs')}}",

                columns: [
                    {data: 'user', orderable: false, searchable: false, class:'text-right'}
                ]
            });

            $('[name=product_watermark]').on('click',function(){
                let data = $(this).val();
                let newData = '';
                if(data=='1') newData = 0;
                else newData = 1;
                $('[name=product_watermark]').val(newData);
            })

            $('.addToCartLog').on('click', function(){
                $('.addToCartLogData').html('Working....');
                $('#addToCartLog').modal('show');
                $.get('{{route("common.addToCart-logs")}}', function(data){
                    $('.addToCartLogData').html(data);
                })
            })

        });
    </script>
@endpush
