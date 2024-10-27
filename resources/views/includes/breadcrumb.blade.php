<!-- breadcrumb start -->
<div class="breadcrumb">
    <div class="container">
        <ul class="list-unstyled d-flex align-items-center m-0">
            <li><a href="{{route('home', app()->getLocale())}}">Home</a></li>
            <li>
                <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g opacity="0.4"> <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z" fill="#000" /></g>
                </svg>
            </li>
            @if(Request::segment(2)=='products')
                <li><a href="{{ url('products') }}">{{ Request::segment(1) }}</a> </li> @endif

            @if(Request::segment(2)=='group')
                <?php $group = \DB::table('groups')->where('slug',Request::segment(3))->select('id','title','slug')->first();?>
                @if($group !=null)
                <li><a href="{{ route('group',[app()->getLocale(), $group->slug]) }}">{{ $group->title }}</a></li> @endif
            @endif

        
            @if(Request::segment(2)=='group-in')
                <?php $inner = \App\Models\Inner_group::where('slug',Request::segment(3))->select('id','group_id','title','slug')->first();?>

                <li><a href="{{ route('group', [app()->getLocale(), $inner->group->slug]) }}">{{ $inner->group->title }}</a></li>
                <li>
                    <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g opacity="0.4"> <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z" fill="#000" /></g>
                    </svg>
                </li>
                <li><a href="{{ route('group-in',[app()->getLocale(), $inner->slug]) }}">{{ $inner->title }}</a></li>
            @endif

            @if(Request::segment(2)=='child-in')
                <?php $child = \App\Models\Child_group::where('slug',Request::segment(3))->select('id','inner_group_id','title','slug')->first();?>

                <li><a href="{{ route('group', [app()->getLocale(), $child->inner_group->group->slug]) }}">{{ $child->inner_group->group->title }}</a></li>
                <li>
                    <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g opacity="0.4"> <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z" fill="#000" /></g>
                    </svg>
                </li>
                <li><a href="{{ route('group-in',[app()->getLocale(), $child->inner_group->slug]) }}">{{ $child->inner_group->title }}</a></li>
                @if($child !=null)
                    <li>
                        <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <g opacity="0.4"> <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z" fill="#000" /></g>
                        </svg>
                    </li>
                    <li><a href="{{ route('child-in',[app()->getLocale(),$child->slug]) }}">{{ $child->title }}</a></li>
                @endif
            @endif

            
            @if(Request::segment(2)=='season-items')
                <?php $season = \App\Models\Season::where('slug',Request::segment(3))->first();
                    $group = \App\Models\Group::where('slug',Request::segment(3))->first();
                ?>
                <li><a href="#">{{ $season->title }}</a></li>
            @endif

            @if(Request::segment(2)=='season-group')
                <?php 
                    $season = \App\Models\Season::where('slug',Request::segment(3))->first();
                    $group = \App\Models\Group::where('slug',Request::segment(4))->pluck('title')->first();
                ?>
                <li><a href="{{ route('season-products',[app()->getLocale(), $season->slug])}}">{{ $season->title }}</a></li>
                <li>
                    <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g opacity="0.4"> <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z" fill="#000" /></g>
                    </svg>
                </li>
                <li><a href="{{ route('season-group', [app()->getLocale(), Request::segment(3), Request::segment(4)]) }}">{{ $group }}</a></li>
            @endif

            @if(Request::segment(2)=='season-group-in')
                <?php 
                    $season = \App\Models\Season::where('slug',Request::segment(3))->select('id','title','slug')->first();
                    $innerSeason = \App\Models\Inner_group_season::where('season_id',$season->id)->select('id','inner_group_id')->first();
                    $inner_group = \App\Models\Inner_group::where('slug',Request::segment(4))->select('group_id','title')->first();
                    $group = \App\Models\Group::where('id',$inner_group->group_id)->select('title','slug')->first();
                ?>
                <li><a href="{{route('season-products',[app()->getLocale(),$season->slug])}}">{{ $season->title }}</a></li>
                <li>
                    <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g opacity="0.4"> <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z" fill="#000" /></g>
                    </svg>
                </li>
                <li><a href="{{ route('season-group',[app()->getLocale(),$season->slug,$group->slug]) }}">{{ $group->title }}</a></li>
                <li>
                    <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g opacity="0.4"> <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z" fill="#000" /></g>
                    </svg>
                </li>
                <li><a href="#">{{$inner_group->title}}</a></li>
            @endif

            @if(Request::segment(2)=='season-child-in')
                <?php 
                    $season = \App\Models\Season::where('slug',Request::segment(3))->select('id','title','slug')->first();
                    $child_group = \App\Models\Child_group::where('slug',Request::segment(4))->select('slug','inner_group_id','title')->first();
                    $inner_group = \App\Models\Inner_group::where('id',$child_group->inner_group_id)->select('id','group_id','title','slug')->first();
                    $group = \App\Models\Group::where('id',$inner_group->group_id)->select('title','slug')->first();
                ?>
                <li><a href="{{ route('season-products',[app()->getLocale(), $season->slug]) }}">{{ $season->title }}</a></li>
              
                <li>
                    <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g opacity="0.4"> <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z" fill="#000" /></g>
                    </svg>
                </li>
                <li><a href="{{ route('season-group',[app()->getLocale(),$season->slug , $group->slug]) }}">{{ $group->title}}</a></li>
                
                <li>
                    <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g opacity="0.4"> <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z" fill="#000" /></g>
                    </svg>
                </li>

                <li><a href="{{ route('season-group-in',[app()->getLocale(),$season->slug,$inner_group->slug]) }}">{{ $inner_group->title}}</a></li>
                <li>
                    <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g opacity="0.4"> <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z" fill="#000" /></g>
                    </svg>
                </li>

                <li><a href="#">{{ $child_group->title}}</a></li>
            
            @endif


            @if(Request::segment(2)=='promotions')
                <li><a href="#">Promotions</a></li>
            @endif

            @if(Request::segment(2)=='promo-items')
                <?php $promotion = \App\Models\Promotion::where('slug',Request::segment(3))->select('id','title')->first();?>
                <li><a href="{{route('promotions', app()->getLocale())}}">Promotions</a></li>

                <li>
                    <svg class="icon icon-breadcrumb" width="64" height="64" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <g opacity="0.4"> <path d="M25.9375 8.5625L23.0625 11.4375L43.625 32L23.0625 52.5625L25.9375 55.4375L47.9375 33.4375L49.3125 32L47.9375 30.5625L25.9375 8.5625Z" fill="#000" /></g>
                    </svg>
                </li>
             
                <li><a href="#">{{ $promotion->title}}</a></li>
            @endif

            @if(Request::segment(2)=='highlight-products')
                <?php $highlight = \App\Models\Highlight::where('id',Request::segment(2))->select('id','title')->first();?>
                <li><a href="#">{{$highlight->title}}</a></li>
            @endif
        </ul>
    </div>
</div>

