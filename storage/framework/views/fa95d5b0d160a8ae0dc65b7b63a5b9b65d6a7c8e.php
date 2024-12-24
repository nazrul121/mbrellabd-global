<?php
    $phones = \App\Models\Contact::select('phone')->distinct('phone')->get();
?>
<section class="header-user-list">
    <div class="h-list-body">
        <a href="#!" class="h-close-text"><i class="feather icon-chevrons-right"></i></a>
        <div class="main-friend-cont scroll-div">
            <div class="main-friend-list">
                <?php $__currentLoopData = $phones; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ph): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php $contact = \App\Models\Contact::where('phone',$ph->phone);?>
                <div class="media userlist-box" data-phone="<?php echo e($ph->phone); ?>" data-username="<?php echo e($contact->first()->name.' - '.$ph->phone); ?>">
                    <a class="media-left" href="#!">
                        <img class="media-object img-radius" src="/storage/images/user.jpg" >
                        <div class="live-status"><?php echo e($contact->count()); ?></div>
                     </a>
                    <div class="media-body">
                        <h6 class="chat-header"><?php echo e($contact->first()->name); ?> <br>
                        <small class="d-block"><?php echo e($contact->first()->subject); ?> </small></h6>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
 
</section>


<section class="header-chat">
    <div class="h-list-header">
        <h6 class="username">Sender Name</h6>
        <a href="#!" class="h-back-user-list"><i class="feather icon-chevron-left"></i></a>
    </div>
    <div class="h-list-body">
        <div class="main-chat-cont scroll-div">
            <div class="main-friend-chat">
                <div class="media chat-messages">
                    <div class="media-body chat-menu-content">
                        <div class="">
                            <p class="chat-cont">Message</p>
                        </div>
                        <p class="chat-time">Time line</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php $__env->startPush('scripts'); ?>
<script>
    $(".userlist-box").click(function(){
        let phone = $(this).data('phone');
        let username = $(this).data('username');
        $('.username').text(username);
        $.get("/common/single-contact/"+phone, function(data, status){
            $('.main-friend-chat').html(data);
        });
    });
</script>
<?php $__env->stopPush(); ?>
<?php /**PATH C:\laragon\www\mbrellabd-global\resources\views/common/includes/short-message.blade.php ENDPATH**/ ?>