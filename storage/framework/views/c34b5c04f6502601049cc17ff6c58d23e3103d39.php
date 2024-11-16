<?php $__env->startSection('content'); ?>
    <?php include_once(resource_path('views/common/product/url.php'));?>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h5>Product dataTable <b><?php echo e(Request::segment(4)); ?></b></h5>
                    <div class="card-header-right">
                        <select class="btn bg-light" name="category" id="category" disabled>
                            <option value="">Choose Group</option>
                            <?php $__currentLoopData = \App\Models\Group::get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($cat->id); ?>"><?php echo e($cat->title); ?></option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <a class="btn btn-outline-info" data-toggle="collapse" href="#collapseExample" role="button" aria-expanded="false" aria-controls="collapseExample"><span class="feather icon-search"> Advance Search</span></a>
                        <a href="<?php echo e(route('common.product.create')); ?>" class="btn btn-outline-primary "><span class="feather icon-plus"></span> Add New</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="collapse mb-4 <?php if(request()->get('design_code') || request()->get('category_id')): ?>show <?php endif; ?>" id="collapseExample">
                        <?php echo $__env->make('common.product.search-form', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover bg-white productTable" style="width:100%">
                            <thead>
                                <tr><th>#</th>
                                    
                                    <th>Country for</th> <th>Image</th> <th>Product title</th><th>Design code</th><th>Categories</th>
                                    <th>Price</th> <th>Qty</th> <th>Status</th> <th>Actions</th></tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php echo $__env->make('common.product.modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
<link rel="stylesheet" href="<?php echo e(asset('back2')); ?>/plugins/data-tables/css/datatables.min.css">
<script src="<?php echo e(asset('back2')); ?>/plugins/data-tables/js/datatables.min.js"></script>
<script src="<?php echo e(asset('back2')); ?>/js/pages/tbl-datatable-custom.js"></script>

<?php if(request()->product): ?>
    <script>
        let id = "<?php echo e(request()->product); ?>";
        $('#VarientModal').modal('show');
        $('#varientLable').text("<?php echo e(request()->title); ?>" )
        $.get( "/common/catalog/product/variant/product-variants/"+id, function(data, status){
            $('.variantArea').html(data);
        });
    </script>
<?php endif; ?>
<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {

        $(function () { table.ajax.reload(); });

        let table = $('.productTable').DataTable({
            processing: true,serverSide: true,
            stateSave: true,

            "lengthMenu": [[15,30, 50, 100, 200, 300], [15,30, 50, 100, 200,300]],
            "language": { processing: '<img src="'+url+'/storage/images/ajax-loader.gif">'},
            ajax: "<?php echo e($url); ?>",
            columns: [
                { data: 'id' }, // Assuming 'id' is needed for the class
                { 
                    data: 'country', 
                    orderable: false, 
                    searchable: false, 
                    render: function (data, type, row) {
                        // Dynamically generate the class name
                        return `<span class=" country c${row.id}">${data}</span>`;
                    }
                },
                { data: 'photo', orderable: false, searchable: false },
                { data: 'title' },
                { data: 'design_code' },
                { data: 'categories', orderable: false, searchable: false },
                { data: 'sale_price' },
                { data: 'qty' },
                { data: 'status', orderable: false, searchable: false },
                { data: 'modify', orderable: false, searchable: false, class: 'text-right' }
            ],
            order: [[0, 'desc']], 
        });

        $('.productTable').on('click', '.delete' ,function(e){
            if(confirm('Are you sure to delete the product item??')){
                let id = $(this).attr('id')
                $.ajax({
                    url: url+"/common/catalog/product/delete/"+id+"",   dataType:"json",
                    success:function(data){
                        if(data.error) alert(data.error); if(data.success) $('.productTable').DataTable().ajax.reload();
                    }
                });
            }
        });

        $('.productTable').on('click', '.variants' ,function(e){
            let id = $(this).attr('id');
            $('#VarientModal').modal('show');
            $('#varientLable').text( $(this).data('title') )
            $.get( url+"/common/catalog/product/variant/product-variants/"+id, function(data, status){
                $('.variantArea').html(data);
            });
        });

        $('.productTable').on('click', '.status' ,function(e){
            let id = $(this).data('id');
            let status = $(this).data('status');
            $(this).text('working..');
            $.get( url+"/common/catalog/product/change-status/"+id, function(data, status){
                table.ajax.reload();
            });
        });

        $('.productTable').on('change', '.checkProduct' ,function(e){
            if($('input.checkProduct').is(':checked')){
                $('[name=category]').prop('disabled',false);
            }else $('[name=category]').prop('disabled',true);
        })

        $('[name=category]').on('change',function(event){
            var searchIDs = $('input:checked').map(function(){
                let values = '';
                if($(this).val() =='on' || $(this).val() =='') values = null;
                else  values = $(this).val();
                return values;
            });
            let group_id = $(this).val();
            // console.log(searchIDs.get());
            $.get( url+"/common/catalog/product/assign2group/"+group_id+"/"+searchIDs.get(), function(data, status){
                alert(data.success);
            });
        });


        $('.productTable').on('click', '.colors' ,function(e){
            let id = $(this).attr('id');
            $('#colorModal').modal('show');
            $.get( url+"/common/catalog/product/variation-photo/"+id, function(data, status){
               $('.colorResult').html(data);
            });
        });

        $('.productTable').on('click', '.meta' ,function(e){
            let id = $(this).attr('id');
            $('#metaModal').modal('show');
            $.get( url+"/common/catalog/product/meta/"+id, function(data, status){
               $('.metaResult').html(data);
            });
        });

        $('[name=design_code]').on('change',function(){
            $("[name=category_id] option:selected").prop("selected", false);
            $("[name=sub_category_id] option:selected").prop("selected", false);
            $("[name=child_category_id] option:selected").prop("selected", false);
        });

        //get sub-categories
        $('[name=category_id]').on('change',function(){
            $("[name=sub_category_id]").html('<option value="">Choose sub-groups</option>');
            $("[name=design_code] option:selected").prop("selected", false)
            if($(this).val().length >0){
                sub_categories( $(this).val() );
            }else{
                $("[name=sub_category_id]").html('');
            }
        });

        // get child-category
        $('[name=sub_category_id]').on('change',function(){
            $("[name=child_category_id]").html('<option value="">Choose Child-groups</option>');
            if($(this).val().length >0){
                child_categories($(this).val());
            }else $("[name=child_category_id]").html('');
        });

        $('.productTable').on('click', '.quickEdit', function (e) {
            e.preventDefault();
            let id = $(this).attr('id');

            // Select the correct span with the exact matching class
            let countrySpan = $(this).closest('tr').find(`.c${id}`);

            let ids = countrySpan.find('img').map(function () {
                return $(this).attr('id'); 
            }).get();


            $('.newCountries').each(function (index, element) {
                if (ids.includes($(element).val())) {
                    $(element).prop('checked', true);
                }
            });

            // Set the form action and populate the hidden input field
            $('#id').val(id);
            $('#quickEditForm').attr('action', `/common/catalog/product/quick-update/${id}`);

            // Show the modal and reset the quick result section
            $('#quickEditModal').modal('show');
            $('.quick_result').html('');
        });


        $("#quickEditForm").submit(function(event) {
            event.preventDefault();
            $(".submitQuickEdit").html(' Loading...');$('.add_result').html('');
            $(".submitQuickEdit").prop('disabled',true);
            var form = $(this);var url = form.attr('action');
            var html = '';
            $.ajax({
                url:url, method:"post", data: new FormData(this),
                contentType: false,cache:false, processData: false,
                dataType:"json",
                success:function(data){
                    if(data.errors) {
                        html = '<div class="alert alert-warning alert-dismissible fade show" role="alert"><strong class="text-danger">Warning! <br/> </strong>';
                        for(var count = 0; count < data.errors.length; count++)
                        { html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span></button>' + data.errors[count] + '</p>';break;}
                        html += '</div>';
                    }
                    if(data.success){
                        html = '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong class="text-info">Success! </strong> ' + data.success +'</div>';

                        table.ajax.reload(function() {
                            var state = table.state();
                            if (state) {
                                // Restore column search
                                table.columns().eq(0).each(function(colIdx) {
                                    var colSearch = state.columns[colIdx].search.search;
                                    table.column(colIdx).search(colSearch);
                                });
                                // Restore table order
                                table.order(state.order).draw(false);
                            }
                        }, false);

                    }
                    $(".submitQuickEdit").html('<i class="fas fa-edit"></i> Submit edit');
                    $(".submitQuickEdit").prop('disabled',false);
                    $('.quick_result').html(html);
                }
            });
        });

    });



    function sub_categories(cat_id){
        if(cat_id=='all') return false;
        $.ajax({ url:"/common/group2sub-categories/"+ cat_id, method:"get",
            success:function(data){
                $.each(data, function(index, value){
                    $("[name=sub_category_id]").append('<option value="'+value.id+'">'+value.title+'</option>');
                });
            }
        });
    }

    function child_categories(sub_cat){
        $.ajax({ url:"/common/innerGroup2child-categories/"+ sub_cat, method:"get",
            success:function(data){
                $.each(data, function(index, value){
                    $("[name=child_category_id]").append('<option value="'+value.id+'">'+value.title+'</option>');
                });
            }
        });
    }

</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('common.layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH D:\xampp-php-8.2\htdocs\laravelapp\resources\views/common/product/index.blade.php ENDPATH**/ ?>