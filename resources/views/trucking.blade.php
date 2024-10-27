@extends('layouts.app')

    @php
        $ids = array();
        $doneStep = 0;
        $lastStatus = null;
        $orderStatus = \App\Models\Order_status::where('action','continue')->get();
        
        $metas = \DB::table('metas')->where('pageFor','track');
        $meta = \DB::table('metas')->where(['pageFor'=>'track', 'type'=>'title']);
        
        $metaTitle = 'Mbrella | Track your ourder';
        if($meta->count() >0){
            $metaTitle = $meta->pluck('description')->first();
        }
    @endphp

    @push('meta')
        <meta property="og:url" content="{{url()->full()}}" />
        <meta property="og:type" content="website">
        @foreach ($metas->get() as $meta)
            <meta property="og:{{$meta->type}}" content="{{$meta->description}}" />
        @endforeach
    @endpush


@section('title',$metaTitle)

@section('content')
    <div class="breadcrumb">
        <div class="container">
            <ul class="list-unstyled d-flex align-items-center m-0">
                <li><a href="{{route('home')}}">Home</a></li>
                <li>
                    <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g opacity="0.4">
                            <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z" fill="#000"></path>
                        </g>
                    </svg>
                </li>
                <li>Tracking order</li>
            </ul>
        </div>
    </div>

    <div class="container">
        <div class="row mt-5">
            <form method="get" class="col-12">
                <div class="row">
                    <div class="col-md-6 offset-md-2 col-sm-6">
                        <input type="text" name="invoice" class="form-control" placeholder="Invoice/Transaction ID" value="{{ request()->get('invoice') }}">
                    </div>

                    <div class="col-md-3 col-sm-5">
                        <button class="btn-primary checkOrder float-end form-control" type="submit">Start Tracking</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="row mt-5 truckAra" style="display:none">
            @if(request()->get('invoice'))
                @if($order !=null)
                    <div class="container">
                        <img class="checkOrderTruck" src="{{url('/storage/images/truck2.gif')}}" style="height:55px;position:relative;top:57px;z-index:999;" id="truck">
                        <?php $orderStatus = \App\Models\Order_status::where('action','continue')->get();?>
                        <div class="track">
                            @foreach ($orderStatus as $key=>$status)
                                <?php $count = \DB::table('order_status_changes')->where(['order_id'=>$order->id,'order_status_id'=>$status->id])->count();
                                $ids[] = $status->id;
                                if($count > 0){
                                    $doneStep = $doneStep+1;
                                } ?>

                                <div class="step @if($count > 0)active @endif">
                                    <span class="icon"> @if($count >0)<i class="fa fa-check"></i> @else <i class="fa fa-lock"></i> @endif </span> <span class="text">{{ $status->title }} </span>
                                </div>
                            @endforeach
                            <?php $lastStatus = \App\Models\Order_status_change::where('order_id',$order->id)->whereNotIn('order_status_id',$ids)->first();?>
                            @if($lastStatus !=null)
                                <div class="step active">
                                    <span class="icon"> @if($lastStatus->order_status->relational_activity=='delivered')<i class="fa fa-check"></i> @else <i class="fa fa-times text-danger"></i> @endif  </span> <span class="@if($lastStatus->order_status->relational_activity=='delivered')text-success @else text-danger @endif text">{{ $lastStatus->order_status->title }} </span>
                                </div>
                            @else 
                                <div class="step">
                                    <span class="icon"><i class="fa fa-lock"></i> </span> <span class="text"> Final step</span>
                                </div>
                            @endif
                        </div>
                    </div>
                    <input type="hidden" name="stepDone" value="{{$doneStep}}">
                    <input type="hidden" name="steps" value="{{$orderStatus->count()}}">

                    <div class="row mt-4"> <br>
                        @if($order !=null)
                            <p class="text-info text-center">Your order status is: <b>{{ $order->order_status->title }} </b> <br>
                            <span class="text-primary">Order date: {{ date('l, jS \of F, Y , h:i:s A',strtotime($order->created_at)) }}</span>
                            </p>
                        @else
                            <p class="text-danger text-center p-5 bg-5">Please put your valid <code>invoice</code>/<code>transaction</code> Number </p>
                        @endif

                    </div>
                @else 
                    <p class="text-center text-warning bg-dark p-md-4"><b>Invoice No</b> / <b>Transaction ID</b> is not correct one</p>
                @endif
            @endif
        </div>
    </div>

   
@endsection

@push('scripts')
    <style>

        .container {
            margin-bottom: 50px
        }

        .track {
            position: relative;
            background-color: #ddd;
            height: 7px;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            margin-bottom: 60px;
            margin-top: 50px
        }

        .track .step {
            -webkit-box-flex: 1;
            -ms-flex-positive: 1;
            flex-grow: 1;
            width: 25%;
            margin-top: -18px;
            text-align: center;
            position: relative
        }

        .track .step.active:before {
            background: orange
        }

        .track .step::before {
            height: 7px;
            position: absolute;
            content: "";
            width: 100%;
            left: 0;
            top: 18px
        }

        .track .step.active .icon {
            background:orange;
            color: #fff
        }

        .track .icon {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            position: relative;
            border-radius: 100%;
            background: #ddd
        }

        .track .step.active .text {
            font-weight: 400;
            color: #000
        }

        .track .text {
            display: block;
            margin-top: 7px
        }

        .itemside {
            position: relative;
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            width: 100%
        }

        .itemside .aside {
            position: relative;
            -ms-flex-negative: 0;
            flex-shrink: 0
        }

        .img-sm {
            width: 80px;
            height: 80px;
            padding: 7px
        }

        ul.row,
        ul.row-sm {
            list-style: none;
            padding: 0
        }

        .itemside .info {
            padding-left: 15px;
            padding-right: 7px
        }

        .itemside .title {
            display: block;
            margin-bottom: 5px;
            color: #212529
        }

        p {
            margin-top: 0;
            margin-bottom: 1rem
        }

        .btn-warning {

            background-color: orange;
            border-color: orange;
            border-radius: 1px
        }

        .btn-warning:hover {

            background-color: orange;
            border-color: orange;
            border-radius: 1px
        }
    </style>

    <script>
        $(function(){
            $('.truckAra').slideDown(200);
            var percent = $('[name=stepDone]').val();
            var newPercent = percent * 18;

            $("#truck").animate({ left:newPercent+'%'}, 2000);

            $('.checkOrder').on('click',function (){
                $(this).text('Tracking your data...')
            });

            @if($order !=null)
             @if($lastStatus !=null)
                setTimeout(() => {
                    $("#truck").animate({
                        top:"101px",
                        left:newPercent - 1 +'%',
                    }, 1500);

                    $("#truck").animate({left:"92%"}, 400);
                    $("#truck").animate({ transform: "rotate(0deg)", top:"57px"}, 1000);

                }, 2000);


             @endif
            @endif
        });

        function AnimateRotate(d){
            $({deg:0}).animate({deg: d}, {
                step: function(now, fx){
                    $("#truck").css({
                        transform: "rotate(" +now + "deg)"
                    });
                }
            });
        }
    </script>
@endpush 
