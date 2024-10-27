@extends('layouts.app')

@section('content')

<div class="breadcrumb">
    <div class="container">
        <ul class="list-unstyled d-flex align-items-center m-0">
            <li><a href="/">Home</a></li>
            <li>
                <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g opacity="0.4">
                        <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z"fill="#000" />
                    </g>
                </svg>
            </li>
            <li>Forgot password</li>
        </ul>
    </div>
</div>
<div class="login-page mt-100" id="formArea">
    <div class="container">
        @if(request()->get('r')=='no-access')
            <div class="alert alert-dismissible fade show" role="alert">
                <span class="required"> <b>Warning:</b>  You have expired logoin duration</span>
            </div>
        @endif

        
        <form class="login-form common-form mx-auto mb-5 border border-primary" id="resetForm" action="" method="get">@csrf
            <div class="section-header mb-3">
                <h2 class="section-heading text-center">Reset Password</h2>
            </div>
            <div class="row border @if($errors->has('email')) border-danger @endif @if(session('status')) border-info @endif  pb-4">
                @if(session('status'))
                    <div class="alert alert-info">
                        {{ session('status') }}
                    </div>
                @elseif($errors->has('email'))
                    <div class="alert alert-danger">
                        {{ $errors->first('email') }}
                    </div>
                @endif

                <div class="send_alert"></div>

                <div class="col-12">
                    <fieldset>
                        <label class="label">Phone No. or email address</label>
                        <input type="text" name="email" id="inputField"/>
                        <span class="text-danger error errorUsername"></span>
                    </fieldset>
                </div>


                <div class="col-12 mt-4">
                    <button type="submit" class="btn-primary d-block mt-2 btn-signin" disabled>Reset Password</button>
                    <a href="{{route('login')}}" class="text_14 d-block mt-4 text-end">Already has account? --Login please</a>
                </div>
            </div>
        </form>
    </div>
</div> 

@endsection


@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>

    <style>

        .btn-primary.disabled, .btn-primary:disabled {
            color: #fff;
            background-color: silver;
            border-color: #0d6efd;
        }
    </style>
    <script>
        $(document).ready(function() {
            $('#inputField').on('input', function(){
                var userInput = $(this).val();
                $('.send_alert').html('');
                if(isValidMobile(userInput)) {
                    $('#resetForm').prop('action',"?");
                    $('.btn-signin').text('Send OTP for verificatin')
                    $('#resetForm').prop('method',"POST");
                    $('.btn-signin').prop('disabled',true);
                    $('.send_alert').html('<p class="text-center p-4 bg-warning"><b>OTP</b> system server is currently disabled. Please type your <b>Email</b> address')
                } else if(isValidEmail(userInput)) {
                    console.log("Email: " + userInput);
                    $('#resetForm').prop('action',"{{ route('password.email') }}");
                    $('#resetForm').prop('method',"POST");
                    $('.btn-signin').text('Send Password Reset Link')
                    $('.btn-signin').prop('disabled',false)
                } else {
                    $('.btn-signin').text('Verifying...')
                    $('#resetForm').prop('action',"");
                    $('.btn-signin').prop('disabled',true)
                    $('.send_alert').html('')
                }
            });
        });


        var input = document.querySelector("#inputField");
        var iti = window.intlTelInput(input, {
            initialCountry: "{{ session('user_currency')->short_code }}",  // No default country
            nationalMode: false,  // User enters full international number, including country code
            autoHideDialCode: false,  // Keep the dial code visible in the input
            separateDialCode: false,  // Do not show the dial code separately
            allowDropdown: false,  // Disable the country dropdown
            utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"  // Use utils.js for validation
        });

        // Function to validate phone numbers including country code
        function isValidMobile() {
            return iti.isValidNumber();  // Validate the full phone number based on the country code entered
        }
        function isValidEmail(input) {
            // Regular expression for email validation
            var emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(input);
        }
    </script>
@endpush