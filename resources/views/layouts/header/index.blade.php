<!-- Top Header Start -->
<div class="top-header top-header-bg">
    <div class="container">
        <div class="top-inner-bg">
            <div class="row align-items-center">
                <div class="col-lg-3 col-md-4">
                    <div class="header-left">
                        <p>{{ __('translation.welcome-to-just-lite') }} </p>
                    </div>
                </div>

                <div class="col-lg-9 col-md-8">
                    <div class="header-right d-flex align-items-center">
                        <div class="header-item">
                            <ul>
                                <li class="title">{{ __('translation.follow-us') }}:</li>
                                @include('partials.social-links', ['order' => ['facebook', 'instagram', 'linkedin']])
                            </ul>
                        </div>

{{--                        <div class="header-item">
                            <a href="cart.html" class="cart-top-btn cart-top-btn-two">
                                <i class='bx bx-shopping-bag'></i>
                                <span>0</span>
                            </a>
                        </div>--}}

                        <div class="header-item">
                            <div class="header-language dropdown language-option">
                                <button class="dropdown-toggle" type="button" id="language1" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <i class='bx bx-world' aria-hidden="true"></i>
                                    <span class="lang-name">{{ app()->getLocale() == 'en' ? 'English' : 'العربيّة' }}</span>
                                </button>
                                <div class="dropdown-menu language-dropdown-menu" aria-labelledby="language1">

                                    <a class="dropdown-item @if(app()->getLocale() == 'en') selected @endif" href="{{ \App\Support\SiteLocale::switchUrl('en') }}" hreflang="en" lang="en">
                                        <img src="{{ asset('images/uk.png') }}" alt="" width="20" height="20">
                                        English
                                    </a>

                                    <a class="dropdown-item @if(app()->getLocale() == 'ar') selected @endif" href="{{ \App\Support\SiteLocale::switchUrl('ar') }}" hreflang="ar" lang="ar">
                                        <img src="{{ asset('images/uae.png') }}" alt="" width="20" height="20">
                                        العربيّة
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Top Header End -->

@push('scripts')

@endpush
