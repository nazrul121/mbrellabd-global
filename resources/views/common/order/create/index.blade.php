@extends('common.layouts')

@section('content')

    <div class="row">
        <div class="col-12">
            <form class="card" action="{{ route('common.make-order') }}">@csrf
                @if(session()->has('address'))
                    <div class="card-header"> <button class="btn btn-info btn-sm float-right addAItem" type="button"><i class="fa fa-plus"></i> Add Product</button></div>
                @endif

                <div class="card-body">
                    @if(Session::has('success')) <p class="alert alert-success"><i class="fa fa-check text-warning"></i> {{ Session::get('success') }}</p> @endif
                    @if(Session::has('error')) <p class="alert alert-danger"><i class="fa fa-times text-warning"></i> {{ Session::get('error') }}</p> @endif

                    <div class="table-responsive">
                        <table class="table table-hover table-bordered" style="width:100%">
                            <thead> <tr> <th>Image</th> <th>Product info</th> <th>Price</th>  <th>Qty</th> <th></th></tr></thead>
                            <tbody class="itemTable"></tbody>
                        </table>
                    </div>
                    <div class="table-responsive mt-5">
                        <table class="table table-hover table-bordered" style="width:100%">
                            <thead class="bg-dark"> <tr> <th>Billing info</th>  <th>Shipping info</th> </tr></thead>
                            <tbody>
                                <tr>
                                    @if(Session::has('address'))
                                    <td>
                                        Name: {{ Session::get('address')['fname'].' '.Session::get('address')['lname'] }} <br>
                                        Phone No: {{ Session::get('address')['phone'] }} <br>
                                        Area: {{ \DB::table('divisions')->where('id',Session::get('address')['division'])->pluck('name')->first() }} <i class="fa fa-arrow-right"></i>
                                        {{ \DB::table('districts')->where('id',Session::get('address')['district'])->pluck('name')->first() }}  <i class="fa fa-arrow-right"></i>
                                        {{ \DB::table('cities')->where('id',Session::get('address')['city'])->pluck('name')->first() }} <br>
                                        Address: {{ Session::get('address')['address'] }}

                                    </td>
                                    <td>

                                        @if(array_key_exists('same', Session::get('address')))
                                            Name: {{ Session::get('address')['fname'].' '.Session::get('address')['lname'] }} <br>
                                            Phone No: {{ Session::get('address')['phone'] }} <br>
                                            Area: {{ \DB::table('divisions')->where('id',Session::get('address')['division'])->pluck('name')->first() }} <i class="fa fa-arrow-right"></i>
                                            {{ \DB::table('districts')->where('id',Session::get('address')['district'])->pluck('name')->first() }}  <i class="fa fa-arrow-right"></i>
                                            {{ \DB::table('cities')->where('id',Session::get('address')['city'])->pluck('name')->first() }} <br>
                                            Address: {{ Session::get('address')['address'] }}

                                        @else
                                            Name: {{ Session::get('address')['shipping_fname'].' '.Session::get('address')['shipping_lname'] }} <br>
                                            Phone No: {{ Session::get('address')['shipping_phone'] }} <br>
                                            Area: {{ \DB::table('divisions')->where('id',Session::get('address')['shipping_division'])->pluck('name')->first() }}  <i class="fa fa-arrow-right"></i>
                                            {{ \DB::table('districts')->where('id',Session::get('address')['shipping_district'])->pluck('name')->first() }}  <i class="fa fa-arrow-right"></i>
                                            {{ \DB::table('cities')->where('id',Session::get('address')['shipping_city'])->pluck('name')->first() }} <br>
                                            Address: {{ Session::get('address')['shipping_address'] }}
                                        @endif
                                    </td>
                                    @else
                                        <td colspan="2" class="text-center"> <button class="btn btn-secondary addAddress" type="button"><i class="fa fa-plus"></i> Create billing and shipping details</button></td>
                                    @endif
                                </tr>

                            </tbody>
                        </table>
                    </div>

                    
                    <button type="submit" class="btn btn-info float-right"> <i class="feather icon-bar-chart"></i> Make order</button>
                    <a href="{{route('common.refresh-create-order')}}" class="btn btn-light float-right"> <i class="feather icon-remove"></i> Cancel order</a>
                </div>

            </form>
        </div>
    </div>
    @include('common.order.create.modal')

@endsection


@push('scripts')

<link rel="stylesheet" href="{{ asset('back2') }}/plugins/data-tables/css/datatables.min.css">
<script src="{{ asset('back2') }}/plugins/data-tables/js/datatables.min.js"></script>
<script src="{{ asset('back2') }}/js/pages/tbl-datatable-custom.js"></script>


