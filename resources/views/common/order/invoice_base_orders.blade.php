
@extends('common.layouts')

@section('content')
    <?php 
    if(request()->start_date && request()->end_date){
        $url = '/common/report/invoice-base-orders?start_date='.request()->start_date.'&end_date='.request()->end_date;
        $excel_url = '/common/report/invoice-base-order-excel?start_date='.request()->start_date.'&end_date='.request()->end_date;
        $pdf_url = '/common/report/invoice-base-order-pdf?start_date='.request()->start_date.'&end_date='.request()->end_date;
    }else {
        $url = '/common/report/invoice-base-orders';
        $excel_url = '/common/report/invoice-base-order-excel';
        $pdf_url = '/common/report/invoice-base-order-pdf';
    }
    ?>
    <input type="hidden" id="order_url" value="{{$url}}">


    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="row mt-2 p-2">
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="row">
                                    <label class="col-md-4 text-right mt-2">Date From</label>
                                    <input type="date" class="form-control col-md-8" placeholder="Start date" name="start_date" value="{{ request()->start_date }}">
                                </div>
                            </div>
                            
                            <div class="col-5">
                                <div class="row">
                                    <label class="col-md-4 text-right mt-2">Date To</label>
                                    <input type="date" class="form-control col-md-8" placeholder="End date" name="end_date" value="{{ request()->end_date }}">
                                </div>
                            </div>

                            <div class="col-2">
                                <div class="row">
                                    <button type="button" class="btn btn-dark searchDateBtn"><i class="fa fa-search"></i> View</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5 text-right">
                        
                        <a @if(request()->start_date && request()->end_date) href="{{$excel_url}}" target="_blank" @else  @endif class="btn btn-info excelBtn"><b class="fas fa-file-excel"></b> Excel</a>
                        <a @if(request()->start_date && request()->end_date) href="{{$pdf_url}}" target="_blank" @else @endif class="btn btn-secondary pdfBtn"><b class="fas fa-file-pdf"></b> PDF</a>
                        <a href="{{route('common.order-export')}}" class="btn btn-warning btn-sm">Individual product sale Report</a>
                    </div>
                </div>

                <div class="card-body">
                    <h3 class="title">Product base order report</h3>
                    <table class="table bg-white table-hover table-bordered orderTable" style="width:100%">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Order No</th>
                                <th>Phone</th>
                                <th>Qty</th>
                               
                                <th>Total cost</th>
                                <th>Delivery Status</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
    </div>
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


<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {
        var order_url = $('#order_url').val();

        $(function(){ table.ajax.reload();});
  
        let table = $('.orderTable').DataTable({
            processing: true,serverSide: true,
            "language": { processing: '<img src="'+url+'/storage/images/ajax-loader.gif">'},
            searching: true, paging: true, info: true,

            ajax: url+order_url,
            order: [ [0, 'desc'] ],
            columns: [
                {data: 'created_at'},
                {data: 'invoice_id'},
                {data: 'customer', orderable: false, searchable: false},
                {data: 'total_items'},
                {data: 'total_cost'},
                {data: 'status', orderable: false, searchable: false}
            ],
           
        }); 

        $('.searchDateBtn').on('click', function(){
            var start = $('[name=start_date]').val().replace("/","-").replace("/","-");
            var end = $('[name=end_date]').val().replace("/","-").replace("/","-");

            if(start =='' || end==''){
                alert('Please select date rage');
            }else{
                window.location.replace("/common/report/invoice-base-orders?start_date="+start+'&end_date='+end)
            }
        })

        $('.orderTable').on('click','.getOrderItems', function(){
            var id = $(this).data('id');
            alert(id);
        })
        

    });
</script>

@endpush