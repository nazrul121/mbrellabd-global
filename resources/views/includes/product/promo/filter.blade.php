<div class="filter-widget">
    <div class="filter-header faq-heading heading_18 d-flex align-items-center justify-content-between border-bottom" data-bs-toggle="collapse" data-bs-target="#filter-collection">
        @if(strlen($title) >25){{ substr($title, 0, 25) }} ... @else {{$title}} @endif
        <span class="faq-heading-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" class="icon icon-down">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </span>
    </div>
    <div id="filter-collection" class="accordion-collapse collapse show">
        <ul class="filter-lists list-unstyled mb-0">
            @foreach ($groups as $group)
                @if($group !=null)
                <li class="filter-item">
                    <label class="filter-label checkCategory">
                        <input type="checkbox" name="group_id" @if(request()->get('category') !='' && strpos( str_replace(' ','-',request()->get('category') ), $group->id.'-'.$group->slug ) !== false) checked @endif
                            value="{{ $group->id.'-'.$group->slug }}" />
                        <span class="filter-checkbox rounded me-2"></span>
                        <span class="filter-text">{{ $group->title }}</span>
                    </label>
                </li>
                @endif
            @endforeach
        </ul>
    </div>
</div>

@foreach ($variations as $key=>$variation)
<div class="filter-widget">
    <div class="filter-header faq-heading heading_18 d-flex align-items-center justify-content-between border-bottom"
        data-bs-toggle="collapse" data-bs-target="#filter-availability{{$key}}"> {{ $variation->title }}
        <span class="faq-heading-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" class="icon icon-down">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </span>
    </div>
 
    <div id="filter-availability{{$key}}" class="accordion-collapse collapse show">
        <ul class="filter-lists list-unstyled mb-0 variantScroll">
            @foreach ($variation->variation_options()->orderBy('title','asc')->get() as $row)
            <?php $checkVo = \App\Models\Product_variation_option::where('variation_option_id',$row->id)->whereIn('product_id',$product_ids);?>
                @if($checkVo->count()>0)
                
                <li class="filter-item">
                    <label class="filter-label colors">
                        <input type="checkbox" name="color_input" @if (str_contains(request()->get('color'), $row->title))checked @endif value="{{ $row->id.'-'.$row->title }}"
                        value="{{ $row->id.'-'.$row->title }}" />
                        <span class="filter-checkbox rounded me-2" style="@if(strpos(strtolower(strtolower($variation->title)),'color') !== false)
                            border:2px solid @if(strpos(strtolower($row->title),'white') !== false || strpos(strtolower($row->code),'#fff') !== false) {{$row->code}} @else #113c41; @endif
                            background: @if(strpos(strtolower($row->title),'white') !== false || strpos(strtolower($row->code),'#fff') !== false)#113c41 @else {{$row->code}} @endif
                        @endif" ></span>
                        <span class="filter-text" style=" color: @if(strpos(strtolower($row->title),'white') !== false || strpos(strtolower($row->code),'#fff') !== false)#113c41 @else {{$row->code}} @endif">{{ $row->title }}</span>
                    </label>
                </li>
                @endif
            @endforeach
        </ul>
    </div> 
</div>
@endforeach


<div class="filter-widget">
    <div class="filter-header faq-heading heading_18 d-flex align-items-center justify-content-between border-bottom" data-bs-toggle="collapse" data-bs-target="#filter-price" aria-expanded="true">
        Price Range <span class="faq-heading-icon">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-down">
                <polyline points="6 9 12 15 18 9"></polyline>
            </svg>
        </span>
    </div>
    <div id="filter-price" class="accordion-collapse collapse show">
        <div class="filter-price d-flex align-items-center justify-content-between">
            <div class="filter-field">
                <input class="field-input minNumber" type="number" placeholder="{{Session::get('user_currency')->currencySymbol}} 0" min="0" value="{{$min}}" max="2000.00">
            </div>
            <div class="filter-separator px-3">To</div>
            <div class="filter-field">
                <input class="field-input maxNumber" type="number" min="0" placeholder="{{Session::get('user_currency')->currencySymbol.$max}}" value="{{$max}}" max="2000.00">
            </div>
        </div>
        <br>
        <a href="#" class="priceHref"> <button class="position-relative btn-atc " type="submit">Search with price</button></a>
    </div>
</div>
