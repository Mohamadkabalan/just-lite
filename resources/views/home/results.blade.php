@php $results = \App\Result::all();  @endphp
@if($results)
<div class="faq-area pt-100 pb-70">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title text-center">
                    <h2>{{ __('translation.results')  }}</h2>
                </div>
            </div>
        </div>
        <div class="row pt-4">
            <div class="col-sm-6">
                <div id="carouselResults" class="carousel slide" data-bs-ride="carousel" style="max-width: 500px">
                    <div class="carousel-indicators">
                        @foreach($results as $key => $result)
                            <button type="button" data-bs-target="#carouselResults" data-bs-slide-to="{{$key}}" class="@if($key==0) active @endif" aria-current="@if($key==0) true @endif" aria-label="Slide {{ $key + 1  }}"></button>
                        @endforeach
                    </div>
                    <div class="carousel-inner">
                        @foreach($results as $key => $result)
                            <div class="carousel-item @if($key==0) active @endif">
                                <img src=" {{ 'storage/'.str_replace('\\', '/', $result->image) }}" class="d-block w-100" alt="{{$result->title_en}}">
                                <div class="carousel-caption d-none d-md-block">
                                    <h2 class="banner-title">@if($lang=='en') {{$result->title_en}} @else {{$result->title_ar}}  @endif</h2>
                                    <h3 class="banner-text">@if($lang=='en') {{$result->text_en}} @else {{$result->text_ar}}  @endif</h3>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselResults" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselResults" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="qodef-m-info p-5">
                    <h5 class="qodef-m-title">{!! __('translation.before-after') !!}  	</h5>
                    <p class="qodef-m-text">{{ __('translation.results-description')  }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endif