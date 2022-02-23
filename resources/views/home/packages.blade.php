@php $packages = \App\Package::all();  @endphp
<!-- Pricing Area -->
<div class="pricing-area pt-100 pb-70">
    <div class="container">
        <div class="section-title text-center">
            <span>{{ __('translation.pricing-plan')  }}</span>
            <h2>{{ __('translation.choose-your-best-plan')  }}</h2>
        </div>

        <div class="row justify-content-center pt-45">
            @foreach($packages as $key => $package)
            <div class="col-lg-4 col-md-6">
                <div class="pricing-card">
                    <div class="pricing-title">
                        <h3>{{$package->price}} $</h3>
                        <span>@if($lang=='en') {{$package->name_en}} @else {{$package->name_ar}}  @endif</span>
                    </div>
                    <ul class="pt-4">
                        @if($lang=='en') {!! $package->features_en !!} @else {!! $package->features_ar !!}  @endif
                    </ul>
                    <div class="price-btn-area text-center">
                        <a href="javascript:;" class="default-btn">{{ __('translation.book-now')  }}</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="pricing-shape">
        <img src="{{ asset('images/shape/shape4.png') }}" alt="Pricing Shape">
    </div>
</div>
<!-- Pricing Area End -->
