

<p class="alert alert-info">
    Order ID: #<code><?php echo e($order->id); ?></code>, &nbsp;  Invoice Number: #<code><?php echo e($order->invoice_id); ?></code>, &nbsp; Transaction ID: #<code><?php echo e($order->transaction_id); ?></code>
</p>

<div class="row"> <div class="restul col-12"></div></div>

<?php $statusAction = \App\Models\Order_status::where('id',$order->order_status_id)->pluck('action')->first();?>

<?php if($statusAction=='continue'): ?>
    <a class="btn btn-secondary btn-sm float-right" data-toggle="collapse" href="#changeProcedure" role="button" aria-expanded="false" aria-controls="changeProcedure" style="position:relative;top:-58px;">
        <span class="fas fa-truck"></span>  Change Order procedure</a>
    <div class="collapse" id="changeProcedure">
        <form class="bg-light p-3 mb-4" id="changeStatus" method="get" action="<?php echo e(route('common.order.delivery-process',$order->id)); ?>"> <?php echo csrf_field(); ?>
            <div class="form-group row">
                <label class="text-center">Order status</label>
                <select name="status" class="form-control">
                    <option value="">Choose Action</option>
                    <?php $__currentLoopData = $order_status; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ostatus): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <?php $oStatusID = \App\Models\Order_status_change::where(['order_id'=>$order->id,'order_status_id'=>$ostatus->id])->pluck('order_status_id')->first();?>
                        <option data-activity="<?php echo e($ostatus->relational_activity); ?>" <?php if($oStatusID==$ostatus->id): ?>disabled <?php endif; ?> value="<?php echo e($ostatus->id); ?>"> <?php echo e($ostatus->id.'. '.$ostatus->title); ?> - <?php echo e($ostatus->description); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <input type="hidden" name="status_text">
            </div>

            <div class="ship_alert"></div>
            <div class="payment_info"></div>
            <div class="payment_form form-group" style="display:none;background:#d1ecf0;padding:7px;">
                <?php echo $__env->make('common.order.include.payment-form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </div>

            <div class="form-group">
                <textarea name="note" class="form-control" placeholder="Type Note (if any)" rows="2"></textarea>
            </div>
            <div class="form-group">
                <button class="btn btn-primary float-right mr-0 changeStatusBtn" type="submit" disabled><i class="fa fa-edit"></i> Change status</button>
            </div>
        </form>
    </div>
<?php else: ?>
    <p class="text-danger alert alert-danger">The order status already been updated as <b class="text-info"><?php echo e($order->order_status->title); ?></b></p>
<?php endif; ?>
<br>


<div class="table-responsive mt-4">
    <table class="table table-hover bg-white orderStatusChangeTable" style="width:100%">
        <thead>
            <tr><th>ID</th><th>Order Status</th><th>Note</th> <th>Date</th> <th>Modify by</th>
            </tr>
        </thead>
    </table>
</div>

<link rel="stylesheet" href="<?php echo e(asset('back2')); ?>/plugins/data-tables/css/datatables.min.css">
<link rel="stylesheet" href="<?php echo e(asset('back2')); ?>/plugins/multi-select/css/multi-select.css">
<script src="<?php echo e(asset('back2')); ?>/plugins/data-tables/js/datatables.min.js"></script>
<script src="<?php echo e(asset('back2')); ?>/js/pages/tbl-datatable-custom.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.5.1/moment.min.js"></script>

<style> .dataTables_length{display:none} </style>
<script>
    $(function(){
        $(function () { table.ajax.reload(); });

        let table = $('.orderStatusChangeTable').DataTable({
            processing: true,serverSide: true,
            "bFilter": false, "bInfo": false,

            "language": { processing: '<img src="'+url+'/storage/images/ajax-loader.gif">'},
            ajax: "<?php echo e(route('common.order.delivery-process',$order->id)); ?>",
            order: [ [0, 'desc'] ],
            columns: [
                {data:'id'},
                {data: 'order_status_id'}, {data: 'note'},{data: 'date'},
                {data: 'user_id', orderable: false, searchable: false, class: 'text-right'}
            ]
        });


        $('[name=status]').on('change',function(){
            $('.status_result').html(''); $('.payment_info').html('');
            let status_id = $(this).val();
            let relationalData = $(this).find(':selected').attr('data-activity');

            if(status_id !=''){
                if(relationalData=='ask-for-payment'){
                    $.get( "<?php echo e(route('common.check-order-payment',$order->id)); ?>", function( data ) {
                        if(data[0] == true){
                            jQuery.each( data[1], function( i, val ) {
                                $('.payment_info').append('<p class="alert alert-success mb-0"><i class="fa fa-check"></i> <b>Payment in</b> '+val.payment_type.title+', <b>Paid amount: </b>'+val.amount+', <b>Date</b>: '+ moment(val.created_at).format('DD/MM/YYYY h:mm A')+' </p>');
                            });
                            $('.payment_info').append('<p class="alert text-right p-0"><label class="btn btn-light btn-sm mr-0"><input type="checkbox" name="avoid_payment" value="exist"/> Avoid new payment</label></p>');
                        }else{
                            // $('.payment_info').append('<p class="alert text-right p-0"><label class="btn btn-light btn-sm mr-0"><input type="checkbox" name="avoid_payment" value="avoid"/> Avoid  payment step</label></p>');
                        }
                        // if(data[0] == false) $('.payment_info').html('');
                    });

                    $('.payment_form').css('display','block');
                    $('[name=payment_type]').prop('required',true);
                    $('[name=amount]').prop('required',true);
                }else {
                    $('.payment_form').css('display','none');
                    $("[name=amount]").val('');  $('[name=payment_type] option:selected').prop("selected", false);
                    $('[name=payment_type]').prop('required',false);
                    $('[name=amount]').prop('required',false);
                }

                if(relationalData=='refund'){
                    $.get( "<?php echo e(route('common.check-order-payment',$order->id)); ?>", function( data ) {
                        if(data[0] == true){
                            jQuery.each( data[1], function( i, val ) {
                                $('.payment_info').append('<p class="alert alert-success mb-0"><i class="fa fa-check"></i> <b>Payment in</b> '+val.payment_type.title+', <b>Paid amount: </b>'+val.amount+', <b>Date</b>: '+ moment(val.created_at).format('DD/MM/YYYY h:mm A')+' </p>');
                            });
                            $('.payment_info').append('<br/>');
                        }else{
                            $('.payment_info').append('<p class="alert alert-danger mb-0"><i class="fa fa-times"></i> <b>Alert:</b> No payment made yet!</p>');
                            $('.changeStatusBtn').prop('disabled',true);
                        }
                    });
                }

                if(relationalData=='ship'){
                   $('.changeStatusBtn').attr('disabled',true);
                   $('.ship_alert').html('<p class="alert alert-danger">To change the status into <b>Shiped</b> You must ready <b>the order</b> item for <a href="/common/courier/ready-to-ship">Shiping <i class="text-info">into</i> Courier partner</a></p>');
                   $('.changeStatusBtn').css('display','none');
                   $('.payment_form').css('display','none');
                   $('[name=note]').css('display','none');
                   return false;
                }else{
                    $('.changeStatusBtn').css('display','block');
                    $('[name=note]').css('display','block');
                    $('.ship_alert').html('');
                }
                $('.changeStatusBtn').attr('disabled',false);
            }
            $('[name=status_text]').val( $(this).find(':selected').text() );

        })

        $("#changeStatus").submit(function(e) {
            e.preventDefault(); var form = $(this);
            $('.restul').html('');
            $.ajax({
                type: "get", url: form.attr('action'), data: form.serialize(),
                success: function(data){
                    $('.restul').html(data[0]);
                    $('.orderStatusChangeTable').DataTable().ajax.reload();
                    $('[name=note]').val('');
                    setTimeout(function() { if(data[1]=='reload'){ location.reload();} }, 2000);
                }
            })
        });

        $('.payment_info').on('change','[name=avoid_payment]',function(){
            if( $('[name=avoid_payment]').is(':checked') ){
                $('.payment_form').slideUp();
                $('[name=payment_type]').prop('required',false);
                $('[name=amount]').prop('required',false);
            }else {
                $('.payment_form').slideDown();
                $('[name=payment_type]').prop('required',true);
                $('[name=amount]').prop('required',true);
            }
        })

    })
</script>
<?php /**PATH /var/www/laravelapp/resources/views/common/order/include/delivery.blade.php ENDPATH**/ ?>