<?php $__env->startSection('content'); ?>
<?php 
if(request()->start_date && request()->end_date){
    $url = '/common/report/order-export?start_date='.request()->start_date.'&end_date='.request()->end_date;
    $excel_url = '/common/report/order-excel?start_date='.request()->start_date.'&end_date='.request()->end_date;
    $pdf_url = '/common/report/order-pdf?start_date='.request()->start_date.'&end_date='.request()->end_date;
}else {
    $url = '/common/report/order-export';
    $excel_url = '/common/report/order-excel';
    $pdf_url = '/common/report/order-pdf';
}
?>
<input type="hidden" id="order_url" value="<?php echo e($url); ?>">

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="row mt-2 p-2">
                    <div class="col-md-7">
                        <div class="row">
                            <div class="col-md-5">
                                <div class="row">
                                    <label class="col-md-4 text-right mt-2">Date From</label>
                                    <input type="date" class="form-control col-md-8" placeholder="Start date" name="start_date" value="<?php echo e(request()->start_date); ?>">
                                </div>
                            </div>
                            
                            <div class="col-5">
                                <div class="row">
                                    <label class="col-md-4 text-right mt-2">Date To</label>
                                    <input type="date" class="form-control col-md-8" placeholder="End date" name="end_date" value="<?php echo e(request()->end_date); ?>">
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
                        <a <?php if(request()->start_date && request()->end_date): ?> href="<?php echo e($excel_url); ?>" target="_blank" <?php else: ?>  <?php endif; ?> class="btn btn-info excelBtn"><b class="fas fa-file-excel"></b> Excel</a>
                        <a <?php if(request()->start_date && request()->end_date): ?> href="<?php echo e($pdf_url); ?>" target="_blank" <?php else: ?> <?php endif; ?> class="btn btn-secondary pdfBtn"><b class="fas fa-file-pdf"></b> PDF</a>
                        <a href="<?php echo e(route('common.invoice-base-reports')); ?>" class="btn btn-warning btn-sm">Order base Report</a>
                    </div>

                </div>

                <div class="card-body">
                    
                    <h3 class="title">Product base order report
                        <small class="float-md-right"><a class="btn btn-sm btn-primary" href="<?php echo e(route('common.deliverd-order-info')); ?>?start_date=<?php echo e(request()->start_date); ?>&end_date=<?php echo e(request()->end_date); ?>"> Deliverd Orders</a></small>
                    </h3>
                    <table class="table bg-white table-hover table-bordered orderTable" style="width:100%">
                        <thead>
                            <tr>
                                <th>Date & Time</th>
                                <th>Order No</th>
                                <th>Trans. ID</th>
                                <th>Customer</th>
                                <th>Phone</th>
                                <th>Billing info</th>
                                <th>Shipping info</th>
                                <th>Category</th>
                                <th>Design_code</th>
                                <th>Barcode</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Disc %</th>
                                <th>Disc Name</th>
                                <th>Disc Amt</th>
                                <th>Tax</th>
                                <th>Tax line</th>
                                <th>Del charge</th>
                                <th>Net amount</th>
                                <th>Payment Method</th>
                                <th>Order Status</th>
                                <th>Payment Status</th>
                                <th>Courier Name</th>
                                <th>Delivery Date</th>
                                <th>Cust. ID</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
    </div>

<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
<link rel="stylesheet" href="<?php echo e(asset('back2')); ?>/plugins/data-tables/css/datatables.min.css">
<script src="<?php echo e(asset('back2')); ?>/plugins/data-tables/js/datatables.min.js"></script>
<script src="<?php echo e(asset('back2')); ?>/js/pages/tbl-datatable-custom.js"></script>

<link rel="stylesheet" href="<?php echo e(asset('back2')); ?>/plugins/bootstrap-datetimepicker/css/bootstrap-datepicker3.min.css">
<script src="<?php echo e(asset('back2')); ?>/plugins/bootstrap-datetimepicker/js/bootstrap-datepicker.min.js"></script>
<script src="<?php echo e(asset('back2')); ?>/js/pages/ac-datepicker.js"></script>
<script src="<?php echo e(asset('back2')); ?>/js/timepicker.js"></script>
<style>
    .datepicker>.datepicker-days { display: block;}
    ol.linenums {  margin: 0 0 0 -8px;}
</style>


<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {
        var order_url = $('#order_url').val();

        $('.refresh').on('click', function(){
            $(this).html('Refreshing...');
            $(this).prop('disabled', true);
            table.ajax.reload();
            $(this).html('<b class="feather icon-refresh-cw"></b>');
            $(this).prop('disabled', false);
        });

        $(function(){ table.ajax.reload();});
  
        let table = $('.orderTable').DataTable({
            processing: true,serverSide: true,
            "language": { processing: '<img src="'+url+'/storage/images/ajax-loader.gif">'},
            searching: false, paging: true, info: true,

            ajax: url+order_url,
            order: [ [0, 'desc'] ],
            columns: [
                {data: 'date_time', orderable: false, searchable: false},
                {data: 'order_no', orderable: false, searchable: false},
                {data: 'transaction_id', orderable: false, searchable: false},
                {data: 'customer', orderable: false, searchable: false},
                {data: 'phone', orderable: false, searchable: false},
                {data: 'billing', orderable: false, searchable: false},
                {data: 'shipping', orderable: false, searchable: false},
                {data: 'category', orderable: false, searchable: false},
                {data: 'design_code', orderable: false, searchable: false},
                {data: 'barcode', orderable: false, searchable: false},
                {data: 'qty', orderable: false, searchable: false},
                {data: 'price', orderable: false, searchable: false},
                {data: 'disc', orderable: false, searchable: false},
                {data: 'disc_name', orderable: false, searchable: false},
                {data: 'disc_amt', orderable: false, searchable: false},
                {data: 'tax', orderable: false, searchable: false},
                {data: 'tax_line', orderable: false, searchable: false},
                {data: 'del_charge', orderable: false, searchable: false},
                {data: 'net_amount', orderable: false, searchable: false},
                {data: 'payment_method', orderable: false, searchable: false},
                {data: 'order_status', orderable: false, searchable: false},
                {data: 'payment_status', orderable: false, searchable: false},
                {data: 'courier', orderable: false, searchable: false},
                {data: 'del_date', orderable: false, searchable: false},
                {data: 'customer_id', orderable: false, searchable: false},
            ],
            lengthMenu: [
                [100,300,500,1000,-1],
                [100,300,500,1000, 'All'],
            ],
           
        });

        $('.searchDateBtn').on('click', function(){
            var start = $('[name=start_date]').val().replace("/","-").replace("/","-");
            var end = $('[name=end_date]').val().replace("/","-").replace("/","-");

            if(start =='' || end==''){
                alert('Please select date rage');
            }else{
                window.location.replace("/common/report/order-export?start_date="+start+'&end_date='+end)
            }
        })
        
  

    });
</script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('common.layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp-php-8.2\htdocs\laravelapp\resources\views/common/order/report.blade.php ENDPATH**/ ?>