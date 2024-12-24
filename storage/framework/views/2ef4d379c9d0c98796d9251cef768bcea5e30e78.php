<?php if($video !=null): ?> <br>

<div class="example-marquee">
    <div id="ytbg" data-ytbg-fade-in="true" data-youtube="<?php echo e($video->video_link); ?>"></div>
    <div class="content">
        <div class="inner">
            <div class="overlay-furniture section-spacing">
                <div class="container video-container">
                    <div class="row">
                        <div class="col-12">
                            <div class="video-tools d-flex align-items-center justify-content-center pb-3 pt-3">
                                <?php if($video->type=='video'): ?>
                                    <video controls autoplay muted loop>
                                        <source src="<?php echo e(url('storage').'/'.$video->video_link); ?>" type="video/mp4">
                                        <source src="<?php echo e(url('storage').'/'.$video->video_link); ?>" type="video/ogg">
                                        Your browser does not support HTML5 video.
                                    </video>
                                <?php endif; ?>
                                <?php if($video->type=='link'): ?>
                                    <iframe class="homeVideo" style="width:100%;" src="<?php echo e($video->video_link); ?>"></iframe>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>

<?php endif; ?> 

<style>
    video::-webkit-media-controls-play-button {}
    video::-webkit-media-controls-timeline { display:none;}
    video::-webkit-media-controls-current-time-display{ display:none;}
    video::-webkit-media-controls-time-remaining-display {display:none;}
    video::-webkit-media-controls-mute-button {}
    video::-webkit-media-controls-volume-slider {}
    video::-webkit-media-controls-toggle-closed-captions-button {display: none;}
    video::-webkit-media-controls-fullscreen-button {display: none;}
    video::-internal-media-controls-download-button {}
    video::-webkit-media-controls-enclosure {overflow:hidden;}
</style>


<script>
    (function($) {
        $(document).ready(function() {
            if($('.video-tools video').length){
                var loadVideo;

                $('video-tools. video').each(function(){
                $(this).attr('webkit-playsinline', '');
                $(this).attr('playsinline', '');
                $(this).attr('muted', 'muted');

                $(this).attr('id','loadvideo');
                    loadVideo = document.getElementById('loadvideo');
                    loadVideo.load();
                });

                $(window).scroll(function () { // video to play when is on viewport
                    $('.video-tools video').each(function(){
                        if (isScrolledIntoView(this) == true) { $(this)[0].play();}
                        else { $(this)[0].pause(); }
                    });
                });  // video to play when is on viewport

            } // end .field--name-field-video
        });

    })(jQuery);
</script>
<?php /**PATH C:\laragon\www\mbrellabd-global\resources\views/includes/video.blade.php ENDPATH**/ ?>