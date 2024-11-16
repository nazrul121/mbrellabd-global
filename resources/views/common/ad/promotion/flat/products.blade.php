<div class="deleteResponse p-2 bg-light text-info text-center"></div>

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
            ajax: "{{route('common.promotion.products',$promotion->id)}}",
            order: [ [0, 'desc'] ],
            columns: [
                {data: 'photo', orderable: false, searchable: false},
                {data: 'title', orderable: false, searchable: false},
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
                $.get(url+'/common/ad/promotion/remove-product/'+id,  function (data, textStatus, jqXHR) { 
                    if(data.alert){
                        $('.deleteResponse').html("<b class='text-danger'>"+data.alert+'</b>');
                    }
                    if(data.success){
                        $('.deleteResponse').html("<b class='text-success'>"+data.success+'</b>');
                        $('.productTbl').DataTable().ajax.reload();
                    }
                });
            }

        })

        $('.productTbl').on('change', '.promoItem', function() {
            // Get all checked checkboxes with name="ids[]"
            var ids = $('[name="ids[]"]:checked').map(function() {
                return $(this).val(); 
            }).get();

            if(ids.length>0){
                $(".removeProItems").prop('disabled', false);
            }else{
                $(".removeProItems").prop('disabled', true);
            }
        });

        $('.removeProItems').on('click', function(){
            var ids = $('[name="ids[]"]:checked').map(function() {
                return $(this).val(); 
            }).get();

            var confirmation = confirm('Are you sure you want to delete those selected items form the promotion??');

            if (confirmation) {
                $.get(url+'/common/ad/promotion/remove-products/'+ids,  function (data, textStatus, jqXHR) { 
                    if(data.alert){
                        $('.deleteResponse').html("<b class='text-danger'>"+data.alert+'</b>');
                    }
                    if(data.success){
                        $('.deleteResponse').html("<b class='text-success'>"+data.success+'</b>');
                        $('.productTbl').DataTable().ajax.reload();
                    }
                    
                });
            }
            
        })

    });
</script>

