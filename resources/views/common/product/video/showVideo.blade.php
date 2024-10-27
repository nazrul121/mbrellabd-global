
@if ($product_video->type=='video')
    <video width="100%" controls>
        <source src="/storage/{{ $product_video->video_link }}" type="video/mp4">
        <source src="/storage/{{ $product_video->video_link }}" type="video/ogg">
        Your browser does not support HTML video.
    </video>
@else
<iframe width="100%" height="315" src="{{ $product_video->video_link }}" allow="autoplay; encrypted-media" allowfullscreen></iframe>
@endif

