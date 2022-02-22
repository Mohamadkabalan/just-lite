<!-- Start Navbar Area -->
<div class="navbar-area">
    <div class="mobile-responsive-nav">
        <div class="container">
            <div class="mobile-responsive-menu">
                <div class="logo">
                    <a href="index.html">
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
                <a class="navbar-brand" href="/">
                    <img style="max-width: 160px;" src="{{ asset('images/logo.png') }}" alt="Logo">
                </a>
                <div class="collapse navbar-collapse mean-menu" id="navbarSupportedContent">
                    <ul class="navbar-nav m-auto">
                        <li class="nav-item">
                            <a href="/" class="nav-link @if($page=='home') active @endif">
                                {{ __('translation.home') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/about" class="nav-link @if($page=='about') active @endif">
                                {{ __('translation.about-us') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/testimonials" class="nav-link @if($page=='testimonials')  active @endif" >
                                {{ __('translation.testimonials') }}
                            </a>
                        </li>
{{--                        <li class="nav-item">
                            <a href="/shop" class="nav-link  @if($page=='testimonials')  active @endif">
                                {{ __('translation.shop') }}
                            </a>
                        </li>--}}
                        <li class="nav-item">
                            <a href="/services" class="nav-link  @if($page=='services')  active @endif">
                                {{ __('translation.services') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/blogs" class="nav-link  @if($page=='blogs')  active @endif">
                                {{ __('translation.blogs') }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="/contact-us" class="nav-link  @if($page=='contact-us')  active @endif">
                                {{ __('translation.contact-us') }}
                            </a>
                        </li>
                    </ul>
                    @if($page=='home')
                    <div class="others-options d-flex align-items-center">
                        <div class="optional-item">
                            <a href="#appointment-area" class="default-btn two">{{ __('translation.book-an-appointment') }}</a>
                        </div>
                    </div>
                    @endif
                </div>
            </nav>
        </div>
    </div>
</div>
<!-- End Navbar Area -->