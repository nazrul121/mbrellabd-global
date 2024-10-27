@extends('layouts.app')

<?php
    $max = \DB::table('products')->max('sale_price'); $min = 0;
    if($max <1) $max += 3;
    $viewType = \DB::table('settings')->where('type','variation-view-type')->pluck('value')->first();
    $listViewVariationId = \DB::table('settings')->where('type','variation-at-product-list')->pluck('value')->first();

    include_once(app_path('Helper/Sidebar.php'));
    $useragent=$_SERVER['HTTP_USER_AGENT'];
?>


@section('title',$promotion->title.' | '.request()->get('system_title'))

@push('meta')
    <meta property="og:url" content="{{url()->full()}}" />
    <meta property="og:type" content="website">
    <meta property="og:description" content="{{$promotion->title}}">
@endpush



@section('content')
    <div class="elementor elementor-2497">
        <div class="elementor-section-wrap">
            <section class="elementor-section elementor-top-section elementor-element elementor-element-256da09 elementor-section-full_width elementor-section-height-default elementor-section-height-default">
                <div class="elementor-container elementor-column-gap-no">
                    <div class="elementor-column elementor-col-100 elementor-top-column elementor-element elementor-element-07634cb" data-id="07634cb" data-element_type="column">
                        <div class="elementor-widget-wrap elementor-element-populated">
                            <div class="elementor-element elementor-element-3846c23 elementor-widget elementor-widget-cbh_product_listing">
                                <div class="elementor-widget-container">
                                    <!-- filter widget for mobile -->
                                    @if(preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
                                        @include('includes.product.promo.mobile-filter')
                                    @endif

                                    <div class="flex mb-11 md:mb-12 lg:mb-14 2xl:mb-16 pt-8">
                                        {{-- left sitebar --}}
                                        @if(!preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i',$useragent)||preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i',substr($useragent,0,4)))
                                            @include('includes.product.promo.filter')
                                        @endif

                                        <div class="w-full lg:-ml-9">
                                            <div class="flex justify-between items-center mb-7">
                                                <p>{{ $promotion->title }}</p>
                                                <p style="color:black" class="promoDetails"><i class="fa fa-link"></i> Details</p>
                                            </div>

                                            <?php $curdate = strtotime(date('Y-m-d H:i')); $expiry = strtotime($promotion->end_date.' '.$promotion->end_time); ?>
                                            @if($bundle_promotions->count() >0 && $curdate <= $expiry)
                                                <div id="chawkbazar-products-grid" class="grid grid-cols-2 sm:grid-cols-3 2xl:grid-cols-4 3xl:grid-cols-5 gap-x-3 lg:gap-x-5 xl:gap-x-7 gap-y-3 xl:gap-y-5 2xl:gap-y-8" style="position: relative; zoom: 1;">
                                                    @foreach ($bundle_promotions as $key=>$bp)
                                                        @if($bp->bundle_promotion_products()->get()->count() >0)
                                                            <div class="button-redq-woocommerce-quick-view chawkbazar-helium-product-card">
                                                                <div class="card-root box-border overflow-hidden transition duration-200 transform ease-linear flex flex-col bg-white rounded-md pr-0 pb-2 lg:pb-3">
                                                                    <div class="bg-gray-300 overflow-hidden mb-3 md:mb-3.5">
                                                                        <?php
                                                                            // dd($product->sale_price);
                                                                            $old = bundle_price($bp->id,'regular_price');
                                                                            $new =bundle_price($bp->id,'discount_price');
                                                                            $percent = ( ($old - $new) / $old) * 100;
                                                                            if(strtolower(\Session::get('user_currency')->name)=='bdt') $formatNumber = 2;
                                                                            else $formatNumber = 3;
                                                                        ?>
                                                                        @if($percent >0)<span class="onsale">{{ number_format($percent) }} % off</span> @endif
                                                                        <a href="#redq-quick-view-modal" class="group" data-product_id="{{ $bp->id }}" rel="modal:open">
                                                                            <img loading="lazy" width="696" height="896" src="{{ url('storage').'/'.$bp->photo }}" alt="{{ $bp->bundle_promotion_products()->get()->count() }} items" class="img{{ $bp->id }} rounded-md transition duration-200 ease-linear transform group-hover:scale-105">
                                                                        </a>
                                                                    </div>
                                                                    <div>
                                                                        <a href="#" target="_blank"><h4 class="text-heading product_title text-sm md:text-base font-semibold mb-1">  Bundle ({{ $bp->id }}) has <b>{{ $bp->bundle_promotion_products()->get()->count() }}</b> product items </h4></a>
                                                                        <p class="text-body product_content text-xs lg:text-sm leading-normal xl:leading-relaxed mb-0">

                                                                        <div class="text-heading product_price font-semibold text-base lg:text-lg mt-1.5 lg:mt-2.5">
                                                                            @if($new < $old )
                                                                                <del aria-hidden="true"><span class="woocommerce-Price-amount amount"> <bdi><span class="woocommerce-Price-currencySymbol">{{ Session::get('user_currency')->currencySymbol }}</span>{{ number_format($old,$formatNumber)}}</bdi></span></del>
                                                                            @endif
                                                                            <ins><span class="woocommerce-Price-amount amount"><bdi><span class="woocommerce-Price-currencySymbol"> {{ Session::get('user_currency')->currencySymbol }}</span>{{ number_format($new,$formatNumber) }} </bdi></span></ins>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    @endforeach

                                                    <div class="chawkbazar-product-search-loadmore text-center col-span-full">
                                                        <ul>
                                                            <li>{{$bundle_promotions->appends(request()->input())->links()}}</li>
                                                        </ul>
                                                    </div>
                                                </div>

                                                {{-- @foreach ($bundle_promotions as $key=>$bp)
                                                    <div class="alert alert-secondary"> <span class="pcoded-badge label label-info rounded-circle">{{ $key+1 }}</span>
                                                        Bundle  has <b>{{ $bp->bundle_promotion_products()->get()->count() }}</b> product items <br>
                                                        <ul>
                                                            @foreach ($bp->bundle_promotion_products()->get() as $bpp)
                                                                <li> {{ $bpp->product->title }} </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                @endforeach --}}
                                            @else
                                            <p style="width:100%;margin:auto">
                                                <center> <img src="{{ url('storage/images/not-found-alt.svg') }}" alt="no product found">
                                                <h3>The promotion <b style="color:#f5d8a4">{{ $promotion->title }} </b> is expired <b style="color:#f5d8a4">Or stock limied</b></h3></center>
                                            </p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>


    <div class="modal fade" id="chirtModal" tabindex="-1" aria-hidden="true" style="display: none;">
        <pre style="padding:0;margin:0px;background:none;">{{ $promotion->description }}</pre>
    </div>
