
<?php 
    $invoice_logo =  \App\Models\General_info::where('field','invoice_logo')->pluck('value')->first();
    $outlet_discount = array();
    $listViewVariationId = DB::table('settings')->where('type','variation-at-product-list')->pluck('value')->first();
    $binNo = DB::table('general_infos')->where('field','bin')->pluck('value')->first();
    $mushok = DB::table('general_infos')->where('field','mushak')->pluck('value')->first();

    $paidAmount = [];
?>

<?php
    $metas = \DB::table('metas')->where('pageFor','invoice');
    $meta = \DB::table('metas')->where(['pageFor'=>'invoice', 'type'=>'title']);

    $metaTitle = 'Print invoice - '.request()->get('system_title');
    if($meta->count() >0){
        $metaTitle = $metas->pluck('description')->first();
    }
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo e($metaTitle); ?></title>
    <?php $__env->startPush('meta'); ?>
        <?php $__currentLoopData = $metas->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $meta): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <meta type="<?php echo e($meta->title); ?>" content="<?php echo e($meta->description); ?>">
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    <?php $__env->stopPush(); ?>
</head>
<body>
    <div class="container">
        <?php if($order): ?>
        <?php $payments = \App\Models\Order_payment::where('transaction_id',$order->transaction_id)->whereIn('status', ['ACCEPTED','VALID','SUCCESS']);
            $totalVat = $subtotal =  array(); $invoice_discount = 0;

            $totalInLocal = $order->total_cost;
            $shippingInLocal = $order->shipping_cost;

            $total = $totalInUSD = $shippingInUSD = 0;

            $orderInDollar = DB::table('dollar_rate_order')->where('order_id',$order->id)->first();
            if($orderInDollar !=null && $shippingInLocal >0 && $totalInLocal>0){
                $shippingInUSD = number_format( $shippingInLocal /$orderInDollar->value , 2);
                $totalInUSD = number_format($totalInLocal/$orderInDollar->value , 2);
            }
        ?>
        <div id="printableArea" style="width:595px;margin:0px auto;">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
            <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/>
            <style>body { font-family: serif;} .table{font-size:11px} .proHead{background:#fbf3e2;}</style>

            <div class="card">
                <div class="row invoice-contact">
                    <div class="col-md-12">
                        <div class="invoice-box row">
                            <div class="col-sm-12 text-capitalize">
                                <table class="table table-responsive invoice-table table-borderless">
                                    <tbody>
                                        <tr>
                                            <td style="width:60%"> <br>
                                                <span><?php echo e(request()->get('system_title')); ?></span><br>
                                                <?php echo e(request()->get('office_address')); ?> <br>
                                                <?php echo e(request()->get('system_phone')); ?> <br>
                                                <span class="text-lowercase"><?php echo e(request()->get('system_email')); ?></span>
                                            </td>
                                            <td style="width:40%;"><a href="<?php echo e(route('home')); ?>"><img src="<?php echo e(url('storage').'/'.$invoice_logo); ?>" alt=""></a><br>
                                                <b> Order Date: <?php echo e(date('F j, Y h:ia',strtotime($order->created_at))); ?><br>
                                                Invoice Number #<?php echo e($order->invoice_id); ?> <br>
                                                    BIN No: <?php echo e($binNo); ?> &nbsp; (Mushak-<?php echo e($mushok); ?> ) </b>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>

                                <table class="table table-responsive table-bordered">
                                    <tbody>
                                        <tr>
                                            <td style="width:60%">
                                                <h6>Billing Information --</h6>
                                                Name: <?php echo e($order->first_name.' '.$order->last_name); ?> <br>
                                                Area: <?php echo e($order->division); ?> <i class="fa fa-long-arrow-right"></i>  

                                                <?php echo e($order->district); ?> <i class="fa fa-long-arrow-right"></i>

                                                <?php echo e($order->city); ?> <br>

                                                Address: <?php echo e($order->address); ?> <br>
                                                Phone: <?php echo e($order->phone); ?> <br>
                                                <?php if($order->email !=null): ?>
                                                    Email:  <span class="text-lowercase"><?php echo e($order->email); ?> </span>
                                                <?php endif; ?>
                                            </td>

                                            <td style="width:40%;">
                                                <h6>Shipping Information --</h6>
                                                Name: <?php echo e($order->ship_first_name.' '.$order->ship_last_name); ?> <br>

                                                Area: <?php echo e($order->ship_division); ?> <i class="fa fa-long-arrow-right"></i> 
                                                <?php echo e($order->ship_district); ?> <i class="fa fa-long-arrow-right"></i> 
                                                <?php echo e($order->ship_city); ?> <br> 

                                                Address: <?php echo e($order->ship_address); ?> <br>
                                                Phone: <?php echo e($order->ship_phone); ?> <br>
                                              
                                                Email:  <span class="text-lowercase"><?php echo e($order->ship_email); ?> </span>
                                                
                                            </td>

                                        </tr>
                                    </tbody>
                                </table>

                                <table class="table table-bordered m-0" style="">
                                    <thead class="proHead" style="vertical-align:middle;">
                                        <tr>
                                            <td colspan="2">Product info</td>
                                            <td class="text-center">Qty</td>
                                            <td class="text-center">Unit Price</td>
                                            <td class="text-center">VAT %</td>
                                            <td class="text-center">Vat Amount</td>
                                            <td style="text-align:right">Total</td>
                                        </tr>
                                    </thead>
                            
                                        <?php $__currentLoopData = $order->order_items()->where('status','!=','removed')->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order_item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                if($order_item->vat_type=='excluding'){
                                                    $vatExcl = ($order_item->vat / 100) * $order_item->discount_price;
                                                    $vatAmount = ($order_item->vat / 100) * $order_item->discount_price;
                                                }else{
                                                    $vatAmount = ($order_item->vat * ($order_item->discount_price * $order_item->qty) ) / ($order_item->vat + 100);
                                                    $vatExcl = 0;
                                                }
                                                $totalVat[] = $vatExcl;

                                                if($order_item->variation_option_id !=null){
                                                    $thumb = \DB::table('variation_option_photos')->where(['variation_id'=>$listViewVariationId,'product_id'=>$order_item->product_id,'variation_option_id'=>$order_item->variation_option_id])->pluck('thumbs')->first();
                                                    if($thumb==null){
                                                        $thumbs = url($order_item->product->thumbs);//$order_item->product->thumbs;
                                                    }else $thumbs = url($thumb);//$thumb;
                                                }else $thumbs = url($order_item->product->thumbs);//$order_item->product->thumbs;


                                                if($order_item->outlet_customer_id !=null){
                                                    $outlet_discount[] = ($order_item->outlet_percent / 100) * product_price($order_item->product_id, $order_item->sale_price) * $order_item->qty;
                                                }else $outlet_discount[] = 0;

                                                $disPercent = ((($order_item->sale_price - $order_item->discount_price) / $order_item->sale_price) * 100);
                                            ?>
                                            <tr>
                                                <td style="width:8%" > <img src="<?php echo e($thumbs); ?>" height="35" data-product="<?php echo e($order_item->product_id); ?>"> <?php echo e($order_item->outlet_percent); ?></td>
                                                <td style="border-left:0;width:37%">
                                                    <h5 style="font-size:11px"><?php echo e($order_item->product->title); ?> - <?php echo e($order_item->product->design_code); ?></h5>
                                                    <p style="font-size: 9px;margin-bottom:0">
                                                        <?php if($order_item->product_combination_id !=null): ?>
                                                            <?php $__currentLoopData = $order_item->product_combination()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $pComb): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                <?php $__currentLoopData = explode('~',$pComb->combination_string); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $string): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                    <?php $v = \App\Models\Variation_option::where('origin',$string)->select('title','variation_id')->first();?>
                                                                    <b> <?php echo e($v->variation->title); ?>: </b> <?php echo e($v->title); ?>

                                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                        <?php endif; ?>
                                                    </p>
                                                </td>
                                                <td style="width:5%" class="text-center"><?php echo e($order_item->qty); ?></td>

                                                <td class="text-center" style="width:15%">
                                                    <?php if($order_item->sale_price > $order_item->discount_price): ?>
                                                    <b style="text-decoration:line-through!important;"><span><?php echo e($order->country->currencySymbol); ?></span> <?php echo e(round($order_item->sale_price, 2)); ?>

                                                        : <?php echo e(round($disPercent, 2)); ?>%
                                                    </b> 
                                                    <?php endif; ?>
                                                    <span><?php echo e($order->country->currencySymbol); ?></span><?php echo e(round($order_item->discount_price, 2)); ?>

                                                </td>

                                                <td class="text-center" style="width:11%">
                                                    <?php echo e(round($order_item->vat,2)); ?>%(<small><?php if($order_item->vat_type=='excluding'): ?>Ex. <?php else: ?> Inc <?php endif; ?></small>)
                                                </td>
                                                <td style="width:12%;text-align:right"><span><?php echo e($order->country->currencySymbol); ?></span> <?php echo e(round($vatAmount,2)); ?></td>
                                                <td style="width:15%;text-align:right"><span><?php echo e($order->country->currencySymbol); ?></span> <?php echo e(round(($order_item->discount_price * $order_item->qty)+$vatExcl, 2)); ?></td>
                                            </tr>
                                            <?php $subtotal[] = round(($order_item->discount_price * $order_item->qty)+$vatExcl, 2);?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </table>
                            
                                <table class="table m-0">
                                    <tbody>
                                        <tr>
                                            <td style="text-align:right;width:80%">Sub Total </td>
                                            <td style="text-align:right"><span><?php echo e($order->country->currencySymbol); ?></span> <?php echo e(array_sum($subtotal)); ?></td>
                                        </tr>
                                        <tr>
                                            <td style="text-align:right">Shipping charge </td>
                                            <td style="text-align: right"><span><?php echo e($order->country->currencySymbol); ?></span> <?php echo e(round($order->shipping_cost , 2)); ?></td>
                                        </tr>
                                        <?php if($order->country_id !=2): ?>
                                            <tr>
                                                <td style="text-align:right">Shipping charge in USD</td>
                                                <td style="text-align: right"><span>$</span> <?php echo e($shippingInUSD); ?></td>
                                            </tr>
                                        <?php endif; ?> 

                                        <?php if($order->invoice_discount >0): ?>
                                            <?php
                                                $invoice_discount_id = DB::table('invoice_discount_order')->where('order_id',$order->id)->pluck('invoice_discount_id')->first();
                                                $invoiceDis = DB::table('invoice_discounts')->where('id',$invoice_discount_id)->first();
                                            ?>
                                            <tr>
                                                <td style="text-align:right">Invoice Discount (<?php echo e($invoiceDis->type); ?>)</td>
                                                <td style="text-align: right"><span><?php echo e($order->country->currencySymbol); ?></span> <?php echo e(round($order->invoice_discount , 2)); ?></td>
                                            </tr>
                                        <?php endif; ?>      
                                    
                                        <?php if(array_sum($outlet_discount) >0): ?>
                                            <tr>
                                                <td style="text-align:right">Membership Discount</td>
                                                <td style="text-align: right"><span><?php echo e($order->country->currencySymbol); ?></span> <?php echo e(round(array_sum($outlet_discount) , 2)); ?></td>
                                            </tr>
                                        <?php endif; ?>

                                        <tr style="font-weight: bold">
                                            <td style="text-align:right"> Grand Total : </td>
                                            <td style="text-align:right">
                                            <span> <?php echo e($order->country->currencySymbol); ?></span> <?php echo e(round( ((array_sum($subtotal) + $order->shipping_cost) - $order->invoice_discount) -array_sum($outlet_discount), 2)); ?>

                                            </td>
                                        </tr>
                                        <?php if($order->country_id !=2): ?>
                                            <tr style="font-weight: bold">
                                                <td style="text-align:right"> Total In USD: </td>
                                                <td style="text-align:right">
                                                <span> $</span> <?php echo e($totalInUSD + $shippingInUSD); ?>

                                                </td>
                                            </tr>
                                        <?php endif; ?>

                                        <?php if($payments->count() > 0): ?>
                                            <tr>
                                                <td>
                                                    <?php $__currentLoopData = $payments->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php
                                                            if(strtolower($payment->status)=='success' || strtolower($payment->status)=='accepted' || strtolower($payment->status)=='valid'){
                                                                if($order->country_id != 2) $paidAmount[] = $payment->amount / $order->country->currencyValue;
                                                                else $paidAmount[] = $payment->amount;
                                                         }  ?> 
                                                        <span class="text-secondary"> Paid Amount: <span><?php echo e($order->country->currencySymbol.$payment->amount); ?> 
                                                            on <small><?php echo e(date('M d,y h:ia',strtotime($payment->created_at))); ?> via
                                                                <?php if($payment->payment_type_id==null): ?> Not taken <?php else: ?> <?php echo e($payment->payment_type->title); ?> 
                                                                <?php endif; ?> </small> (Transaction: <?php echo e($payment->status); ?>) </span> <br>
                                                        </span>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </td>
                                                <td style="text-align:right">
                                                    Paid Amount: <span><?php echo e($order->country->currencySymbol); ?><?php echo e(number_format(array_sum($paidAmount), 2)); ?></span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>Total Due</td>
                                                <td style="text-align:right">
                                                    <span><?php echo e($order->country->currencySymbol); ?><?php echo e(number_format( ( ( (array_sum($subtotal) + $order->shipping_cost) - array_sum($paidAmount) ) ) - $order->invoice_discount, 2)); ?></span>
                                                </td>
                                            </tr>
                                        <?php endif; ?>
                                        <?php if($order->note !=null): ?><tr> <td colspan="3"> Order Notes : <?php echo e($order->note); ?> </td></tr> <?php endif; ?>
                                    </tbody>
                                </table>

                                <table class="table table-bordered">
                                    <tr><td>Payment info</td>  <td>Invoice & Transaction info</td> </tr>
                                    <tr>
                                        <td style="width:60%">

                                            <?php if($payments->count() < 1): ?>
                                                Payment Method: <?php echo $order->payment_geteway->name; ?> <br>
                                                Payment Status: <span>Unpaid</span> <br>
                                                Total Due :  <span> <?php echo e($order->country->currencySymbol); ?></span> <?php echo e(round( ((array_sum($subtotal) + $order->shipping_cost) - $order->invoice_discount) - array_sum($outlet_discount), 2)); ?>

                                            <?php else: ?>
                                                Payment Method: <?php echo $order->payment_geteway->name; ?> <br>
                                                Total Amount :  <span> <?php echo e($order->country->currencySymbol); ?></span> <?php echo e(number_format(array_sum($subtotal) + $order->shipping_cost, 2)); ?> <br>
                                                <?php $__currentLoopData = $payments->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                               
                                                    <span class="text-secondary"> Paid Amount: <span><?php echo e($order->country->currencySymbol.' '.$payment->amount); ?> on <small><?php echo e(date('M d,y h:ia',strtotime($payment->created_at))); ?> via <?php if($payment->payment_type_id==null): ?> Not taken <?php else: ?> <?php echo e($payment->payment_type->title); ?> <?php endif; ?> </small> (Transaction: <?php echo e($payment->status); ?>) </span> <br>
                                                        
                                                    </span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    
                                                ?>
                                                
                                                Total Due: <span><?php echo e($order->country->currencySymbol); ?>

                                                    <?php echo e(number_format((  (((array_sum($subtotal) + $order->shipping_cost ) - array_sum($paidAmount)) - $order->invoice_discount)) - array_sum($outlet_discount) , 2)); ?></span>
                                            <?php endif; ?>
                                        </td>
                                        <td style="width: 40%">
                                        Invoice Number: #<?php echo e($order->invoice_id); ?> <br>Transaction ID: #<?php echo e($order->transaction_id); ?>

                                        </td>

                                    </tr>
                                </table>

                            </div>
                        </div>
                    </div>
          
                </div>
            </div>
        </div>

        <div class="row text-center">
            <div class="col-sm-12 invoice-btn-group text-center">
                <br><br>
                <button onclick="printableArea()" class="btn btn-info">Print Invoice</button>
                <button onclick="history.back();"class="btn btn-secondary m-b-10 ">Back</button>
            </div>
        </div>
        <?php else: ?>
            <p class="alert alert-danger">Something went wrong. Please contact to the authority</p>
        <?php endif; ?>
    </div>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css"/>

    <script>
        function printableArea() {
            const printContents = document.getElementById('printableArea').innerHTML;
            const originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
        }
    </script>

</body>
</html><?php /**PATH D:\xampp-php-8.2\htdocs\laravelapp\resources\views/common/order/invoice.blade.php ENDPATH**/ ?>