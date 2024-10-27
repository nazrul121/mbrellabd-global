
<div class="card-block table-border-style">
    <div class="table-responsive">
        <table class="paymentTable table table-columned">
            <thead>
                <tr>
                    <th>Payment Type</th>
                    <th>Amount</th>
                    <th>Payer info</th>
                    <th>Recept by</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

{{-- @if($order->order_status_id > $orderStatusID) --}}
    <form action="{{ route('common.order.create-payment',$order->id) }}" class="bg-light mt-3" id="paymentForm" method="post">@csrf
        <div class="payment_result"></div>
        @include('common.order.include.payment-form')
        <div class="form-group mt-4">
            <button class="btn btn-primary float-right mr-0 paymentBtn" type="submit" ><i class="fa fa-check"></i> Accept Payment</button>
        </div>
    </form>
{{-- @endif --}}

<link rel="stylesheet" href="{{ asset('back2') }}/plugins/data-tables/css/datatables.min.css">
<link rel="stylesheet" href="{{ asset('back2') }}/plugins/multi-select/css/multi-select.css">
<script src="{{ asset('back2') }}/plugins/data-tables/js/datatables.min.js"></script>
<script src="{{ asset('back2') }}/js/pages/tbl-datatable-custom.js"></script>
<style> .dataTables_length{display:none} </style>

<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {

        $(function () { table.ajax.reload(); });

        let table = $('.paymentTable').DataTable({
            processing: true,serverSide: true, "bFilter": false, "bInfo": false,
            "language": { processing: '<img src="'+url+'/storage/images/ajax-loader.gif">'},
            ajax: "{{route('common.order.ask-for-payment',$order->id)}}",
            order: [ [0, 'desc'] ],
            columns: [
                {data: 'payment_type', orderable: false, searchable: false},
                {data: 'amount'},
                {data: 'order_info', orderable: false, searchable: false},
                {data: 'received_by', orderable: false, searchable: false},
            ]
        });

        $("#paymentForm").submit(function(event) {
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
                        html = '<div class="alert alert-warning alert-dismissible fade show m-0" role="alert"><strong class="text-danger">Warning! </strong>';
                        for(var count = 0; count < data.errors.length; count++)
                        { html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span></button>' + data.errors[count] + '</p>';break;}
                        html += '</div>';
                    }
                    if(data.success){
                        html = '<div class="alert alert-success alert-dismissible fade show m-0" role="alert"><strong class="text-info">Success! </strong> ' + data.success + '</div>';
                        $('.paymentTable').DataTable().ajax.reload();
                    }
                    $("[type='submit']").text('Update Data');
                    $("[type='submit']").prop('disabled',false);
                    $('.payment_result').html(html);
                }
            });
        });

    });
</script>

