
@extends('common.layouts')

@section('content')
@php
    if(Request::segment(3)=='date-to-date-orders'){
        $url = route( 'common.date-to-date-orders', [Request::segment(4), Request::segment(5)] );
    }
    elseif(Request::segment(3)=='all-orders') $url = route('common.all-orders');

    else $url = route('common.orders',$order_status->id);
    if($order_status->id=='3'){
        $searchBy = true;
    }else $searchBy = false;
    // echo $url;
@endphp
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h5><b>{{ $order_status->title }}</b>  dataTable</h5>
                    <button class="btn btn-sm btn-secondary readyBtn" disabled>Ready to Ship</button>

                    <div class="card-header-right">
                        <div class="input-daterange input-group" id="datepicker_range">
                            <input type="text" class="form-control text-left" placeholder="Start date" name="start" value="{{ str_replace('-','/',Request::segment(4)) }}">
                            <input type="text" class="form-control text-right" placeholder="End date" name="end" value="{{ str_replace('-','/',Request::segment(5)) }}">
                            <button type="button" class="btn btn-dark searchDateBtn"><i class="fa fa-search"></i> Searach</button>
                        </div>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered orderTable" style="width:100%">
                            <thead>
                                <tr><th> @if($order_status->id=='3')<input type="checkbox" id="checkAll" style="width:25px;height:25px"> @else # @endif </th>
                                    <th>Transaction ID</th>
                                    <th>Product info</th>
                                    <th>Customer info</th>
                                    <th>Source</th>
                                    <th>Order info</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('common.order.modal')

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


<script src="{{ asset('back2')}}/plugins/bootstrap-datetimepicker/js/bootstrap-datepicker.min.js"></script>
<script src="{{ asset('back2')}}/js/pages/ac-datepicker.js"></script>

<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {
 
        let table = $('.orderTable').DataTable({
            processing: true,serverSide: true,
            "language": { processing: '<img src="'+url+'/storage/images/ajax-loader.gif">'},
            ajax: "{{$url}}",
            order: [ [0, 'desc'] ],
            columns: [
                @if($order_status->id=='3')
                    {data: 'id', name:'id', class:'ready2ship', orderable: false, searchable:false},
                @else  
                    {data: 'id', name:'id', class:'ready2ship'},
                @endif

                {data: 'transaction_id', name: 'transaction_id'},
                {data: 'product_info', orderable: false, searchable: false},
                {data: 'customer_info', orderable: false, searchable: false},
                {data: 'ref'},
                {data: 'order_info', orderable: false, searchable: false}
            ]
        });

        $('.orderTable').on('click','.note',function(){
            let transaction_id = $(this).data('transaction_id');
            $('#orderModal').modal('show');
            let customer = $('.cusInfo'+transaction_id).text();
            $('#orderModalLabel').text(customer);
            $('.modalData').html($(this).attr('title'));
        })

        $('.orderTable').on('click','.shippingInfo',function(){
            $('#orderModal').modal('show');
            let id = $(this).data('id');
            let transaction = $(this).data('transaction_id');
            let customer = $('.cusInfo'+transaction).text();
            $('#orderModalLabel').text(customer);
            $.get( url+"/common/order/shipping-address/"+id, function(data, status){
                $('.modalData').html(data);
            });
        })

        $('.orderTable').on('click','.deliveryPorcess',function(){
            $('#deliveryModal').modal('show');
            let id = $(this).data('id');
            $.get( url+"/common/order/delivery-process/"+id, function(data, status){
                $('.delivery_result').html(data);
            });
        })

        $('.orderTable').on('click','.payment',function(){
            $('#paymentModal').modal('show');
            let transaction_id = $(this).data('transaction_id');
            let customer = $('.cusInfo'+transaction_id).text();
            $('#paymentModalLabel').text(customer);
            let id = $(this).data('id');
            $('#paymentForm').attr('action','/common/order/create-payment/'+id);
            $.get( url+"/common/order/ask-for-payment/"+id, function(data, status){
                $('.paymentResult').html(data);
            });
        });

        $('.orderTable').on('click','.dhl', function(){
            var id = $(this).data('id');
            $('.dhlDetails').html('Data loading....');
            $('#dhlModal').modal('show');
            $.get( url+"/common/order/order-dhl/"+id, function(data, status){
                $('.dhlDetails').html(data);
            });
        })


        var clicked = false;

        $(".orderTable").on("click", '#checkAll', function() {
            $("input.selectedOrder").prop("checked", !clicked);
            clicked = !clicked;
            this.innerHTML = clicked ? 'Deselect' : 'Select';

            if($('input.selectedOrder').is(':checked')){
                $('.readyBtn').prop('disabled',false);
            }else $('.readyBtn').prop('disabled',true);
        });

        $('.orderTable').on('change','.ready2ship',function(e){
            if($('input.selectedOrder').is(':checked')){
                $('.readyBtn').prop('disabled',false);
            }else $('.readyBtn').prop('disabled',true);
        })

        $('.readyBtn').on('click',function(event){
            var searchIDs = $(".ready2ship input:checkbox:checked").map(function(){
                return $(this).val();
            }).toArray();
            $('#shipModal').modal('show');
            // console.log(searchIDs);
            $.get( url+"/common/order/prepare-to-ship/"+searchIDs, function(data, status){
                $('.ready2Ship').html(data);
            });
        });

        $('.searchDateBtn').on('click', function(){
            var start = $('[name=start]').val().replace("/","-").replace("/","-");
            var end = $('[name=end]').val().replace("/","-").replace("/","-");

            if(start =='' || end==''){
                alert('Please select date rage');
            }else{
                window.location.replace("/common/order/date-to-date-orders/"+start.replace('/','-')+'/'+end.replace('/','-'))
            }

        })

    });
</script>

@endpush
