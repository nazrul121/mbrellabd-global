<div class="row">
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"> <span class="d-block m-t-5">Product infomation</span> </div>
            <div class="card-block">
                <div class="row form-group">
                    <div class="col-sm-12">
                        <input type="text" name="title" class="form-control" value="<?php echo e(old('title')??$product->title); ?>" placeholder="Product name/title" required>
                        <span class="text-danger"><?php echo e($errors->first('title')); ?></span>
                    </div>
                </div>
                <div class="row text-right">
                    <label class="col-sm-2 col-form-label">Design Code</label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="design_code" value="<?php echo e(old('design_code')); ?>" placeholder="design code" required>
                        <span class="text-danger"><?php echo e($errors->first('design_code')); ?></span>
                    </div>

                    <label class="col-sm-2 col-form-label">Design Year</label>
                    <div class="col-sm-3">
                       <select name="design_year" class="form-control" required>
                           <option value="">Choose year</option>
                           <?php $year = 2015; // staring year ?>
                            <?php for($i = $year; $year <= date('Y') +1; $year++): ?>
                                <option <?php if($year==$product->design_year): ?>selected <?php endif; ?> value="<?php echo e($year); ?>"><?php echo e($year); ?></option>
                            <?php endfor; ?>
                       </select>
                        <span class="text-danger"><?php echo e($errors->first('design_year')); ?></span>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-12" >
                        <label>Check from Categories [ <code>Multiple selection allowed</code> ]</label>
                        <div class="form-group categoryArea">
                            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key1=>$cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="checkbox checkbox-info checkbox-fill d-inline">
                                    <input type="checkbox" class="category" <?php if($product->cat_belongsTo_product($product->id,$cat->id)): ?>checked <?php endif; ?> name="category_ids[]" id="cat<?php echo e($cat->id); ?>" value="<?php echo e($cat->id); ?>">
                                    <label for="cat<?php echo e($cat->id); ?>" class="cr text-info"><?php echo e($cat->title); ?></label>
                                </div>
                                <?php $__currentLoopData = $cat->inner_groups()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key2=>$sub): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-sm-10 offset-1 ">
                                        <div class="checkbox mb-1 checkbox-primary checkbox-fill d-inline">
                                            <input type="checkbox" class="sub_category" data-cat="<?php echo e($cat->id); ?>" <?php if($product->sub_cat_belongsTo_product($product->id,$sub->id)): ?>checked <?php endif; ?> name="sub_category_ids[]" id="sub<?php echo e($cat->id.$sub->id); ?>" value="<?php echo e($sub->id); ?>">
                                            <label for="sub<?php echo e($cat->id.$sub->id); ?>" class="cr text-primary"><?php echo e($sub->title); ?></label>
                                        </div>
                                    </div>

                                    <?php if($sub->child_groups()->count()>0): ?>
                                        <?php $__currentLoopData = $sub->child_groups()->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key3=>$child): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <div class="col-sm-8 offset-1 "> &nbsp; &nbsp; &nbsp;
                                                <div class="checkbox mb-1 checkbox-secondary checkbox-fill d-inline">
                                                    <input type="checkbox" class="child_category" data-cat="<?php echo e($cat->id); ?>" data-sub="<?php echo e($sub->id); ?>" <?php if($product->child_cat_belongsTo_product($product->id,$child->id)): ?>checked <?php endif; ?> name="child_category_ids[]" id="child<?php echo e($cat->id.$sub->id.$child->id); ?>" value="<?php echo e($child->id); ?>">
                                                    <label for="child<?php echo e($cat->id.$sub->id.$child->id); ?>" class="cr text-secondary"><?php echo e($child->title); ?></label>
                                                </div>
                                            </div>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>

                <?php $currency = \App\Models\Country::where('is_default','1')->first();
                    if($product->net_price !=null) $netPrice = $product->net_price * $currency->value;
                    else $netPrice = null;
                    if($product->sale_price !=null) $sale_price = $product->sale_price * $currency->value;
                    else $sale_price = null;
                ?>

                <div class="row text-right mt-4">
                    <label class="col-sm-2 col-form-label">Net Price (<?php echo e($currency->symbol); ?>) </label>
                    <div class="col-sm-4">
                        <input type="text" name="net_price" class="form-control" value="<?php echo e(old('net_price')??$netPrice); ?>" required/>
                        <span class="text-danger"><?php echo e($errors->first('net_price')); ?></span>
                    </div>
                    <label class="col-sm-2 col-form-label">Retail Price (<?php echo e($currency->symbol); ?>)</label>
                    <div class="col-sm-4">
                        <input type="text" name="sale_price" class="form-control" value="<?php echo e(old('sale_price')??$sale_price); ?>" required/>
                        <span class="text-danger"><?php echo e($errors->first('sale_price')); ?></span>
                    </div>
                </div>

                <div class="row text-right mt-4">
                    <label class="col-sm-1 col-form-label"> Vat</label>
                    <div class="col-sm-3">
                        <div class="input-group mb-3">
                            <input type="text" class="form-control input-group-item" name="vat" value="<?php echo e(old('vat',$product->vat)); ?>" required >
                            <div class="input-group-append"><span class="input-group-text">%</span></div>
                        </div>
                        <span class="text-danger"><?php echo e($errors->first('vat')); ?></span>
                    </div>
                    <div class="col-sm-3 pt-3">
                        <label class="form-label">
                            <input type="radio" class="vat_type" name="vat_type" value="including" <?php if($product->vat_type=='including'): ?>checked <?php endif; ?>> <span></span>  <span> Including</span>
                        </label> &nbsp; &nbsp;
                        <label class="form-label">
                            <input type="radio" class="vat_type" name="vat_type" value="excluding" required <?php if($product->vat_type=='excluding'): ?>checked <?php endif; ?>>  <span></span><span> Excluding</span>
                        </label>
                        <span class="text-danger"><?php echo e($errors->first('vat_type')); ?></span>
                    </div>
                    <label class="col-sm-2 col-form-label">Quantity</label>
                    <div class="col-sm-3">
                        <input type="number" name="qty" class="form-control"  value="<?php echo e(old('qty')??$product->qty); ?>" required/>
                        <span class="text-danger"><?php echo e($errors->first('qty')); ?></span>
                    </div>
                </div>

                <div class="row form-group">
                    <div class="col-sm-12">
                        <label for="" class="mt-3">Product Descriptions</label>
                        <textarea class="summernote" name="description" required><?php echo e(old('description')??$product->description); ?></textarea>
                        <span class="text-danger"><?php echo e($errors->first('description')); ?></span>
                    </div>
                </div>

            </div>
        </div>
    </div>


    <div class="col-md-5">
        <div class="card">
            <div class="card-header"> <span class="d-block m-t-5">Photos and more</span> </div>
                <div class="card-block">
                    <div class="row form-group">
                        <div class="col-sm-3 mt-5">
                            <?php $width = \App\Models\Setting::where('type','product-weight')->pluck('value')->first();
                            $height = \App\Models\Setting::where('type','product-height')->pluck('value')->first();?>
                            <label class="col-form-label">Product`s Feature Photo [size: <?php echo e($width); ?>x<?php echo e($height); ?>px]</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="file" name="feature_photo" class="dropify" data-height="270" data-show-remove="false" />
                            <span class="text-danger"><?php echo e($errors->first('feature_photo')); ?></span>
                        </div>
                    </div>

                    <div class="form-group row text-right mt-3">
                        <label class="col-form-label col-sm-4 text-sm-right">Product`s photos [950x950px]</label>
                        <div class="col-sm-8">
                            <input type="file" class="form-control" name="photos[]" multiple=true/>
                            <span class="text-danger"><?php echo e($errors->first('photos')); ?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="">Product tag<span>s</span></label>
                        <input type="text" name="tags" data-role="tagsinput" value="<?php echo e(old('tags')??$product->tags); ?>" required/>
                        <span class="text-danger"><?php echo e($errors->first('tags')); ?></span>
                        <small>Separate each tag with a coma <code>(,)</code></small>
                    </div>

                    <div class="form-group">
                        <div class="checkbox checkbox-info checkbox-fill d-inline">
                            <input type="checkbox" name="cod" id="cod" <?php if($product->cod=='1'): ?>checked <?php endif; ?> value="<?php echo e(old('cod')??$product->cod); ?>" >
                            <label for="cod" class="cr">Cash On Delivery available</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="checkbox checkbox-info checkbox-fill d-inline">
                            <input type="checkbox" name="portable" id="portable" <?php if($product->portable=='1'): ?>checked <?php endif; ?> value="<?php echo e(old('portable')??$product->portable); ?>">
                            <label for="portable" class="cr">The product is portable (carriable)</label>
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="checkbox checkbox-info checkbox-fill d-inline">
                            <input type="checkbox" name="is_group" id="is_group" <?php if($product->is_group=='1'): ?>checked <?php endif; ?> value="<?php echo e(old('is_group')??$product->is_group); ?>" >
                            <label for="is_group" class="cr">This is a <b>Group product</b></label>
                        </div>
                    </div>

                    <div class="row form-group mt-4 text-right">
                        <label class="col-sm-2 col-form-label">Brand</label>
                        <div class="col-sm-3">
                            <select name="brand_id" class="form-control" required>
                                <?php $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option <?php if($product->brand_id==$brand->id): ?>selected <?php endif; ?> value="<?php echo e($brand->id); ?>"><?php echo e($brand->title); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <span class="text-danger"><?php echo e($errors->first('brand')); ?></span>
                        </div>
                        <label class="col-sm-3 col-form-label">Supplier info</label>
                        <div class="col-sm-4">
                            <select name="supplier_id" id="" class="form-control" required>
                                <?php $__currentLoopData = $suppliers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($item->id); ?>"><?php echo e($item->company_name); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <span class="text-danger"><?php echo e($errors->first('supplier_id')); ?></span>
                        </div>
                    </div>

                    <div class="row form-group mt-4 text-right">

                        <?php $sku = substr(str_shuffle(str_repeat("0123456789abcdefghijklmnopqrstuvwxyz", 15)), 0, 12); ?>

                        <label class="col-sm-2 col-form-label">SKU</label>
                        <div class="col-sm-4">
                            <input type="text" name="sku" class="form-control" value="<?php echo e(old('sku',$sku)); ?>"  required>
                            <span class="text-danger"><?php echo e($errors->first('sku')); ?></span>
                        </div>

                        <label class="col-sm-2 col-form-label">Barcode</label>
                        <div class="col-sm-4">
                            <input type="text" name="barcode" class="form-control" value="<?php echo e(old('barcode',rand(9,99999999))); ?>" required>
                            <span class="text-danger"><?php echo e($errors->first('barcode')); ?></span>
                        </div>
                    </div>

                    <div class="form-group bg-light p-2">
                        <p class="text-info">Country for--</p>
                        <?php $__currentLoopData = get_currency(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <?php
                                $check = \DB::table('country_product')->where(['product_id'=>$product->id, 'country_id'=>$item->id]);
                                if($check->count()>0){
                                    $isChecked = 'checked';
                                }else $isChecked = '';
                            ?>
                            <label class="form-label">
                                <input type="checkbox" class="position-relative lang" style="top:3px;" name="langs[]" value="<?php echo e($item->id); ?>" <?php echo e($isChecked); ?>> <span></span>
                                <span> <img class="flag" style="height:13px;" src="<?php echo e(url($item->flag)); ?>"> <?php echo e($item->short_name); ?></span>
                            </label> &nbsp; &nbsp; 
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <span class="text-danger"><?php echo e($errors->first('langs')); ?></span>
                    </div>


                    <div class="form-group bg-light p-2">
                        <p class="text-info">Product weight--</p>
                        <div class="row">
                            <label class="col-sm-3 hsCode text-md-right col-form-label"> HS code</label>
                            <div class="col-sm-9">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control input-group-item" name="hs_code" value="<?php echo e(old('hs_code',$product->hs_code)); ?>" >
                                </div>
                                <span class="text-danger"><?php echo e($errors->first('hs_code')); ?></span>
                            </div>
                            <div class="col-sm-6">
                                <label class="col-form-label">Gross weight </label>
                                <input type="text" name="gross_weight" class="form-control" value="<?php echo e(old('gross_weight')??$product->gross_weight); ?>" placeholder="In kg"/>
                                <span class="text-danger"><?php echo e($errors->first('gross_weight')); ?></span>
                            </div>
                           
                            <div class="col-sm-6">
                                <label class="col-form-label">Volumatric weight</label>
                                <input type="text" name="vol_weight" class="form-control" value="<?php echo e(old('vol_weight')??$product->vol_weight); ?>" placeholder="In kg"/>
                                <span class="text-danger"><?php echo e($errors->first('vol_weight')); ?></span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-4" style="font-size: 18px;background: #f1f1f1;padding: 10px;border-radius: 13px;">
                        <label class="form-label">
                            <input type="radio" class="status" name="status" value="1" <?php if($product->status=='1' || Request::segment(4)=="create"): ?>checked <?php endif; ?>> <span></span>
                            <span> Published</span>
                        </label> &nbsp; &nbsp;
                        <label class="form-label">
                            <input type="radio" class="status" name="status" value="0" <?php if($product->status=='0'): ?>checked <?php endif; ?> >
                            <span></span><span> Publish later</span>
                        </label>
                        <span class="text-danger"><?php echo e($errors->first('status')); ?></span>
                    </div>

                    <div class="form-group pt-2 bg-light" style="width:100%">
                        <div class="checkbox checkbox-info checkbox-fill d-inline " >
                            <input type="checkbox" name="size_chirt" id="size_chirt">
                            <label for="size_chirt" class="cr">CHoose <b>Size-chirt</b> [Optional]</label>
                        </div>
                    </div>


                    <div class="row">
                        <div class="col-12">
                            <button type="button" class="btn btn-rounded btn-secondary float-right add_button btn-sm"><span class="feather icon-plus"></span> Additional info</button>
                        </div>
                    </div>
                    <div class="additionaArea">
                        <?php if($product->additional_field): ?>
                            <?php $__currentLoopData = explode(',',$product->additional_field); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key=>$field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="row mb-3">
                                <div class="col-sm-5">
                                    <label for="">Additional record</label>
                                    <input type="text" class="form-control" name="fields[]" value="<?php echo e($field); ?>">
                                </div>
                                <div class="col-sm-6">
                                    <label for="">Record Value</label>
                                    <input type="text" class="form-control" name="field_values[]" value="<?php echo e(explode(',',$product->additional_value)[$key]); ?>">
                                </div>
                                <a href="javascript:void(0);" class="removeBtn" style="margin-top:30px;color:red;font-size:25px;">x</a>
                            </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </div>

                    <div class="form-group mb-5 mt-5" >
                        <button type="submit" class="btn btn-rounded btn-info float-right"><span class="feather icon-copy"></span> Copy Product data</button>
                    </div>

                </div>

            </div>
        </div>
    </div>

</div>

<div class="modal fade" id="chirtModal" tabindex="-1" aria-hidden="true" style="display: none;">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Choose a Size chirt</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body m-3" >
                <div class="row show_chirts">
                    <?php $__currentLoopData = \App\Models\Size_chirt::all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $chirt): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-md-6">
                            <img class="sizeChirtShow" style="<?php if($product->size_chirt_id==$chirt->id): ?>border:3px solid green <?php endif; ?>" data-id="<?php echo e($chirt->id); ?>" src="<?php echo e(url('storage/'.$chirt->photo)); ?>">
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <input type="hidden" name="size_chirt_id" value="<?php echo e($product->size_chirt_id); ?>">
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>


