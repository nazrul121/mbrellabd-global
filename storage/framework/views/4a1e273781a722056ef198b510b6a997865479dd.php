<?php if($promotion->status=='1'): ?>
    <?php 
        $promotionCountry = $promotion->countries()->select('country_id')->distinct()->pluck('country_id')->toArray();
        $catIds = \App\Models\Country_group::whereIn('country_id',$promotionCountry)->select('group_id')->distinct()->pluck('group_id')->toArray();
        $categories =  \App\Models\Group::whereIn('id',$catIds)->orderBy('title')->get();
    ?>
    <form class="mb-5" id="addSummary" action="<?php echo e(route('common.promotion.save-form',$promotion->id)); ?>" method="post"> <?php echo csrf_field(); ?>
        <div class="col-md-10 offset-1 add_summary"></div>
        <div class="row text-right ">
            <label for="" class="col-md-2 col-form-label">Product Group</label>
            <div class="col-sm-2">
                <select class="form-control" name="category_id" required>
                    <option value="">Choose Group</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($type->id); ?>"><?php echo e($type->title); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <label for="" class="col-form-label col-sm-1">Sub Group</label>
            <div class="col-sm-2">
                <select class="form-control" name="sub_category_id"> </select>
            </div>

            <label for="" class="col-form-label col-sm-1">Child Group</label>
            <div class="col-sm-2">
                <select class="form-control" name="child_category_id"> </select>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-sm-3 offset-2 text-right mt-2">
                <label class="form-label">
                    <input type="radio" class="discount_in" name="discount_in" value="percent" checked> <span></span>
                    <span>Discount in Percent</span>
                </label> &nbsp; &nbsp;
                <label class="form-label">
                    <input type="radio" class="discount_in" name="discount_in" value="amount">
                    <span></span><span>Discount in Fix amount</span>
                </label>
            </div>

            <label for="" class="col-sm-1 text-right col-form-label">Amount</label>

            <div class="col-sm-3">
                <div class="input-group">
                    <input type="number" class="form-control" name="amount" placeholder="Discount percentage" required>
                    <div class="input-group-append">
                    <span class="input-group-text percentSymbol">%</span>
                    </div>
                </div>
            </div>

            <div class="col-md-2 text-right"><button type="submit" class="btn btn-info saveDiscount"> Save Discount</button> </div>
        </div>

        <div class="cold-md-9 offset-md-1 showProducts"></div>
    </form>
<?php endif; ?>


<style>
    .dataTables_length{display:none}
