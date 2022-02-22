@php $testimonials =\App\Testimonial::all();  @endphp
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

                <div class="content">
                    <h3>@if($lang=='en') {{$testimonial->title_en}} @else {{$testimonial->title_ar}}  @endif</h3>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
<!-- Testimonials Area End -->