<?php $__env->startPush('scripts'); ?>
<style>
    .sizeChirtShow{
        cursor: pointer; max-width:100%; border:3px solid silver;margin: 1%;
    }
    .sizeChirtShow:hover{
        transform: scale(1.1);
    }
    .sizeChirtShow:active{
        border:3px solid green;
    }
    .categoryArea{
        background: rgb(255, 255, 255);border: 2px solid rgb(240, 243, 246);overflow: hidden;
        resize: both;height:250px;min-width: 120px;min-height: 90px;max-width: 100%;max-height: 500px;width: 886px;padding: 1em;
        overflow-y: scroll;
    }
</style>
<script type="text/javascript" src="https://jeremyfagis.github.io/dropify/dist/js/dropify.min.js"></script>
<link rel="stylesheet" type="text/css" href="https://jeremyfagis.github.io/dropify/dist/css/dropify.min.css">

<script src="<?php echo e(asset('back2')); ?>/plugins/bootstrap-tagsinput-latest/js/bootstrap-tagsinput.min.js"></script>
<script src="<?php echo e(asset('back2')); ?>/plugins/bootstrap-maxlength/js/bootstrap-maxlength.min.js"></script>
<script src="<?php echo e(asset('back2')); ?>/js/pages/form-advance-custom.js"></script>
<!-- summernote css/js -->
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>