</style>
<script>
    $(function(){

        $("#addSummary").submit(function(event) {
            event.preventDefault();
            $("[type='submit']").html(' Working...');
            $('.add_result').html('');
            $("[type='submit']").prop('disabled',true);

            var form = $(this);
            $.ajax({
            type: form.attr('method'),
            url: form.attr('action'),
            data: form.serialize()
            }).done(function(data) {

                $(".saveDiscount").attr('disabled',false);
                $(".saveDiscount").text('Save Discount');

                if(data.warning){
                    $('.add_summary').html('<div class="alert alert-danger alert-dismissible fade show" role="alert"><strong>Warning!</strong> '+data.warning+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>');
                }
                if(data.success){
                    $('.add_summary').html('<div class="alert alert-success alert-dismissible fade show" role="alert">'+data.success+'<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button></div>');
                }
            }).fail(function(data) {
                alert('Execution failed. Please try again later!!');
            });
        });


        $('[name=discount_in]').on('change',function(){
            let type = $(this).val();
            if(type=='percent'){
                $('[name=amount]').attr("placeholder", "Discount percentage");
                $('.percentSymbol').text('%');
            }
            else {
                $('[name=amount]').attr("placeholder", "Discount Amount");
                $('.percentSymbol').text('Tk');
            }

        });
        //get sub-categories
        $('[name=category_id]').on('change',function(){
            $("[name=sub_category_id]").html('<option value="">Choose sub-groups</option>');
            if($(this).val().length >0){
                sub_categories( $(this).val() );
                get_category_products( $(this).val() );
            }else{
                $("[name=sub_category_id]").html('');
                $('.showProducts').html('');
            }
        });

        // get child-category
        $('[name=sub_category_id]').on('change',function(){
            $("[name=child_category_id]").html('<option value="">Choose Child-groups</option>');
            if($(this).val().length >0){
                get_inner_category_products( $(this).val() );
                child_categories($(this).val());
            }else $("[name=child_category_id]").html('');
        });

        $('[name=child_category_id]').on('change', function () {
            console.log("child_category_id changed:", $(this).val()); // Debugging

            if ($(this).val().length > 0) {
                get_child_category_products($(this).val());
            }
        });
    })



    function sub_categories(cat_id){
        if(cat_id=='all') return false;
        $.ajax({ url:"/common/main2sub-categories-promotion/<?php echo e($promotion->id); ?>/"+ cat_id, method:"get",
            success:function(data){
                $.each(data, function(index, value){
                    $("[name=sub_category_id]").append('<option value="'+value.id+'">'+value.title+'</option>');
                });
            }
        });
    }

    function child_categories(sub_cat){
        $.ajax({ url:"/common/sub2child-categories-promotion/<?php echo e($promotion->id); ?>/"+ sub_cat, method:"get",
            success:function(data){
                $.each(data, function(index, value){
                    $("[name=child_category_id]").append('<option value="'+value.id+'">'+value.title+'</option>');
                });
            }
        });
    }


    function get_category_products(cat_id){
        $('.showProducts').html('<h4 class="text-warning p-3">Working...</h4>');
        $('[type=submit]').attr('disabled',true);
        $.ajax({ url:url + "/common/get-group-products/"+ cat_id+'/<?php echo e($promotion->id); ?>', method:"get",
            success:function(data){
                $('.showProducts').html(data);
                $('[type=submit]').attr('disabled',false);
            }
        });
    }

    function get_inner_category_products(cat_id) {
        $('.showProducts').html('<h4 class="text-warning p-3">Working...</h4>');
        $('[type=submit]').attr('disabled', true);

        let group_id = $('[name=category_id]').val();

        $.ajax({
            url: `https://mbrellabd.com/common/get-inner-group-products/${cat_id}/<?php echo e($promotion->id); ?>`,
            method: "GET",
            data: { group_id: group_id },
            success: function (data) {
                $('.showProducts').html(data);
                $('[type=submit]').attr('disabled', false);
            },
            error: function (xhr, status, error) {
                console.error("Status:", status);
                console.error("Error:", error);
                console.error("Response:", xhr.responseText);

                $('.showProducts').html('<h4 class="text-danger p-3">An error occurred. Please try again.</h4>');
                $('[type=submit]').attr('disabled', false);
            }
        });
    }

    
    function get_child_category_products(cat_id) {
        console.log("Calling get_child_category_products with ID:", cat_id); // Debugging

        $('.showProducts').html('<h4 class="text-warning p-3">Working...</h4>');
        $('[type=submit]').attr('disabled', true);

        let group_id = $('[name=category_id]').val();
        let innerGroup_id = $('[name=sub_category_id]').val();

        console.log("Group ID:", group_id); // Debugging
        console.log("Inner Group ID:", innerGroup_id); // Debugging

        $.ajax({
            url: `https://mbrellabd.com/common/get-child-group-products/${cat_id}/<?php echo e($promotion->id); ?>/unchecked/`,
            method: "GET",
            data: { group_id: group_id, inner_group_id: innerGroup_id },
            success: function (data) {
                console.log("Response:", data); // Debugging
                $('.showProducts').html(data);
                $('[type=submit]').attr('disabled', false);
            },
        });
    }
</script>
<?php /**PATH /var/www/laravelapp/resources/views/common/ad/promotion/flat/form.blade.php ENDPATH**/ ?>