<script>
    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');

    $(document).ready(function () {

        $(".itemTable" ).load( "{{ route('common.order-items') }}" );

        $('.itemTable').on('click', '.removeBtn', function(){
            var id =$(this).data('id');
            if(confirm('Are you sure to remove the order item from the list??')){
                $.get("/common/order/create/remove-item/"+id);
                $( ".itemTable" ).load( "{{ route('common.order-items') }}" );
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
            var value = $(this).text(); var id = $(this).data('id');
            $('[name=product_id]').val(id); $('#title').val(value);
            $('#product_list').html("");

            $.get(url+'/common/order/get-product-combinations/'+id, function( data ) {
                $('[name=combination]').html(data);
            });

            $(".addToCartBtn").prop('disabled',false);
        });

        $('#addToCart').submit(function(event) {
            event.preventDefault(); // Prevent the form from submitting via the browser
            var form = $(this);
            $('.addToCartBtn').prop('disabled',true);
            $.ajax({ type: form.attr('method'), url: form.attr('action'), data: form.serialize()})
            .done(function(data) {
                if(data.failed){
                    $('[name=combination]').css('border','1px solid red'); 
                    alert(data.failed); return false;
                }else{
                     $('[name=combination]').css('border','1px solid #ddd'); 
                }
                
                $( ".itemTable" ).load( "{{ route('common.order-items') }}" );
                $('#title').val('');   $('[name=product_id]').val('');
                $('[name=combination]').html(''); $('[name=qty]').html('');
                $('.addToCartBtn').prop('disabled',false);
            }).fail(function(data) {
                // Optionally alert the user of an error here...
            });
        });


        $('.addAddress').on('click', function(){
            var type = $(this).data('type');

            var id = $(this).data('id');
            $('#addressModal').modal('show');

            $('[name=type]').val(type); $('[name=id]').val(id);
            $('.shippingForm').css('display','none')

        })

        $('#isSame').on('change',function(){
            if($(this).is(':checked')) {
                $(this).val('1');
                $('.shippingForm').css('display','none')

                $('[name=shipping_fname]').attr('required',false);
                $('[name=shipping_lname]').attr('required',false);
                $('[name=shipping_phone]').attr('required',false);
                $('[name=shipping_division]').attr('required',false);
                $('[name=shipping_district]').attr('required',false);
                $('[name=shipping_city]').attr('required',false);
                $('[name=shipping_address]').attr('required',false);
            }
            else {
                $(this).val('0');
                $('.shippingForm').css('display','block')

                $('[name=shipping_fname]').attr('required',true);
                $('[name=shipping_lname]').attr('required',true);
                $('[name=shipping_phone]').attr('required',true);
                $('[name=shipping_division]').attr('required',true);
                $('[name=shipping_district]').attr('required',true);
                $('[name=shipping_city]').attr('required',true);
                $('[name=shipping_address]').attr('required',true);
            }
        });

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


        $('.addAItem').on('click', function(){
            $('#itemModal').modal('show');
        })

        //check if billing address exist
        $('.checkBilling').on('click',function(){
            var fieldVal = '';
            var field = $(this).data('field');
            if(field=='phone'){
                fieldVal = $('[name=phone]').val();
            }else{
                fieldVal = $('[name=email]').val();
            }
            
            if(fieldVal.length >0){
                $.ajax({ url:url+"/check-billing/"+ field+'/'+fieldVal, method:"get",
                    success:function(data){
                        if(data){
                            $('[name=address]').val(data.address);
                            $('[name=fname]').val(data.first_name);
                            $('[name=lname]').val(data.last_name);
                            $('[name=phone]').val(data.phone);
                            $('[name=email]').val(data.email);

                            get_district(data.division_id);
                            get_cities(data.district_id);

                            $('[name=division] option[value="'+data.division_id+'"]').attr('selected', true);
                            setTimeout(function() {
                                $('[name=district] option[value="'+data.district_id+'"]').attr('selected', true);
                                $('[name=city] option[value="'+data.city_id+'"]').attr('selected', true);
                            }, 200);
                        }
                    }
                });
            }

            
        })

        $('.checkShipping').keyup(function(){
            let field = $(this).data('field');
            let fieldVal = $(this).val();
            // console.log(field+fieldVal);
            $.ajax({ url:url+"/check-shipping/"+ field+'/'+fieldVal, method:"get",
                success:function(data){
                    if(data){
                        $('[name=shipping_address]').val(data.address);
                        $('[name=shipping_fname]').val(data.fname);
                        $('[name=shipping_lname]').val(data.lname);
                        $('[name=shipping_phone]').val(data.phone);
                        $('[name=shipping_email]').val(data.email);

                        get_shipping_districts(data.division_id);
                        get_shipping_cities(data.district_id);

                        $('[name=shipping_division] option[value="'+data.division_id+'"]').attr('selected', true);
                        setTimeout(function() {
                            $('[name=shipping_district] option[value="'+data.district_id+'"]').attr('selected', true);
                            $('[name=shipping_city] option[value="'+data.city_id+'"]').attr('selected', true);
                        }, 310);

                    }
                }
            });
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
