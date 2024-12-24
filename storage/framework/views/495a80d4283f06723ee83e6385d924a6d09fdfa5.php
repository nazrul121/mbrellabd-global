<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>Sub-category dataTable &nbsp; &nbsp;
                    <div class="switch d-inline m-r-10">
                        <input type="checkbox" id="sortBy" <?php if(request()->get('sortBy')=='1'): ?>checked <?php endif; ?> >
                        <label for="sortBy" class="cr"></label>
                    </div>
                    <label for="sortBy">Show data by <b>Sorting</b></label>
                </h5>
                <div class="card-header-right">
                    <?php $groups = \DB::table('groups')->where('status','1')->get();?>

                    <select name="group" style="padding: 10px;" class="border border-info rounded">
                        <option value="">Choose group</option>
                        <?php $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $group): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($group->id); ?>" <?php if(request()->get('group')==$group->id): ?>selected <?php endif; ?> ><?php echo e($group->title); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>

                    <?php if(check_access('create-sub-category')==true): ?>
                    <button type="button" class="btn btn-outline-primary addModal"><i class="feather icon-plus"></i> Add New</button> <?php endif; ?>
                </div>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <div class="result">Result</div>
                    <table class="table catTable table-hover bg-white" style="width:100%">
                        <thead>
                            <tr>
                                <th>ID</th> <th>Country</th><th>Image</th> <th>Category Name</th>
                                <th>BelongsTo</th> 
                                <th>Product Number</th> <th>Status</th> <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody class="row_"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <?php echo $__env->make('common.category.sub.modal', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</div>
<?php $__env->stopSection(); ?>

<?php 
 $url = url('/common/catalog/category/sub?group='.request()->get('group'));
 
 if(request()->get('sortBy')){
    $url = url('/common/catalog/category/sub/'.request()->get('sortBy'));
 }

?>

<?php $__env->startPush('scripts'); ?>
<link rel="stylesheet" href="<?php echo e(asset('back2')); ?>/plugins/data-tables/css/datatables.min.css">
<script src="<?php echo e(asset('back2')); ?>/plugins/data-tables/js/datatables.min.js"></script>
<!-- <script src="<?php echo e(asset('back2')); ?>/js/pages/tbl-datatable-custom.js"></script> -->
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/jquery-ui.min.js"></script>

<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {
        $('[name=group]').on('change', function(){
            var status = $(this).val();
            var url = new URL(window.location.href);
            url.searchParams.set('group',status);
            window.location.href = url.href;
        })

        $('#sortBy').change(function () {
            if ($(this).prop('checked')) {
                updateSortByParameter(1);
            } else {
                updateSortByParameter(0);
            }
        });

        function updateSortByParameter(value) {
            // Get the current URL
            var currentUrl = new URL(window.location.href);
            // Update or add the sortBy parameter
            currentUrl.searchParams.set('sortBy', value);
            // Reload the page with the updated URL
            window.location.href = currentUrl.toString();
        }

        let table = $('.catTable').DataTable({
            processing: true,serverSide: true,
            "language": { processing: '<img src="'+url+'/storage/images/ajax-loader.gif">'},
            ajax: "<?php echo e($url); ?>",
            data:{ soryBy:'1' },
            order: [ [0, 'desc'] ],
            columns: [
                {data: 'id'},
                {data: 'country', orderable: false, searchable: false},
                {data: 'photo', orderable: false, searchable: false},
                {data: 'title'},
                {data: 'group_id'},
                {data: 'products', orderable: false, searchable: false},
                {data: 'status'},
                {data: 'modify', orderable: false, searchable: false, class:'text-right'}
            ],
            "createdRow": function (row, data, dataIndex) {
                // Set the ID as the 'id' attribute of the row
                $(row).attr('id', data.id);
            }
        });


        $('.catTable').on('click','.edit',function(){
            $('#editModal').modal('show'); $('.edit_result').html('');
            let id = $(this).attr('id');
            $('input.lang').prop('checked', false);
            $("[name=category] option:selected").removeAttr('selected');
            
            $.ajax({
                url: url+"/common/catalog/category/sub/single-item/"+id,
                type: 'get', dataType: 'json',
                success: function (data) {
                    $('[name=title]').val(data.title);$('#id').val(data.id);
                    $('[name=description]').val(data.description);
                    $('[name=display_name]').val(data.display_name);
                    $('#editForm').attr('action',url+'/common/catalog/category/sub/update/'+id);
                    if(data.status == 1){
                        $('input.status[value="1"]').prop('checked', true);
                    }else $('input.status[value="0"]').prop('checked', true);

                    if(data.is_top == 1){
                        $('[name=is_top]').prop('checked', true);
                    }else $('[name=is_top]').prop('checked', false);

                    setTimeout(function() {
                        $("[name=category] option").each(function(){
                            if ($(this).val() == data.group_id)  $(this).attr("selected",true);
                        });

                        $.each(data.country, function(key, value) {
                            $('input.lang[value="'+value.country_id+'"]').prop('checked', true);
                        });
                    }, 300);

                    $('#editModal .oldPhoto').val(data.photo)
                    $('#editModal .setPhoto').attr('src',url+'/storage/'+data.photo)
                }
            });
        })

        $('.addModal').on('click', function(){
            $('#addModal').modal('show'); $('#addForm').trigger("reset");
            $('.add_result').html(''); $('#output').attr('src',url+'/storage/images/thumbs_photo.png');
        })

        $("#addForm").submit(function(event) {
            event.preventDefault();
            $("[type='submit']").html(' Loading...');$('.add_result').html('');
            $("[type='submit']").prop('disabled',true);
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
                        $('.catTable').DataTable().ajax.reload();
                        // setTimeout(function() { $('#addModal').modal('hide');}, 1000);
                    }
                    $("[type='submit']").text('Save Data');
                    $("[type='submit']").prop('disabled',false);
                    $('.add_result').html(html);
                }
            });
        });

        $("#editForm").submit(function(event) {
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
                        html = '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong class="text-danger">Warning! </strong>';
                        for(var count = 0; count < data.errors.length; count++)
                        { html += '<button type="button" class="close" data-dismiss="alert" aria-label="Close"> <span aria-hidden="true">×</span></button>' + data.errors[count] + '</p>';break;}
                        html += '</div>';
                    }
                    if(data.success){
                        html = '<div class="alert alert-success alert-dismissible fade show" role="alert"><strong class="text-info">Success! </strong> ' + data.success + '</div>';
                        $('.catTable').DataTable().ajax.reload();
                        setTimeout(function() { $('#editModal').modal('hide');}, 1000);
                    }
                    $("[type='submit']").text('Update Data');
                    $("[type='submit']").prop('disabled',false);
                    $('.edit_result').html(html);
                }
            });
        });

        $('.catTable').on('click', '.delete' ,function(e){
            if(confirm('Are you sure to remove the record permanently?? --- There is no Undo option')){
                let id = $(this).attr('id')
                $.ajax({
                    url: url+"/common/catalog/category/sub/delete/"+id+"",
                    dataType:"json",
                    success:function(data){
                        if(data.error) alert(data.error);
                        if(data.success) $('.catTable').DataTable().ajax.reload();
                    }
                });
            }
        });

        $('.catTable').on('click', '.meta' ,function(e){
            let id = $(this).attr('id');
            $('#metaModal').modal('show');
            $.get( url+"/common/catalog/sub/meta/"+id, function(data, status){
                $('.metaResult').html(data);
            });
        });

        $(".row_" ).sortable({
            placeholder : "ui-state-highlight",
            update  : function(event, ui){
                var page_id_array = new Array();
                $('.row_ tr').each(function(){ page_id_array.push($(this).attr("id")); });

                $.ajax({
                    url:"<?php echo e(route('common.sub-group-ordering')); ?>",method:"get",
                    data:{page_id_array:page_id_array},
                    success:function(data) { $('.result').html(data);}
                });
            }
        });

    });

</script>
<script>
    var loadFile = function(event) {
      var reader = new FileReader();
      reader.onload = function(){
        var output =( document.getElementById)('output');
        output.src = reader.result;
      };
      reader.readAsDataURL(event.target.files[0]);
    };
  </script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('common.layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\mbrellabd-global\resources\views/common/category/sub/index.blade.php ENDPATH**/ ?>