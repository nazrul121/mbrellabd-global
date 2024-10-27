<div class="form-group">
    <label for="recipient-name" class="col-form-label">Social Media Name</label>
    <input type="text" class="form-control" name="media_name">
</div>

<div class="form-group">
    <div class="row">
        <div class="col-md-12">
            <label for="">Media icon (optional)</label>
            <button type="button" data-toggle="collapse" data-target="#iconS" aria-expanded="false" aria-controls="iconS" class="btn btn-outline-secondary form-control chooseIcon">
                <span class="showIcon"></span>
                Choose icon</button>
            <input type="hidden" name="media_icon">
            <div class="card">
                <div class="collapse" id="iconS">
                    <div class="card-body row" style="height: 300px; overflow-y:scroll">
                        @include('common.setting.social.icons')
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
<div class="form-group">
    <label for="recipient-name" class="col-form-label">Social Media LInk</label>
    <input type="text" class="form-control" name="media_link">
</div>


<div class="form-group">
    <label class="form-label">
        <input type="radio" class="status" name="status" value="1"> <span></span>
        <span>Published</span>
    </label>
    <label class="form-label">
        <input type="radio" class="status" name="status" value="0">
        <span></span><span>Unpublished</span>
    </label>
</div>

@push('styles')
<style>
    #icon-wrapper:-webkit-full-screen,
    #icon-wrapper:-moz-full-screen,
    #icon-wrapper:-ms-fullscreen,
    #icon-wrapper:fullscreen {
        width: 80vh;
        height: 80vh;
    }
</style>
@endpush

@push('scripts')

<script>

    $('#toggle_fullscreen').on('click', function(){
        // if already full screen; exit
        // else go fullscreen
        if (
            document.fullscreenElement ||
            document.webkitFullscreenElement ||
            document.mozFullScreenElement ||
            document.msFullscreenElement
        ) {
            if (document.exitFullscreen) {
            document.exitFullscreen();
            } else if (document.mozCancelFullScreen) {
            document.mozCancelFullScreen();
            } else if (document.webkitExitFullscreen) {
            document.webkitExitFullscreen();
            } else if (document.msExitFullscreen) {
            document.msExitFullscreen();
            }
        } else {
            element = $('#icon-wrapper').get(0);
            if (element.requestFullscreen) {
            element.requestFullscreen();
            } else if (element.mozRequestFullScreen) {
            element.mozRequestFullScreen();
            } else if (element.webkitRequestFullscreen) {
            element.webkitRequestFullscreen(Element.ALLOW_KEYBOARD_INPUT);
            } else if (element.msRequestFullscreen) {
            element.msRequestFullscreen();
            }
            $('#icon-wrapper').css('overflow-y','scroll');
        }
    });

    $('.i-block').on('click',function(){
        $('.showIcon').removeClass();
        $('.chooseIcon span').addClass('showIcon')
        let icon = $(this).data('clipboard-text');
        $('[name=media_icon]').val(icon);
        $('.showIcon').addClass(icon)
        $('.collapse').removeClass('show')
        document.exitFullscreen();
        $('#icon-wrapper').css('overflow-y','none');
    })
</script>


@endpush
