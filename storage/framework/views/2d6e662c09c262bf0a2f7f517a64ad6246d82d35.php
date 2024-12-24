<?php $__env->startSection('content'); ?>
<?php
    $description =  \App\Models\General_info::where('field','system_description')->pluck('value')->first();
    $system_domain =  \App\Models\General_info::where('field','system_domain')->pluck('value')->first();

    $system_phone =  \App\Models\General_info::where('field','system_phone')->pluck('value')->first();
    $invoiceLogo =  \App\Models\General_info::where('field','invoice_logo')->pluck('value')->first();
    $watermark_logo =  \App\Models\General_info::where('field','watermark_logo')->pluck('value')->first();
    $system_description =  \App\Models\General_info::where('field','system_description')->pluck('value')->first();

    $binNo = DB::table('general_infos')->where('field','bin')->pluck('value')->first();
    $mushak = DB::table('general_infos')->where('field','mushak')->pluck('value')->first();
?>
<form class="row" method="post" enctype="multipart/form-data"> <?php echo csrf_field(); ?>
    <?php if(Session::has('message')): ?>
    <div class="col-md-12">
        <div class="card">
            <div class="card-header"> <h5>Alert message</h5></div>
            <div class="card-body">
                <div class="alert <?php if(Session::has('success')): ?> alert-success <?php else: ?> alert-danger <?php endif; ?> alert-dismissible fade show" role="alert">
                   <h5> <strong> <?php if(Session::has('success')): ?>Success :  <?php echo e(Session::get('success')); ?>

                    <?php else: ?> Warning: <?php echo e(Session::get('alert')); ?>  <?php endif; ?>
                 </strong> </h5>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span></button>
                </div>
            </div>
        </div>
    </div>  <?php endif; ?>

    <div class="col-md-6">
        <div class="card">
            <div class="card-header"> <h5>System Infomation</h5> </div>
            <div class="card-block">
                <div class="text-right">

                    <div class="row form-group">
                        <label class="col-form-label col-sm-3">System title</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="system_title" value="<?php echo e(old('system_title')??request()->get('system_title')); ?>">
                            <span class="text-danger"><?php echo e($errors->first('system_title')); ?></span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-form-label col-sm-3">Brand slogan</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="system_slogan" value="<?php echo e(old('system_slogan')??request()->get('system_slogan')); ?>">
                            <span class="text-danger"><?php echo e($errors->first('system_slogan')); ?></span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-form-label col-sm-3">Brand Domain</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="system_domain" value="<?php echo e(old('system_domain')??$system_domain); ?>">
                            <span class="text-danger"><?php echo e($errors->first('system_domain')); ?></span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-form-label col-sm-3">System email</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="system_email" value="<?php echo e(old('system_email')??request()->get('system_email')); ?>">
                            <span class="text-danger"><?php echo e($errors->first('system_email')); ?></span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-form-label col-sm-3">System Helpline</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="system_helpline" value="<?php echo e(old('system_helpline')??request()->get('system_helpline')); ?>">
                            <span class="text-danger"><?php echo e($errors->first('system_helpline')); ?></span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-form-label col-sm-3">System_phone</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="system_phone" value="<?php echo e(old('system_phone')??$system_phone); ?>">
                            <span class="text-danger"><?php echo e($errors->first('system_phone')); ?></span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-form-label col-sm-3">System Fax</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="system_fax" value="<?php echo e(old('system_fax')??request()->get('system_fax')); ?>">
                            <span class="text-danger"><?php echo e($errors->first('system_fax')); ?></span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-form-label col-sm-3">Office address</label>
                        <div class="col-sm-9">
                            <textarea rows="2" class="form-control" name="office_address"><?php echo e(old('office_address')??request()->get('office_address')); ?></textarea>
                            <span class="text-danger"><?php echo e($errors->first('office_address')); ?></span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <label class="col-form-label col-sm-3">System short description</label>
                        <div class="col-sm-9">
                            <textarea rows="3" class="form-control" name="system_description"><?php echo e(old('system_description')??$system_description); ?></textarea>
                            <span class="text-danger"><?php echo e($errors->first('system_description')); ?></span>
                        </div>
                    </div>

                    
                    <div class="row form-group">
                        <div class="col-12">
                            <p class="text-left "><b for="">For invoice--</b></p>
                        </div>
                        <label class="col-form-label col-sm-3">BIN number</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="bin" value="<?php echo e(old('bin')??$binNo); ?>">
                            <span class="text-danger"><?php echo e($errors->first('bin')); ?></span>
                        </div>
                    </div>

                    <div class="row form-group">
                        <label class="col-form-label col-sm-3">Mushak number</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" name="mushak" value="<?php echo e(old('mushak')??$mushak); ?>">
                            <span class="text-danger"><?php echo e($errors->first('mushak')); ?></span>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">  <h5>System logos & more</h5> </div>
            <div class="card-block">
                <div>

                    <div class="row form-group">
                        <div class="col-sm-6">
                            <label class="col-form-label">Header logo resize</label>
                            <input type="file" data-height="120" class="form-control dropify" data-default-file="<?php echo e(url('storage/'.request()->get('header_logo'))); ?>" name="header_logo" >
                            <span class="text-danger"><?php echo e($errors->first('header_logo')); ?></span>
                        </div>
                        <div class="col-sm-6">
                            <label class="col-form-label">Footer logo [210x66px]</label>
                            <input type="file" data-height="120" class="form-control dropify" data-default-file="<?php echo e(url('storage/'.request()->get('footer_logo'))); ?>" name="footer_logo" >
                            <span class="text-danger"><?php echo e($errors->first('footer_logo')); ?></span>
                        </div>
                    </div>

                    <div class="row form-group">
                        <div class="col-sm-6 ">
                            <label class="col-form-label">Invoice logo [210x66px]</label>
                            <input type="file" data-height="120" class="form-control dropify" data-default-file="<?php echo e(url('storage/'.$invoiceLogo)); ?>" name="invoice_logo" >
                            <span class="text-danger"><?php echo e($errors->first('invoice_logo')); ?></span>
                        </div>
                        <div class="col-sm-6">
                            <label class="col-form-label">Watermark logo [524x216px]</label>
                            <input type="file" data-height="120" class="form-control dropify" data-default-file="<?php echo e(url('storage/'.$watermark_logo)); ?>" name="watermark_logo" >
                            <span class="text-danger"><?php echo e($errors->first('watermark_logo')); ?></span>
                        </div>
                    </div>

                    <div class="row form-group text-right">
                        <div class="col-sm-3 mt-5">
                            <label class="col-form-label">Favicon [25x25px]</label>
                        </div>
                        <div class="col-sm-9">
                            <input type="file" data-height="100" class="form-control dropify" data-default-file="<?php echo e(url('storage/'.request()->get('favicon'))); ?>" name="favicon" >
                            <span class="text-danger"><?php echo e($errors->first('favicon')); ?></span>
                        </div>
                    </div>
                    <div class="row form-group">
                        <div class="col-sm-12">
                            <button class="btn btn-info float-right mr-0 mt-3 btn-lg"> <span class="feather icon-edit"></span> Update information</button>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

</form>
<?php $__env->stopSection(); ?>


<?php $__env->startPush('scripts'); ?>
<link rel="stylesheet" href="<?php echo e(asset('back2')); ?>/css/dropify.min.css">
<script src="<?php echo e(asset('back2')); ?>/js/dropify.min.js"></script>
<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
    $('.dropify').dropify();
    $(document).ready(function () {

    });

</script>

<?php $__env->stopPush(); ?>

<?php echo $__env->make('common.layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\mbrellabd-global\resources\views/common/setting/index.blade.php ENDPATH**/ ?>