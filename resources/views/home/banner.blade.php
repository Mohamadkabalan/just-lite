@php $banners = \App\Banner::all();  @endphp
@if($banners->isNotEmpty())
    {{--<div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
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
                    <h2 class="banner-title" >@if($lang=='en') {{$banner->title_en}} @else {{$banner->title_ar}}  @endif</h2>
                    <h3 class="banner-text">@if($lang=='en') {{$banner->text_en}} @else {{$banner->text_ar}}  @endif</h3>
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
    </div>--}}

    <!-- Banner Area -->
    <div class="banner-slider-area">
        <div class="banner-slider owl-carousel owl-theme">
            @foreach($banners as $key => $banner)
            <div class="banner-item">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <div class="banner-item-content">
                                <h2>@if($lang=='en') {{$banner->title_en}} @else {{$banner->title_ar}}  @endif</h2>
                                <p>@if($lang=='en') {{$banner->text_en}} @else {{$banner->text_ar}}  @endif</p>
                            </div>
                        </div>

                        <div class="col-lg-6 pe-0">
                            <div class="banner-item-img">
                                {{-- The originals are 1020px PNGs of about 1 MB; phones and tablets get Voyager's 510px copy. --}}
                                <img src="{{ \App\Support\VoyagerImage::url($banner->image) }}"
                                     srcset="{{ \App\Support\VoyagerImage::url($banner->image, 'medium') }} 510w, {{ \App\Support\VoyagerImage::url($banner->image) }} 1020w"
                                     sizes="(max-width: 991px) 100vw, 620px" alt="Banner Images">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="banner-shape-two">
            <img src="{{ asset('images/shape/shape1.png')  }} " alt="Banner Images">
            <img src="{{ asset('images/shape/shape2.png')  }} " alt="Banner Images">
            <img src="{{ asset('images/shape/shape3.png')  }}" alt="Banner Images">
        </div>
    </div>
    <!-- Banner Area End -->












@endif
