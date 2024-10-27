<div class="chawkbazar-filter-widgets flex-shrink-0 md:pr-8 lg:pr-20 xl:pr-24 md:w-75 lg:w-80 xl:w-96 hidden lg:block">
    <div class="block border-b border-gray-300 pb-7 mb-7">
        <div class="sidebar-header flex items-center justify-between mb-2.5 chawkbazar-open-mobile-filter">
            <h2 class="font-semibold text-heading text-2xl mb-0">Filters</h2>
        </div>
    </div>

    <div id="chawkbazar-filter-form">

        <section class="chawkbazar-filter-widget border-b border-solid border-gray-300 pb-7">
            <h4 class="font-semibold md:text-base text-sm">{{ $title }}</h4>
            <div class="checkbox-group flex flex-col space-y-2">
                <div class="variantScroll ">
                    @foreach ($categories as $group)
                        @if($group !=null)
                        <label class="inline-flex items-center filter-item rounded transition duration-300 ease-in-out checkCategory">
                            <input name="group_id" @if(request()->get('category') !='' && strpos( str_replace(' ','-',request()->get('category') ), $group->id.'-'.$group->slug ) !== false) checked @endif
                            value="{{ $type.'-'.$group->id.'-'.$group->slug }}" type="checkbox" class="filter-checkbox form-checkbox h-5 w-5 text-gray-600 " />
                            <span class="text-gray-700 cursor-pointer"> {{ $group->title }}  <svg style="margin-top:5px;color:#cc8f21;" stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 512 512" height="1em" width="1em"> <path d="M294.1 256L167 129c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.3 34 0L345 239c9.1 9.1 9.3 23.7.7 33.1L201.1 417c-4.7 4.7-10.9 7-17 7s-12.3-2.3-17-7c-9.4-9.4-9.4-24.6 0-33.9l127-127.1z"></path> </svg>
                            <?php $inner_group_ids = \App\Models\Product_season::where(['group_id'=>$group->id,'season_id'=>$season->id])->select('inner_group_id')->distinct('inner_group_id')->get()->toArray();
                                $innerGroups = \App\Models\Inner_group::whereIn('id',$inner_group_ids)->select('title')->get();
                            ?>
                            @if($inner_group_ids !=null)
                                @foreach($innerGroups as $key => $sub)  {{ $sub->title }} @endforeach
                            @endif
                        </label>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>

        <section class="chawkbazar-filter-widget pb-5 mt-5">
            @foreach ($variations as $key=>$variation)
            <div class="variantScroll" @if($key !=0) style="margin-top:1.5em;" @endif>
                <h4 class="font-semibold md:text-base text-sm">{{ $variation->title }}</h4>
                @foreach ($variation->variation_options()->orderBy('title','asc')->get() as $row)
                <?php $checkVo = \App\Models\Product_variation_option::where('variation_option_id',$row->id)->whereIn('product_id',$product_ids);?>
                @if($checkVo->count()>0)
                <div style="width:100%;float: left;">
                    <label class="inline-flex items-center filter-item rounded transition duration-300 ease-in-out colors">
                        <input name="color_input" @if (str_contains(request()->get('color'), $row->title))checked @endif value="{{ $row->id.'-'.$row->title }}" type="checkbox" class="filter-checkbox form-checkbox h-5 w-5 text-gray-600" /> &nbsp;
                        <span class="cursor-pointer">
                            <span class="chawkbazar-filter-color-preview mr-3 mt-0.5" style="@if(strpos(strtolower($row->title),'white') !== false || strpos(strtolower($row->title),'multi') !== false)border:2px solid #113c41 @else background:{{$row->code}} @endif"></span> {{ $row->title }}</span>
                    </label>
                </div> @endif
                @endforeach
            </div>
            @endforeach
        </section>

        <div class="block border-b border-gray-300 pb-5 mb-3 mt-5">
            <h3 class="text-heading text-sm md:text-base font-semibold">Price Range</h3>
            <div class="flex flex-col">
                <div class="price-box" style="text-align: center">
                    <p><b style="font-size:25px">{{ Session::get('user_currency')->currencySymbol }}</b> <span style="font-size:25px" class="priceRange"></span></p>
                    <div id="" class="price price-range"> </div>
                    <a href="#" class="priceHref"> <button class="pk-subscribe-submit" type="submit">Search with price</button></a>
                </div>
            </div>
        </div>

    </div>
</div>

<style>
    .price-box {width: 90%; margin: 25px auto}
    .price-box label, input {border: none; display: inline-block; margin-right: -4px; vertical-align: top; width: 30%}
    .price-box input {width:100%}
    .price {margin: 25px 0}
    .variantScroll{ max-height:300px;overflow-y:scroll }

    .variantScroll::-webkit-scrollbar {
        width:8px;
    }
    .variantScroll::-webkit-scrollbar-thumb:hover {
        -webkit-box-shadow: inset 0 0 6px orange; width:50px;
    }
    .variantScroll::-webkit-scrollbar-track {
        -webkit-box-shadow:inset 0 0 6px rgba(110, 0, 0, 0.3);
        border-radius:5px;
    }
    .variantScroll::-webkit-scrollbar-thumb {
        border-radius:5px;
        -webkit-box-shadow: inset 0 0 6px #113c40;
    }
</style>

