@extends('layouts.error')

@section('title','429 - Too many request')

@section('content')

<div class="error-page">
    <div class="container">
        <div class="error-content text-center pb-5">
            <h1 class="error-code">wait <small class="timer"></small></h1>
            <div class="error-img mx-auto">
                <img style="max-width:100%" src="/assets/img/error/429.png" alt="page not found">
            </div>

            <h2 class="error-message">Please wait for a while of <b class="timer">....</b> <small>sec.</small> </h2>
        </div>
    </div>
</div>
@endsection

@push('script')
<script>
    $(document).ready(function() {
        let retryAfter = {{ $retryAfter ?? 60 }};  // Get the initial time (in seconds)

        // Show the countdown in mm:ss format
        function formatTime(seconds) {
            let minutes = Math.floor(seconds / 60);  // Get minutes
            let remainingSeconds = seconds % 60;    // Get remaining seconds
            return `${minutes}:${remainingSeconds < 10 ? '0' : ''}${remainingSeconds}`;
        }

        // Display the initial time
        $('.timer').text(formatTime(retryAfter));

        // Countdown logic
        let countdownInterval = setInterval(function() {
            retryAfter--;
            $('.timer').text(formatTime(retryAfter));  // Update the displayed time

            if (retryAfter <= 0) {
                clearInterval(countdownInterval);
                $('.timer').text('Loading...');  // Show loading text when countdown ends
                setTimeout(function() {
                    location.reload();  // Reload the page after 2 seconds
                }, 2000);
            }
        }, 1000);
    });

</script>
@endpush

