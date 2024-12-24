<div class="newsletter-section mt-5 mb-3 overflow-hidden">
    <div class="newsletter-inner">
        <div class="container p-md-5 bg-brand pb-3">
            <div class="newsletter-container">
                <div class="row align-items-center">
                    <div class="col-lg-8 col-12">
                        <div class="newsletter-content newsletter-heading ">
                            <div class="newsletter-header">
                                <p class="newsletterTitle">Get Expert Tips In Your Inbox</p>
                                <h6 class="newsletter-subheading">Subscribe to our newsletter and stay updated.</h6>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4 col-12">
                        <form action="<?php echo e(route('subscribe')); ?>" class="newsletter-form d-flex align-items-center rounded"><?php echo csrf_field(); ?>
                            <input class="newsletter-input bg-transparent border-0" type="email" value="<?php echo e(session()->get('subcriber')); ?>" <?php if(session()->has('subcriber')): ?>readonly <?php endif; ?> name="email" placeholder="Enter your e-mail" autocomplete="off" required>
                            <button class="newsletter-btn rounded" type="submit" <?php if(session()->has('subcriber')): ?>disabled <?php endif; ?>>
                                <svg width="17" height="14" viewBox="0 0 17 14" fill="#fff" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M9.11539 -0.000488604L7.50417 1.99951L11.5769 5.59951L0.500001 5.59951L0.500001 8.19951L11.7049 8.19951L7.50417 11.4995L8.70513 13.9995L16.5 7.19951L9.11539 -0.000488604Z" fill="#FEFEFE"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH C:\laragon\www\mbrellabd-global\resources\views/includes/subscribe.blade.php ENDPATH**/ ?>