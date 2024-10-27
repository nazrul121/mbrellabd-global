<!DOCTYPE html>
<html lang="en">

<head>
    <title>@yield('title', Auth::user()->user_type->title.' admin access')</title>
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="description" content="{{ request()->get('system_slogan') }}" />
    <meta name="keywords" content="mbrella">
    <meta name="author" content="{{ request()->get('system_title') }}" />
    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ url('storage').'/'.request()->get('favicon') }}" type="image/x-icon">
    <link rel="icon" href="{{ url('storage').'/'.request()->get('favicon') }}" type="image/x-icon">

    <script>
        (function(h,o,t,j,a,r){
            h.hj=h.hj||function(){(h.hj.q=h.hj.q||[]).push(arguments)};
            h._hjSettings={hjid:1629436,hjsv:6};
            a=o.getElementsByTagName('head')[0];
            r=o.createElement('script');r.async=1;
            r.src=t+h._hjSettings.hjid+j+h._hjSettings.hjsv;
            a.appendChild(r);
        })(window,document,'https://static.hotjar.com/c/hotjar-','.js?sv=');
    </script>

    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css">
    <link rel="stylesheet" href="{{ asset('back2') }}/plugins/animation/css/animate.min.css">
    <link rel="stylesheet" href="{{ asset('back2') }}/plugins/notification/css/notification.min.css">

    <link rel="stylesheet" href="{{ asset('back2') }}/css/style.css">

    @stack('styles')
</head>
<body>

    <div class="loader-bg"> <div class="loader-track"> <div class="loader-fill"></div></div> </div>

    @if (Auth::user()->user_type->title=='staff')
        @include('common.includes.left_nav')
        {{-- @include('staff.includes.nav') --}}
    @else
        @include('common.includes.left_nav')
    @endif

    @include('common.includes.header')


    @include('common.includes.short-message')


    <div class="pcoded-main-container">
        <div class="pcoded-wrapper">
            <div class="pcoded-content">
                <div class="pcoded-inner-content">
                    <div class="main-body">
                        <div class="page-wrapper"> @yield('content') </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="url" value="{{ url('/') }}">

    <script src="{{ asset('back2') }}/js/vendor-all.min.js"></script>
    <script src="{{ asset('back2') }}/plugins/bootstrap/js/bootstrap.min.js"></script>
    <script src="{{ asset('back2') }}/js/menu-setting.min.js"></script>
    <script src="{{ asset('back2') }}/js/pcoded.min.js"></script>

    <script src="{{ asset('back2') }}/plugins/amchart/js/amcharts.js"></script>
    <script src="{{ asset('back2') }}/plugins/amchart/js/gauge.js"></script>
    <script src="{{ asset('back2') }}/plugins/amchart/js/serial.js"></script>
    <script src="{{ asset('back2') }}/plugins/amchart/js/light.js"></script>
    <script src="{{ asset('back2') }}/plugins/amchart/js/pie.min.js"></script>
    <script src="{{ asset('back2') }}/plugins/amchart/js/ammap.min.js"></script>
    <script src="{{ asset('back2') }}/plugins/amchart/js/usaLow.js"></script>
    <script src="{{ asset('back2') }}/plugins/amchart/js/radar.js"></script>
    <script src="{{ asset('back2') }}/plugins/amchart/js/worldLow.js"></script>
    
    <script src="{{ asset('back2') }}/plugins/notification/js/bootstrap-growl.min.js"></script>
    @stack('scripts')
    <script>
        var url = $('#url').val()
        
    </script>
    </body>
</html>
