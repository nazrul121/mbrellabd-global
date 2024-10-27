
<div class="container">
    <div class="row">
        @foreach (array_chunk($categories->toArray(), 2) as $i=>$chunk)
         @foreach ($chunk as $key=>$cat)   
            <div class="col-lg-6 col-md-6 col-6 aos-init aos-animate" data-aos="fade-up" data-aos-duration="700">
                <div class="article-card bg-transparent p-0 shadow-none">
                    
                    <a class="article-card-img-wrapper" href="{{ route('group',[app()->getLocale(),$cat['slug']]) }}">
                        <img style="width:100%" src="{{ url('storage').'/'.$cat['photo'] }}" alt="{{$cat['title']}}" class="article-card-img rounded">
                        <span class="article-tag-absolute rounded p-3">{{$cat['title']}}</span>
                    </a>  
                    {{-- <h2 class="article-card-heading heading_18">
                        <a class="heading_18" href="article.html">  Pure is the most furniture.  </a>
                    </h2> --}}
                </div>
            </div>
            @endforeach
        @endforeach
    </div>
</div>
