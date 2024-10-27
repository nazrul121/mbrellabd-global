
@extends('common.layouts')

@section('content')

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5>Edit order. Transaction ID: <b>{{ $order->transaction_id }}</b>, Invoice ID: <b>{{ $order->invoice_id }}</b> </h5>
                    <button class="btn btn-info btn-sm float-right addItem"><i class="fa fa-plus"></i> Add new item</button>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" style="width:100%">
                            <thead> <tr> <th>Billing info</th>  <th class="text-right">Shipping info</th> </tr></thead>
                            <tbody>
                                <tr>
                                    <td>Name: {{ $order->first_name.' '.$order->last_name }} <br>
                                        Phone: {{ $order->phone }}<br> <button class="btn btn-info float-right editAddress" data-type="billing" ><i class="fa fa-edit text-white"></i> Edit address</button>
                                        Area: {{ $order->division }} <i class="fa fa-long-arrow-right"></i>   
                                        {{ $order->district }} <i class="fa fa-long-arrow-right"></i> 
                                        {{ $order->city }} <br

                                        Address: {{ $order->address }}
                                    </td>
                                    <td>Name: {{ $order->ship_first_name.' '.$order->ship_last_name }} <br>
                                        Phone: {{ $order->ship_phone }} <br> <button class="btn btn-info float-right editAddress" data-type="shipping"><i class="fa fa-edit text-white"></i> Edit address</button>
                                        Area: {{ $order->ship_division }} <i class="fa fa-long-arrow-right"></i>
                                        {{ $order->ship_district}} <i class="fa fa-long-arrow-right"></i>  
                                        {{ $order->ship_city }} <br> 
                                        Address: {{ $order->ship_address }}
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="table-responsive">
                        <table class="table table-hover table-bordered itemTable" style="width:100%">
                            <thead> <tr> <th>Product info</th> <th>Price</th>  <th class="text-right">Qty</th> </tr></thead>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('common.order.edit.modal')

    @php
        $divId = \DB::table('divisions')->where(['country_id'=>$order->country_id, 'name'=>$order->division, 'status'=>'1'])->pluck('id')->first();
        $disId = \DB::table('districts')->where(['division_id'=>$divId, 'name'=>$order->district, 'status'=>'1'])->pluck('id')->first();
        $cityId = \DB::table('cities')->where(['district_id'=>$disId, 'name'=>$order->city, 'status'=>'1'])->pluck('id')->first();

        $shipDivId = \DB::table('divisions')->where(['country_id'=>$order->country_id, 'name'=>$order->ship_division, 'status'=>'1'])->pluck('id')->first();
        $shipDisId = \DB::table('districts')->where(['division_id'=>$shipDivId, 'name'=>$order->ship_district, 'status'=>'1'])->pluck('id')->first();
        $shipCityId = \DB::table('cities')->where(['district_id'=>$shipDisId, 'name'=>$order->ship_city, 'status'=>'1'])->pluck('id')->first();

    @endphp
@endsection


@push('scripts')
<style>
    .width200{width:150px}
</style>
<link rel="stylesheet" href="{{ asset('back2') }}/plugins/data-tables/css/datatables.min.css">
<script src="{{ asset('back2') }}/plugins/data-tables/js/datatables.min.js"></script>
<script src="{{ asset('back2') }}/js/pages/tbl-datatable-custom.js"></script>


