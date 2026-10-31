@php $about =\App\About::first();  @endphp
@if($about)
{{-- An image uploaded in the admin replaces the stylesheet's default photo (images/about/about-bg.jpg). --}}
@if($about->image)
<style>
    .about-area::before {
        background-image: url({{ \App\Support\VoyagerImage::url($about->image) }});
    }
</style>
@endif
<!-- About Area -->
<div class="about-area pt-100 pb-70">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <div class="about-content">
                    <div class="section-title">
                        <span>{{ __('translation.about-us')  }}</span>
                        <h2>@if($lang=='en') {{$about->title_en}} @else {{$about->title_ar}}  @endif</h2>
                        <p>
                            @if($lang=='en') {{$about->text_en}} @else {{$about->text_ar}}  @endif
                        </p>
                    </div>

                    @php $nutritionSpecialFeatures =\App\NutritionSpecialFeature::all(); $avg=intval($nutritionSpecialFeatures->count() / 2); @endphp
                    @if($nutritionSpecialFeatures->isNotEmpty())
                    <h3><span>{{ __('translation.nutrition-special-feature')  }}</span></h3>
                    <div class="row">
                        <div class="col-lg-6 col-sm-6">
                            <div class="about-list">
                                <ul>
                                    @for($i=0;$i <$avg;$i++)
                                    <li><i class='bx bx-chevrons-right'></i> @if($lang=='en') {{$nutritionSpecialFeatures[$i]->title_en}} @else {{$nutritionSpecialFeatures[$i]->title_ar}}  @endif</li>
                                    @endfor
                                </ul>
                            </div>
                        </div>

                        <div class="col-lg-6 col-sm-6">
                            <div class="about-list">
                                <ul>
                                    @for($i=$avg;$i< $nutritionSpecialFeatures->count();$i++)
                                        <li><i class='bx bx-chevrons-right'></i> @if($lang=='en') {{$nutritionSpecialFeatures[$i]->title_en}} @else {{$nutritionSpecialFeatures[$i]->title_ar}}  @endif</li>
                                    @endfor
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-lg-6">
                <div class="about-play-btn">
                    <a href="{{ $about->video }}" class="play-btn" aria-label="{{ __('translation.play-video') }}">
                        <i class='bx bxs-right-arrow' aria-hidden="true"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="about-shape">
        <img src="{{ asset('images/about/about-bg-shape.png')  }}" alt="About Images">
        <img src="{{ asset('images/shape/shape1.png')  }}" alt="About Images">
        <img src="{{ asset('images/shape/shape2.png')  }}" alt="About Images">
        <img src="{{ asset('images/shape/shape3.png') }} " alt="About Images">
    </div>
</div>
<!-- About Area End -->
@endif