
@extends('common.layouts')

@section('content')
@php
    $total = array();
@endphp
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"><h5><b>Order Duration</b> report</h5></div>

            <div class="card-body">

                <table class="table">
                    <tr class="bg-light">
                        <th>Hours</th>
                        <th>Orders</th>
                    </tr>
                    @foreach ($averageTime as $item)
                        @php
                            $total[] = $item->qty;

                            $timestamp = strtotime($item->dateTo) + 60*60;
                            $time = date('h:a', $timestamp);
                        @endphp
                        <tr>
                            {{-- <td>{{$item->hour}} - {{$item->hourTo}} ( {{$item->dateFrom}} - {{$item->dateTo}} )</td> --}}
                            <td>{{date('h: a', strtotime($item->dateFrom)) }} - {{$time}} [{{$item->hour}} - {{$item->hourTo}}] </td>
                            <td class="orders" data-id="{{$item->hour}}">{{$item->qty}}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td class="text-right"> <b>Total Orders:</b></td>
                        <td>{{array_sum($total)}}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>


    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content orderInfo"> </div>
        </div>
    </div>

@endsection


@push('scripts')
    <script>
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        var url = $('#url').val();
        $(document).ready(function () {
            $('.orders').on('click',function(){
                var id = $(this).data('id');
                $('.bd-example-modal-lg').modal('show');
                $.get('/common/report/duration-orders/'+id, function(data){
                    $('.orderInfo').html(data);
                })
            })
        })
    </script>
@endpush