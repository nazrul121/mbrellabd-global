<form method="post" action="{{ route('common.order.save-top-sheet') }}">@csrf
    @if(Session::has('success')) <p class="alert alert-success"> {{ Session::get('success') }}</p> @endif
    @foreach ($orders as $order)
    <div class="form-group mb-3 orderInfo" style="display:none">
        <input type="hidden" name="order_ids[]" value="{{ $order->id }}">
        <div class="col-md-12 bg-light">
            <p class="alert alert-info"><b>Shipping Area: </b>{{ $order->shipping_address->division->name }} <i class="fas fa-long-arrow-alt-right"></i>
                {{ $order->shipping_address->district->name }} <i class="fas fa-long-arrow-alt-right"></i>
                {{ $order->shipping_address->city->name }}  <i class="fas fa-long-arrow-alt-right"></i> {{ $order->shipping_address->address }}
            </p>
            <div class="row">
                <label for="" class="col-md-2 text-right mt-2">Delivery Zone</label>
                <select name="zones[]" class="col-md-4 form-control zone" required>
                    <option value="">Select Delivery zone</option>
                </select>

                <label for="" class="col-md-2 text-right mt-2">Transaction id</label>
                <input type="text" readonly class="fomr-control col-md-4" value="{{ $order->transaction_id }}">
            </div>
        </div>
    </div>  @endforeach

    <div class="form-group mt-5">
        <div class="col-md-12" style="background:#ccd3d5">
            <div class="row">
            <label for="" class="col-md-2 text-right mt-2">Company</label>
            @php
                $companies = \App\Models\Courier_company::where('status','1')->get();
            @endphp
            <select name="company" class="col-md-4 form-control" required>
                <option value="">Select Courier</option>
                @foreach ($companies as $company)
                    <option value="{{ $company->id }}">{{ $company->name }}</option>
                @endforeach
            </select>

            <label for="" class="col-md-2 text-right mt-2">Representative</label>
            <select name="company_man" class="col-md-4 form-control">
                <option value="">Select Representative</option>
            </select>
            </div>
        </div>
        <div class="col-12 mt-3">
           <div class="row float-right">
            <button class="btn btn-info mr-0 shipOrderBtn" type="submit" disabled><i class="fa fa-truck"></i> Ship the orders</button>
           </div>
        </div>
    </div>
</form>

<script>
    $(function(){
        $('[name=company]').on('change',function(){
            $('.shipOrderBtn').attr('disabled',true);
            let company_id = $(this).val();
            $("[name=company_man]").html(''); $(".zone").html('');

            $.get( url+"/common/courier/companies/company-representatives/"+company_id, function(data, status){
                $("[name=company_man]").append( '<option value="">Select Representative</option>');
                $.each( data , function( i, v ){
                    $("[name=company_man]").append( '<option value="'+v.id+'">'+v.name+' - '+v.phone+'</option>' );
                });
            });

            if(company_id !='')$('.orderInfo').css('display','block');
            else $('.orderInfo').css('display','none');

            $.get( url+"/common/courier/companies/zones/"+company_id, function(data, status){
                $(".zone").append( '<option value="">Select Zone</option>');
                $.each( data , function( i, v ){
                    $(".zone").append( '<option value="'+v.id+'">'+v.name+'</option>' );
                });
                $('.shipOrderBtn').attr('disabled',false);
            });


        })
    })
</script>

