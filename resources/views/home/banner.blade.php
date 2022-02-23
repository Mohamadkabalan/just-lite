@php $banners = \App\Banner::all();  @endphp
@if($banners)
    <!-- Banner Area -->
{{--    <div class="banner-area" style="background-image: url( {{ 'storage/'.str_replace('\\', '/', $banner->image) }});">
        <div class="container">
            <div class="banner-content wow fadeInUp" data-wow-delay="00ms" data-wow-duration="1000ms">
                <h1 class="wow fadeInLeft" data-wow-delay="100ms" data-wow-duration="1000ms">@if($lang=='en') {{$banner->title_en}} @else {{$banner->title_ar}}  @endif</h1>
                <p class="wow fadeInLeft" data-wow-delay="200ms" data-wow-duration="1000ms">@if($lang=='en') {{$banner->text_en}} @else {{$banner->text_ar}}  @endif</p>
                <a href="about.html" class="default-btn  wow fadeInLeft"  data-wow-delay="300ms" data-wow-duration="1200ms">{{ __('translation.learn-more') }}</a>
                <div class="banner-shape">
                    <img src="{{ asset('images/shape/shape1.png')  }}" alt="Banner Images">
                    <img src="{{ asset('images/shape/shape2.png')  }}" alt="Banner Images">
                    <img src="{{ asset('images/shape/shape3.png')  }}" alt="Banner Images">
                </div>
            </div>
        </div>
    </div>--}}
    <!-- Banner Area End -->
    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            @foreach($banners as $key => $banner)
            <button type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide-to="{{$key}}" class="@if($key==0) active @endif" aria-current="@if($key==0) true @endif" aria-label="Slide {{ $key + 1  }}"></button>
            @endforeach
        </div>
        <div class="carousel-inner">
            @foreach($banners as $key => $banner)
            <div class="carousel-item @if($key==0) active @endif">
                <img src=" {{ 'storage/'.str_replace('\\', '/', $banner->image) }}" class="d-block w-100" alt="{{$banner->title_en}}">
                <div class="carousel-caption d-none d-md-block">
                    <h2 class="text-white">@if($lang=='en') {{$banner->title_en}} @else {{$banner->title_ar}}  @endif</h2>
                    <h3 class="text-white">@if($lang=='en') {{$banner->text_en}} @else {{$banner->text_ar}}  @endif</h3>
                </div>
            </div>
            @endforeach
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
@endif
