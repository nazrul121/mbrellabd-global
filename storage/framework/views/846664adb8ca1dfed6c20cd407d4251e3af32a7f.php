<br>
<table class="table table-hover bg-white productTbl mt-5" style="width:100%">
    <thead>
        <tr><th>Image</th> <th> Product title</th><th> Design code</th>
        <th>Sale price</th> <th>discount Price</th><th>Qty</th> <th>Actions</th></tr>
    </thead>
</table>

<?php
if(request()->get('category_id')) $url = route('common.category-products',request()->get('category_id'));
if(request()->get('sub_category_id')) $url = route('common.sub-category-products',request()->get('sub_category_id'));
if(request()->get('child_category_id')) $url = route('common.child-category-products',request()->get('child_category_id'));
?>

<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $(document).ready(function () {
        $(function () { table.ajax.reload(); });

        let table = $('.productTbl').DataTable({
            processing: true,serverSide: true,
            "language": { processing: '<img src="/storage/images/ajax-loader.gif">'},
            ajax: "<?php echo e(route('common.promotion.products',$promotion->id)); ?>",
            order: [ [0, 'desc'] ],
            columns: [
                {data: 'photo', orderable: false, searchable: false},
                {data: 'title'},
                {data: 'design_code', orderable: false, searchable: false},
                {data: 'sale_price'},
                {data: 'discount_price'},
                {data: 'qty', orderable: false, searchable: false},
                {data: 'modify', orderable: false, searchable: false, class:'text-right'}
            ]
        });

        $('.productTbl').on('click','.delete', function(){
            var id = $(this).attr('id');
            if(confirm('Are you sure to remove the item??')){
                $.get(url+'/common/ad/promotion/remove-product/'+id,  function (data, textStatus, jqXHR) {  // success callback
                    $('.productTbl').DataTable().ajax.reload();
                });
            }

        })
    });
</script>

<?php /**PATH /var/www/laravelapp/resources/views/common/ad/promotion/flat/products.blade.php ENDPATH**/ ?>