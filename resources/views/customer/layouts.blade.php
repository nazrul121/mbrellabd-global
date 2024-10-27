<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-js">

<head>
    <meta http-equiv="content-type" content="text/html;charset=utf-8" />
    <link rel="preload" href="{{asset('assets/fonts/powerkit-icons.woff')}}" as="font" type="font/woff" crossorigin />

    @stack('meta')
    @include('includes.head')
    
    @stack('style')

    <style>
        .sidebar {
          background-color: #343a40;
          min-height: 100vh;
          padding: 1rem;
          color: white;
          display: none; /* Hide sidebar */
        }
    
        .main-content {
            padding: 2rem;
            border: 2px solid #212529;
            border-top: 0;
        }
        .navBar2{ background: #000000c7}
    
        .nav-menu {
          text-align: center; /* Center align navigation */
          margin-bottom: 2rem;
        }
    
        .nav-item {
          margin: 0 1rem; /* Spacing between nav items */
        }
    
        .dashboard-card {
          border-radius: 10px;
          box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    
        .dashboard-card h5 {
          color: #007bff;
        }
        .dashboard-card {
            border-radius: 15px;
            transition: 0.3s;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            background-color: white;
        }
            .dashboard-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.15);
        }
            .card-icon {
            font-size: 30px;
            color: #007bff;
         }
        .card-header {
            background-color: #2125291f;
            color: #000000;
            font-size: 1.25rem;
        }
        .required{color:red}
    </style>
</head>

<body>

    <?php 
        $customer = \App\Models\Customer::where('user_id', Auth::user()->id)->first();
        $divisions = \App\Models\Division::where(['status'=>'1', 'country_id'=> session('user_currency')->id])->get();
        if(session('user_currency')->id !=2) $disState = 'States'; else $disState = 'Districts';
    ?>

    <div class="body-wrapper">
        <!-- announcement bar start -->
        @include('includes.top-header')

        <div class="container">
            @include('customer.includes.nav')
            <!-- Main Content -->
            <div class="col-lg-12 main-content">
                    
                <div class="row mb-4">
                    <div class="col-12">
                        <h2 class="text-center">Welcome, [@if($customer ==null) Unknown @else {{ $customer->first_name }} @endif]</h2>
                        <p class="text-center text-muted">@if($customer ==null) please complete your billing information @else {{ $customer->address.' - '.$customer->postCode }} @endif </p>
                    </div>
                </div>

                <!-- Dashboard Info Cards -->
                <div class="row g-4">
                    @if(Auth::user()->phone!=null && $customer !=null && $customer->division_id !=null && $customer->district_id !=null && $customer->city_id !=null)
                        @yield('content')
                    @else
                        @include('customer.includes.mainProfileForm')
                    @endif 
                </div>
            </div>
        </div>
    </div>



        <!-- scrollup start -->
        <button id="scrollup">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="18 15 12 9 6 15"></polyline></svg>  
        </button>
        
        <input type="hidden" id="url" value="{{ url('/') }}">

        <!-- all js -->
        <script src="/assets/js/vendor.js"></script>
        <script src="/assets/js/main.js"></script>
        <script>

            let url = $('#url').val();
            $(document).ready(function(){
                //get districts
                $('[name=division]').on('change',function(){
                    $("[name=district]").html('') ; $("[name=city]").html('');
                    $("[name=district]").append('<option value="">{{ $disState }}</option>')
                    let id =  $(this).val();

                    $.ajax({ url:url+"/get-districts/"+ id, method:"get",
                        success:function(data){
                            $.each(data, function(index, value){
                                $("[name=district]").append('<option value="'+value.id+'">'+value.name+'</option>');
                            });
                        }
                    });
                });

                // get cities
                $('[name=district]').on('change',function(){
                    $("[name=city]").html('')
                    $("[name=city]").append('<option value="">Cities</option>')
                    let id =  $(this).val();
                    $.ajax({ url:url+"/get-cities/"+ id, method:"get",
                        success:function(data){
                            $.each(data, function(index, value){
                                $("[name=city]").append('<option value="'+value.id+'">'+value.name+'</option>');
                            });
                        }
                    });
                });

            });

        </script>

        @stack('scripts')
    </div>
</body>

</html>