@endsection
@push('style')
<link rel="stylesheet" id="elementor-post-2497-css" href="{{asset('assets/css/autoptimize_single_d7ee6d44fbf5ddc562ecccf7eb18303c.css')}}" type="text/css" media="all" />
<style>
    .button-redq-woocommerce-quick-view .onsale {
        position: absolute;  top: 15px;
        right: 15px; line-height: 1;  color: #fff;
        font-size: 12px;  background-color:#e29e1c;
        border-radius: 3px;   z-index: 1;
        pointer-events: none; left: auto; margin: 0;  min-height: 22px;
        min-width: auto; padding: 0 10px;
        display: -webkit-inline-box; display: -ms-inline-flexbox; display: inline-flex;
        -webkit-box-align: center; -ms-flex-align: center;align-items: center;
    }

</style>
@endpush

@push('scripts')
    <script>
        var cgory= "", colr ="", price=""; sorting="newest";
        let current_url = window.location.pathname;
        // alert(current_url);
        //category checkbox
        $('.checkCategory input[type=checkbox],.colors input[type=checkbox]').on('change',function(){
            var groups = get_groups();
            var colors = get_colors();
            // debugger
            cgory='?category='+groups;
            colr='&color='+colors;
            price='&price='+price;
            sorting='&sorting='+sorting;

            window.location = current_url+cgory+colr+price+sorting;
        });

        $('#sortBy').change( function() {
            var groups = get_groups();
            var colors = get_colors();

            sorting='&sorting='+ $(this).val()
            cgory='?category='+groups
            colr='&color='+colors
            price='&price='+price
            sorting='&sorting='+sorting
            window.location = current_url+cgory+colr+price+sorting;
        });

        $('#chawkbazar-open-mobile-filter').on('click',function(){
            $('#chawkbazar-filter-widgets-mobile').slideDown();
        });

        $('#chawkbazar-close-mobile-filter').on('click',function(){
            $('#chawkbazar-filter-widgets-mobile').slideUp();
        })

        function get_colors(){
            colors = [];
            $.each($("input[name='color_input']:checked"), function(el){
                colors.push($(this).val());
            }); return colors;
        }

        function get_groups(){
            groups = [];
            $.each($("input[name='group_id']:checked"), function(el){
                groups.push($(this).val());
            }); return groups;
        }

        $('.promoDetails').on('click',function(){
            // alert( $(this).html() )
            $('#chirtModal').modal('show');
            $('#chirtModal').css('top','-10%');
        })

    </script>
    @if (request()->price)
        <?php
            $num_amountMax = explode('-',request()->price)[1];
            $num_totalMax = $max;
            $count1Max = $num_amountMax / $num_totalMax;
            $count2Max = $count1Max * 100;
            $priceMaxPercent = number_format($count2Max, 0);
            // echo 'Current max value: '.$num_amountMax.', max: '.$num_totalMax.', divide: '.$num_amountMax .' with: '. $num_totalMax.' = '.$count2Max.', '.$count2Max.' x 100 = '.$priceMaxPercent.'%<br/>';

            $num_amountMin = explode('-',request()->price)[0];
            $count1Min = $num_amountMin / $num_totalMax;
            $count2Min = $count1Min * 100;
            $priceMinPercent = number_format($count2Min, 0);
            // echo 'Current max value: '.$num_amountMin.', max: '.$num_totalMax.', divide: '.$num_amountMin .' with: '. $num_totalMax.' = '.$count2Min.', '.$count2Min.' x 100 = '.$priceMinPercent.'%';
        ?>
        <script>
            setTimeout(function() {
                $('.ui-widget-header').css('width','{{  $priceMaxPercent-$priceMinPercent }}%');
                $('.ui-state-default:nth-child(3n)').css('left','{{ $priceMaxPercent }}%');

                $('.ui-widget-header').css('left','{{  $priceMinPercent }}%');
                $('.ui-state-default:nth-child(2n)').css('left','{{ $priceMinPercent }}%');
                $('.priceRange').text('{{ request()->price }}');
            }, 500);

        </script>
    @endif
    <script>
        $(function() {
            let minValue = parseInt("{{ $min }}");
            let maxValue = parseInt("{{ $max }}");
            // alert(minValue+' = '+maxValue);
            $(".price-range").slider({
                step: 1, range: true,
                min:minValue,   max:maxValue,
                values: [0, maxValue],
                slide: function(event, ui){
                    $(".priceRange").text(ui.values[0] + "-" + ui.values[1]);

                    var groups = get_groups();
                    var colors = get_colors();
                    // debugger
                    cgory='?category='+groups;
                    colr='&color='+colors;
                    price = '&price='+ ui.values[0] + "-" + ui.values[1];

                    // window.location =  current_url+cgory+colr+price;

                    $('.priceHref').attr('href', current_url+cgory+colr+price);
                }
            });
            $(".priceRange").text($(".price-range").slider("values", 0) + "-" + $(".price-range").slider("values", 1));
        });
    </script>
@endpush
