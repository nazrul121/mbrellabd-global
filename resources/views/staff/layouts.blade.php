<!DOCTYPE html>
<html lang="en">
<head>
<title>@yield('title',Auth::user()->user_type->title.' access panel')</title>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="description" content="Datta Able Bootstrap admin template made using Bootstrap 4 and it has huge amount of ready made feature, UI components, pages which completely fulfills any dashboard needs." />
<meta name="keywords" content="admin templates, bootstrap admin templates, bootstrap 4, dashboard, dashboard templets, sass admin templets, html admin templates, responsive, bootstrap admin templates free download,premium bootstrap admin templates, datta able, datta able bootstrap admin template">
<meta name="author" content="Codedthemes" />

<link rel="icon" href="https://codedthemes.com/demos/admin-templates/datta-able/bootstrap/assets/images/favicon.ico" type="image/x-icon">
<link rel="stylesheet" href="{{ asset('back2') }}/fonts/fontawesome/css/fontawesome-all.min.css">
<link rel="stylesheet" href="{{ asset('back2') }}/plugins/animation/css/animate.min.css">
<link rel="stylesheet" href="{{ asset('back2') }}/plugins/prism/css/prism.min.css">
<link rel="stylesheet" href="{{ asset('back2') }}/css/style.css">
</head>
<body>

<div class="loader-bg"><div class="loader-track"><div class="loader-fill"></div></div></div>


@include('staff.includes.nav')


@include('common.includes.header')


<div class="pcoded-main-container">
<div class="pcoded-wrapper">
<div class="pcoded-content">
<div class="pcoded-inner-content">

<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">  <h5 class="m-b-10">Horizontal Layout</h5>  </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index-2.html"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="#!">Page Layouts</a></li>
                    <li class="breadcrumb-item"><a href="#!">Horizontal Layout</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

    <div class="main-body">
        <div class="page-wrapper">
            @yield('content')
        </div>
    </div>
</div>
</div>
</div>
</div>


<script src="{{ asset('back2') }}/js/vendor-all.min.js"></script>
<script src="{{ asset('back2') }}/plugins/bootstrap/js/bootstrap.min.js"></script>
<script src="{{ asset('back2') }}/js/pcoded.min.js"></script>

<script src="{{ asset('back2') }}/plugins/prism/js/prism.min.js"></script>
<script src="{{ asset('back2') }}/js/horizontal-menu.js"></script>
<script src="{{ asset('back2') }}/js/menu-setting.min.js"></script>

</body>
</html>
