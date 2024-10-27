<div class="form-group">
    <label for="recipient-name" class="col-form-label">Gateway Name</label>
    <input type="text" class="form-control" name="name">
</div>

<div class="form-group">
    <label for="recipient-name" class="col-form-label">Name origin</label>
    <input type="text" class="form-control" name="name_origin">
</div>


<div class="form-group">
    <div class="row">
        <div class="col-md-12">
            <label for="">Gateway icon (optional)</label>
            <button type="button" data-toggle="collapse" data-target="#iconS" aria-expanded="false" aria-controls="iconS" class="btn btn-outline-secondary form-control chooseIcon">
                <span class="showIcon"></span>
                Choose icon</button>
            <input type="hidden" name="icon">
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
    <label for="message-text" class="col-form-label">Description - <small>Optional</small></label>
    <textarea class="form-control" name="description" rows="5"></textarea>
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
        $('[name=icon]').val(icon);
        $('.showIcon').addClass(icon)
        $('.collapse').removeClass('show')
        document.exitFullscreen();
        $('#icon-wrapper').css('overflow-y','none');
    })
</script>


@endpush
