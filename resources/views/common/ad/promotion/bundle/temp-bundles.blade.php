
<div class="col-xl-12 col-md-12">

    <div class="card Recent-Users addBundle">
        <div class="card-header"> <h5>Bundle with selected product items</h5> </div>
        <form id="saveBundle" class="card-block px-0 py-3" enctype="multipart/form-data" method="post" action="{{ route('common.save-bundle') }}">@csrf
            <div class="table-responsive">
                <table class="table table-hover">
                    <?php $total = array();?>
                    <tbody>
                        @foreach ($bundle_promotion_products as $item)
                        <tr class="unread row{{ $item->id }}">
                            <td><img class="rounded-circle" style="width: 40px;" src="/storage/{{ $item->product->thumbs }}"  /></td>
                            <td>
                                <h6 class="mb-1">{{ $item->product->title }} @if($item->product->status=='1')
                                    <span class="badge badge-success">Active</span> @else
                                    <span class="badge badge-danger">Inactive</span> @endif
                                </h6>
                                <p class="m-0"><strong>Price:</strong>: {{  $item->product->sale_price }},
                                <strong>Available Quantity</strong>: {{ $item->product->qty }} </p>
                            </td>

                            <td class="float-right">
                                <a href="#!" class="label theme-bg1 text-danger f-12 remove" id="{{ $item->id }}">Remove</a>
                            </td>
                        </tr>
                        <?php $total[] = $item->product->sale_price; ?>
                        @endforeach
                        @if($bundle_promotion_products->count() >0)
                            <tr><td colspan="3"> <h2 class="alert alert-info"><i class="fa fa-check text-success"></i> Combine total prices of <b>{{ $bundle_promotion_products->count() }}</b> items: {{ array_sum($total) }} </h2></td></tr>
                        @endif

                        @if($bundle_promotion_products->count() <1)
                            <tr> <td colspan="3" class="text-center text-danger">No product selected yet</td></tr>
                        @endif
                    </tbody>
                </table>
            </div>
            <input type="hidden" name="promotion_id" value="{{ $promotion->id }}">
            @if($bundle_promotion_products->count() >0)
            <div class="row">
                <div id="BundleResult" class="col-12"></div>
                <label class="col-md-2 p-2 text-md-right" for="">Discount Price: </label>
                <div class="col-md-3">
                    <input type="number" name="discount_price" class="form-control">
                </div>

                <label class="col-md-1 p-2 text-md-right" for="">photo</label>
                <div class="col-md-3">
                    <input type="file" name="bunlde_photo" class="form-control">
                </div>

                <div class="col-md-3">
                    <button class="btn btn-info createBundleBtn" type="submit"> <i class="fa fa-plus text-warning"></i> Create a Bundle</button>
                </div>
            </div>
            @endif
        </form>

    </div>

    {{-- see bundles of the promotion --}}
    <div class="card Recent-Users promotionBundles" style="display:none">
        <div class="card-header"> <h5>Bundles of the promotion</h5> </div>
        <div class="card-block px-0 py-3" method="post" action="">
            <div class="table-responsive">
                <table class="table table-hover">
                    <tbody>
                        <?php $bundle_promotions =  \App\models\Bundle_promotion::where('promotion_id',$promotion->id)->get(); $key = 1; ?>
                        @foreach ($bundle_promotions as $bp)
                            @if($bp->bundle_promotion_products()->get()->count() > 0)
                                <div class="alert alert-secondary" style="font-size: 17px;">
                                    <span class="pcoded-badge label label-info rounded-circle">{{ $key }}</span>
                                    Bundle  has <b>{{ $bp->bundle_promotion_products()->get()->count() }}</b> product items <br>
                                    <ul>
                                        @foreach ($bp->bundle_promotion_products()->get() as $bpp)
                                        <?php $product_combination = \App\Models\Product_combination::where('id',$bpp->product_combination_id)->select('id','combination_string','qty')->get();?>
                                        <li> {{ $bpp->product->title }},
                                            @foreach ($product_combination as $comb)
                                                @foreach (explode('~',$comb->combination_string) as $string)
                                                <?php $option = \App\Models\Variation_option::where('origin',$string)->select('variation_id')->first();?>
                                                    <small class="badge badge-secondary">{{ $option->variation->title }}: {{ $string }}</small>
                                                @endforeach
                                            @endforeach
                                        </li>
                                        @endforeach
                                    </ul>
                                    <a href="javaScript:;" class="text-white f-12 removeBundle badge badge-danger float-right" sytle="margin-top:-20px;" id="{{ $bp->id }}">Delete bundle</a>
                                </div>
                                <?php $key =$key + 1;?>
                            @endif
                        @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
    $(function(){
        $('.remove').on('click', function(){
            var id = $(this).attr('id');
            $.get( url+"/common/ad/promotion/remove-temp-bundles/"+id, function( data ) {
                $('.row'+id).remove();
                $( ".bundleItems" ).load( "/common/ad/promotion/temp-bundles/{{ $promotion->id }}");
            })
        });

        $("#saveBundle").submit(function(event) {
            event.preventDefault();
            $(".createBundleBtn").html(' Loading...');$('#BundleResult').html('');
            $(".createBundleBtn").prop('disabled',true);
            var form = $(this);var url = form.attr('action');
            var html = '';
            $.ajax({
                url:url, method:"post", data: new FormData(this),
                contentType: false,cache:false, processData: false,
                dataType:"json",
                success:function(data){
                    if(data[0]=='success'){
                        $("#BundleResult").html(data[1]);
                        $( ".bundleItems" ).load( "/common/ad/promotion/temp-bundles/{{ $promotion->id }}");
                    }else $("#BundleResult").html(data[1]);

                    $(".createBundleBtn").html('');
                    $(".createBundleBtn").prop('disabled',false);
                }
            });
        });

        $('.removeBundle').on('click',function(){
            var id = $(this).attr('id');
            alert(id)
        });


        $('.viewBundles').on('click',function(){
            $('.promotionBundles').css('display','block');
            $('.addBundle').css('display','none');
            $('#addToBundle').css('display','none');
            $('.viewselected').css('display','block');
            $(this).css('display','none')
        })

        $('.viewselected').on('click',function(){
            $('.promotionBundles').css('display','none')
            $('.addBundle').css('display','block');
            $('#addToBundle').css('display','block');
            $('.viewBundles').css('display','block');
            $(this).css('display','none')
        })

    })
</script>
