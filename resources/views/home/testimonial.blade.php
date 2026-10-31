@php $testimonials =\App\Testimonial::all();  @endphp
@if($testimonials->isNotEmpty())
<!-- Testimonials Area -->
<div class="testimonials-area ptb-100">
    <div class="container">
        <div class="testimonials-slider owl-carousel owl-theme">
            @foreach($testimonials as $testimonial)
            <div class="testimonials-item">
                <div class="section-title text-center">
                    <span>{{ __('translation.testimonials') }}</span>
                </div>
                <i class='bx bxs-quote-left'></i>
                <p>
                    @if($lang=='en') {{$testimonial->text_en}} @else {{$testimonial->text_ar}}  @endif
                </p>

                @php $testimonialName = $lang == 'en' ? $testimonial->title_en : $testimonial->title_ar; @endphp
                @if(filled($testimonialName))
                <div class="content">
                    <p class="testimonial-name">{{ $testimonialName }}</p>
                </div>
                @endif
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- Testimonials Area End -->
@endif
