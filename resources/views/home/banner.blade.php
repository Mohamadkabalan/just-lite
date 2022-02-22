@php $banner = \App\Banner::all()->first();  @endphp
@if($banner)
    <!-- Banner Area -->
    <div class="banner-area" style="background-image: url( {{ 'storage/'.str_replace('\\', '/', $banner->image) }});">
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
    </div>
    <!-- Banner Area End -->
@endif
