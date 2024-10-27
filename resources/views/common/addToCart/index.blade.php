
@extends('common.layouts')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header"><h5>Customer non-ordered cart list</h5>
                    <div class="card-header-right">
                        
                    </div>
                </div>
        
                <div class="card-body">
                    <div class="row">
                        <div class="card-body">
                            <table class="table table-hover m-0">
                                <tr class="bg-light">
                                <th>Cart ID</th> <th> items</th> <th>Qty</th>
                                </tr>

                                @foreach ($cartlist as $key=>$item)
                                    @php $newCart = \App\Models\Cartlist::where('session_id',$item->session_id); 
                                    $qty = array();

                                    foreach ($newCart->get() as $key => $value) {
                                        $qty[] = $value->qty;
                                    }
                                    @endphp
                                    <tr class="cartView" data-id="{{$item->session_id}}">
                                        <td>{{$item->session_id}}</td>
                                        <td>{{$newCart->count()}} items</td>
                                        <td>{{array_sum($qty)}}</td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                        
                    </div>
                    <div class="row">
                        {{$cartlist->links()}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('common.addToCart.modals')
@endsection


@push('scripts')

<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {
        $('.cartView').on('click', function(){
            var id = $(this).data('id');
            $('#productModal').modal('show');
            $.get(url + '/common/report/addToCart/'+id, function(data){
                $('.cartItems').html(data)
            })
        })

    });
</script>

@endpush