<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {

        $(function () { table.ajax.reload(); });

        let table = $('.itemTable').DataTable({
            processing: true,serverSide: true,
            "language": { processing: '<img src="'+url+'/storage/images/ajax-loader.gif">'},
            ajax: "{{route('common.edit-order',$order->id)}}",
            order: [ [0, 'desc'] ],
            columns: [
                {data: 'product_info', orderable: false, searchable: false},
                {data: 'price', orderable: false, searchable: false},
                {data: 'qty', orderable: false, searchable: false, class:'width200'}
            ]
        });


        $('.itemTable').on('change','.changeCombination', function(){
            $('.updateComButton').attr('disabled',true)
            $('.updateComb'+ $(this).val().split("|")[1]).attr('disabled',false);
        })

        $('.itemTable').on('click', '.updateComButton', function(){
            if(confirm('Are you sure to update the order item variation??')){
                var id =$(this).data('id'); var comb = $('.comb'+id).val().split("|")[0];
                $.get("/common/order/update-combination/edit-variation/"+id+'/'+comb, function( data ) {
                    $('.itemTable').DataTable().ajax.reload();
                });
            }
        })

        $('.itemTable').on('keyup','.changeQty',function(){
            var qty = $(this).val(); var id =$(this).data('id');
            if(qty.length >0){
                if(confirm('Are you sure to update the order item Quantity??')){
                    $.get("/common/order/update-order-qty/update-qty/"+id+'/'+qty);
                } $('.itemTable').DataTable().ajax.reload();
            }
            
        })

        $('.itemTable').on('click', '.removeBtn', function(){
            var id =$(this).data('id');
            if(confirm('Are you sure to remove the order item from the list??')){
                $.get("/common/order/delete-order-item/remove-item/"+id);
                $('.itemTable').DataTable().ajax.reload();
            }
        })


        $('.addItem').on('click',function(){ $('#addModal').modal('show'); })

        $('#title').on('keyup', function(){
            var query = $(this).val();
            if(query.length >1){
                $.ajax({
                    url:"{{ route('common.searach-products') }}",
                    type:'GET', data:{'name':query},
                    success:function (data) { $('#product_list').html(data);}
                })
            }else $('#product_list').html('');
        });

        $(document).on('click', 'li', function(){
            var value = $(this).text();
            var id = $(this).data('id');
            $('[name=product_id]').val(id);
            $('#title').val(value);
            $('#product_list').html("");

            $.get('/common/order/get-product-combinations/'+id, function( data ) {
                $('[name=variation]').html(data);
            });

            $(".addToCartBtn").prop('disabled',false);
        });


        $('#addToCart').submit(function(event) {
            event.preventDefault(); // Prevent the form from submitting via the browser
            var form = $(this);
            // alert(form.attr('action'));
            $.ajax({
                type: form.attr('method'),
                url: form.attr('action'),
                data: form.serialize()
            }).done(function(data) {
                if(data.success){
                    $('.itemTable').DataTable().ajax.reload();
                    alert(data.success)
                }else alert(data.failed)

                $('#title').val('');
                $('[name=product_id]').val('');
                $('[name=variation]').html('');
                $('[name=qty]').html('');
            }).fail(function(data) {
                // Optionally alert the user of an error here...
            });
            $('.itemTable').DataTable().ajax.reload();
        });


        $('.editAddress').on('click', function(){
            var type = $(this).data('type');
            var id = $(this).data('id');
            $('#addressModal').modal('show');
            $('#addressModalLabel').text('Edit ' + type + ' address');

            $('[name=type]').val(type); $('[name=id]').val(id);

            if(type=='billing'){
                $('.billingForm').css('display','block')
                $('.shippingForm').css('display','none')

                $('[name=shipping_fname]').attr('required',false);
                $('[name=shipping_lname]').attr('required',false);
                $('[name=shipping_phone]').attr('required',false);
                $('[name=shipping_division]').attr('required',false);
                $('[name=shipping_district]').attr('required',false);
                $('[name=shipping_city]').attr('required',false);
                $('[name=shipping_address]').attr('required',false);

                get_district("{{ $divId }}");
                get_cities("{{ $disId }}");

                setTimeout(function() {
                    $("[name=district] option").each(function(){
                        if ($(this).val() == "{{ $disId.'|'.$order->district }}")  $(this).attr("selected",true);
                    });

                    $("[name=city] option").each(function(){
                        if ($(this).val() == "{{ $cityId.'|'.$order->city }}")  $(this).attr("selected",true);
                    });

                }, 1000);

            }else{
                $('.billingForm').css('display','none')
                $('.shippingForm').css('display','block')

                $('[name=fname]').attr('required',false);
                $('[name=lname]').attr('required',false);
                $('[name=phone]').attr('required',false);
                $('[name=division]').attr('required',false);
                $('[name=district]').attr('required',false);
                $('[name=city]').attr('required',false);
                $('[name=address]').attr('required',false);
                get_shipping_districts("{{ $shipDivId }}");

                setTimeout(function() {
                    $("[name=shipping_district] option").each(function(){
                        if ($(this).val() == "{{ $shipDisId.'|'.$order->ship_district }}")  $(this).attr("selected",true);
                    });
                }, 1000);


                @if($shipDisId !=null)
                    get_shipping_cities("{{ $shipDisId }}");
                    setTimeout(function() {
                        $("[name=shipping_city] option").each(function(){
                            if ($(this).val() == "{{ $shipCityId.'|'.$order->ship_city }}")  $(this).attr("selected",true);
                        });
                    }, 1000);
                @endif
               
            }
        })


        $('[name=division]').on('change', function(){
            var id = $(this).val();
            get_district(id);
        })

        $('[name=district]').on('change', function(){
            var id = $(this).val();
            get_cities(id);
        })

        $('[name=shipping_division]').on('change', function(){
            var id = $(this).val();
            get_shipping_districts(id);
        })

        $('[name=shipping_district]').on('change', function(){
            var id = $(this).val();
            get_shipping_cities(id);
        })

    });
</script>

<script>
    function get_district(division_id){
        $("[name=district]").html('');
        $("[name=district]").append('<option value="">Select</option>');
        $.ajax({
            url:url+"/get-districts/"+ division_id, method:"get",
            success:function(data){
                $.each(data, function(index, value){
                    $("[name=district]").append('<option value="'+value.id+'|'+value.name+'">'+value.name+'</option>');
                });
            }
        });
    }

    function get_cities(district_id){
        $("[name=city]").html('');
        $("[name=city]").append('<option value="">Select</option>');
        $.ajax({ url:url+"/get-cities/"+ district_id, method:"get",
            success:function(data){
                $.each(data, function(index, value){
                    $("[name=city]").append('<option value="'+value.id+'|'+value.name+'">'+value.name+'</option>');
                });
                $.get(url+"/district-delivery-info/"+district_id, function(data, status){
                    $('.shipping').html(data);
                });
            }
        });
    }

    function get_shipping_cities(district_id){
        $("[name=shipping_city]").html('');
        $("[name=shipping_city]").append('<option value="">Select</option>');
        $.ajax({ url:url+"/get-cities/"+ district_id, method:"get",
            success:function(data){
                $.each(data, function(index, value){
                    $("[name=shipping_city]").append('<option value="'+value.id+'|'+value.name+'">'+value.name+'</option>');
                });

            }
        });
    }

    function get_shipping_districts(div_id){
        $("[name=shipping_district]").html('');
        $("[name=shipping_district]").append('<option value="">Select</option>');
        $.ajax({ url:url+"/get-districts/"+ div_id, method:"get",
            success:function(data){
                $.each(data, function(index, value){
                    $("[name=shipping_district]").append('<option value="'+value.id+'|'+value.name+'">'+value.name+'</option>');
                });
            }
        });
    }
</script>
@endpush