<script>
    $('.dropify').dropify();
    $('.summernote').summernote({ height: 300 });
    $(function(){

        $('.sub_category').on('click',function(){
            let group = $(this).data('cat');
            $('#cat'+group).attr('checked',true);
            $('#cat'+group).val(group);
        });

        $('.child_category').on('click',function(){
            let group = $(this).data('cat');
            let sub = $(this).data('sub');
            $('#cat'+group).attr('checked',true);
            $('#cat'+group).val(group);

            $('#sub'+group+sub).attr('checked',true);
            $('#sub'+group).val(sub);
        });


        //add one + remove row
        var maxField = 10; //Input fields increment limitation
        var addButton = $('.add_button'); //Add button selector
        var wrapper = $('.additionaArea'); //Input field wrapper
        var fieldHTML = '<div class="row mb-3"><div class="col-sm-5"> <label for="">Additional record</label> <input type="text" class="form-control" name="fields[]"> </div> <div class="col-sm-6">  <label for="">Record Value</label>  <input type="text" class="form-control" name="field_values[]"></div><a href="javascript:void(0);" class="remove_button" style="margin-top:30px;color:red;font-size:25px;">x</a></div>'; //New input field html
        var x = 1;
        //Once add button is clicked
        $(addButton).click(function(){
            if(x < maxField){  x++;  $(wrapper).append(fieldHTML); }
        });
        //Once remove button is clicked
        $(wrapper).on('click', '.remove_button', function(e){
            e.preventDefault(); $(this).parent('div').remove();
        });

        $(document).on('keypress',function(e) {
            if(e.which == 13) {  e.preventDefault(); }
        });



        // checkbox data-set on change
        $('[name=cod]').on('change',function(){
            if($(this).prop("checked") == true){ $('[name=cod]').val('1')}
            else if($(this).prop("checked") == false){  $('[name=cod]').val('0')}
        });
        $('[name=portable]').on('change',function(){
            if($(this).prop("checked") == true){ $('[name=portable]').val('1')}
            else if($(this).prop("checked") == false){  $('[name=portable]').val('0')}
        });
        $('[name=is_group]').on('change',function(){
            if($(this).prop("checked") == true){ $('[name=is_group]').val('1')}
            else if($(this).prop("checked") == false){  $('[name=is_group]').val('0')}
        });

        $(document).on('click', '.removeBtn', function(e){
            e.preventDefault(); $(this).parent('div').remove();
        });

        $('#size_chirt').on('click',function(){
            $('#chirtModal').modal('show');
        })

        $('.sizeChirtShow').on('click',function(){
            let id = $(this).data('id');
            $('[name=size_chirt_id]').val(id);
            $('.sizeChirtShow').css('border','3px solid silver')
            $(this).css('border','3px solid green');
            $('#chirtModal').modal('hide');
        });

        setTimeout(() => {
            $('.bootstrap-tagsinput input').css('width','100%');
        }, 1000);

        $('input[name="langs[]"]').change(function() {
            // Get all checked checkboxes and collect their values into an array
            var selectedValues = $('input[name="langs[]"]:checked').map(function() {
                return this.value;
            }).get();

            // Check if the array contains the value "2"
            if (selectedValues.includes("2")) {
                $('[name=hs_code]').prop('required',false)
            } else {
                $('[name=hs_code]').prop('required',true)
            }
        });


    })
</script>


<?php $__env->stopPush(); ?>


<?php /**PATH D:\xampp-php-8.2\htdocs\laravelapp\resources\views/common/product/copy/form.blade.php ENDPATH**/ ?>