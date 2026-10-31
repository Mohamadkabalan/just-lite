<!-- Start Navbar Area -->
<div class="navbar-area">
    <div class="mobile-responsive-nav">
        <div class="container">
            <div class="mobile-responsive-menu">
                <div class="logo">
                    <a href="{{ localized_route('home') }}">
                        <img src="{{ asset('images/logo.png') }}" alt="logo">
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Menu For Desktop Device -->
    <div class="desktop-nav desktop-nav-one nav-area">
        <div class="container">
            <nav class="navbar navbar-expand-md navbar-light ">
                <a class="navbar-brand" href="{{ localized_route('home') }}">
                    <img style="max-width: 160px;" src="{{ asset('images/logo.png') }}" alt="Logo">
                </a>
                <div class="collapse navbar-collapse mean-menu" id="navbarSupportedContent">
                    <ul class="navbar-nav m-auto">
                        <li class="nav-item">
                            <a href="{{ localized_route('home') }}" class="nav-link @if($page=='home') active @endif">
                                {{ __('translation.home') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ localized_route('about') }}" class="nav-link @if($page=='about') active @endif">
                                {{ __('translation.about-us') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ localized_route('testimonials') }}" class="nav-link @if($page=='testimonials')  active @endif" >
                                {{ __('translation.testimonials') }}
                            </a>
                        </li>
{{--                        <li class="nav-item">
                            <a href="/shop" class="nav-link  @if($page=='testimonials')  active @endif">
                                {{ __('translation.shop') }}
                            </a>
                        </li>--}}
                        {{--<li class="nav-item">
                            <a href="{{ localized_route('services.index') }}" class="nav-link  @if($page=='services')  active @endif">
                                {{ __('translation.services') }}
                            </a>
                        </li>--}}
                        <li class="nav-item">
                            <a href="{{ localized_route('blogs.index') }}" class="nav-link  @if($page=='blogs')  active @endif">
                                {{ __('translation.blogs') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ localized_route('contact-us') }}" class="nav-link  @if($page=='contact-us')  active @endif">
                                {{ __('translation.contact-us') }}
                            </a>
                        </li>
                    </ul>
                    @if($page=='home')
                    <div class="others-options d-flex align-items-center">
                        <div class="optional-item">
                            <a href="#pricing-area" class="default-btn two">{{ __('translation.book-an-appointment') }}</a>
                        </div>
                    </div>
                    @endif
                </div>
            </nav>
        </div>
    </div>
</div>
<!-- End Navbar Area -->