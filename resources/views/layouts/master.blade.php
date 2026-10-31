@php $lang=app()->getLocale(); @endphp
<!DOCTYPE html>
<html lang="{{ $lang }}" dir="{{ $lang == 'ar' ? 'rtl' : 'ltr' }}">

    <head>

        <!-- Required Meta Tags -->
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Bootstrap CSS (5.1.3): the RTL build for Arabic, the LTR build otherwise -->
        @if($lang == 'ar')
        <link rel="stylesheet" href="{{ asset('css/bootstrap.rtl.min.css') }}">
        @else
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
        @endif
        <!-- Animate Min CSS -->
        <link rel="stylesheet" href="{{ asset('css/animate.min.css') }}">
        <!-- Flaticon CSS -->
        <link rel="stylesheet" href="{{ asset('fonts/flaticon.css')  }}">
        <!-- BoxIcons Min CSS -->
        <link rel="stylesheet" href="{{ asset('css/boxicons.min.css') }}">
        <!-- Owl Carousel Min CSS -->
        <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}">

        <link rel="stylesheet" href="{{ asset('css/owl.theme.default.min.css')  }}">
        <!-- Magnific Popup CSS -->
        <link rel="stylesheet" href="{{ asset('css/magnific-popup.min.css')  }}">
        <!-- Odometer CSS -->
        <link rel="stylesheet" href="{{ asset('css/odometer.min.css')  }}">
        <!-- Meanmenu CSS -->
        <link rel="stylesheet" href="{{ asset('css/meanmenu.min.css')  }}">
        <!-- Style CSS -->
        <link rel="stylesheet" href="{{ asset('css/style.css') }}">
        <!-- Responsive CSS -->
        <link rel="stylesheet" href="{{ asset('css/responsive.css')  }}">

        @if(app()->getLocale() == 'ar')
        <!-- RTL CSS -->
        <link rel="stylesheet" href="{{ asset('css/rtl.css')  }}">
        @endif

        @php
            // Each page sets page_title and, where it has its own, meta_description and og_image.
            // Section values arrive HTML-escaped; decode them so {{ }} escapes them exactly once.
            $section = fn (string $name) => trim(html_entity_decode($__env->yieldContent($name), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
            $pageTitle = $section('page_title');
            $title = $pageTitle !== '' ? $pageTitle.' | Just Lite' : __('translation.meta-title');
            $description = $section('meta_description') ?: __('translation.meta-description');
            $siteUrl = rtrim(config('site.url'), '/');
            $ogImage = $siteUrl.parse_url($section('og_image') ?: '/images/just-lite.png', PHP_URL_PATH);
            $canonical = \App\Support\SiteLocale::canonical();
        @endphp
        <title>{{ $title }}</title>
        <meta name="description" content="{{ $description }}">

        <link rel="canonical" href="{{ $canonical }}">
        @foreach(config('app.available_locales') as $locale)
        <link rel="alternate" hreflang="{{ $locale }}" href="{{ \App\Support\SiteLocale::canonical($locale) }}">
        @endforeach
        <link rel="alternate" hreflang="x-default" href="{{ \App\Support\SiteLocale::canonical(\App\Support\SiteLocale::DEFAULT) }}">

        <meta property="og:site_name" content="Just Lite">
        <meta property="og:type" content="{{ trim($__env->yieldContent('og_type')) ?: 'website' }}">
        <meta property="og:locale" content="{{ $lang == 'ar' ? 'ar_AR' : 'en_US' }}">
        <meta property="og:locale:alternate" content="{{ $lang == 'ar' ? 'en_US' : 'ar_AR' }}">
        <meta property="og:title" content="{{ $title }}">
        <meta property="og:description" content="{{ $description }}">
        <meta property="og:url" content="{{ $canonical }}">
        <meta property="og:image" content="{{ $ogImage }}">
        <meta name="twitter:card" content="summary_large_image">
        <meta name="twitter:title" content="{{ $title }}">
        <meta name="twitter:description" content="{{ $description }}">
        <meta name="twitter:image" content="{{ $ogImage }}">

        @stack('structured-data')

        @yield('head')

        @section('seo')

        @show

        @stack('css')

        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('images/favicon.png')  }}">

        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer','GTM-MNGKCWV');</script>
        <!-- End Google Tag Manager -->

    </head>

<body>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MNGKCWV"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
    <!-- Preloader -->
    <div class="preloader">
        <div class="spinner">
            <div class="dot1"></div>
            <div class="dot2"></div>
        </div>
    </div>
    <!-- Preloader End -->
    @include('layouts.header.index')
    <div id="app">
        @yield('content-wrapper')
    </div>

    @section('footer')

    @include('layouts.footer.index')

    <!-- Modal Start -->
    <div class="modal fade fade-scale searchmodal" id="searchmodal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-bs-dismiss="modal">
                        <i class='bx bx-x'></i>
                    </button>
                </div>
                <div class="modal-body">
                    <form class="modal-search-form">
                        <input type="search" class="search-field" placeholder="Search...">
                        <button type="submit"><i class='bx bx-search-alt'></i></button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal End -->

    <!-- Jquery Min JS -->
    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <!-- Bootstrap Bundle Min JS -->
    <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    <!-- Magnific Popup JS -->
    <script src="{{ asset('js/magnific-popup.min.js') }}"></script>
    <!-- Odometer JS -->
    <script src="{{ asset('js/odometer.min.js') }}"></script>
    <!-- Appear Min JS -->
    <script src="{{ asset('js/appear.min.js') }}"></script>
    <!-- Meanmenu JS -->
    <script src="{{ asset('js/meanmenu.min.js') }}"></script>
    <!-- Owl Carousel JS -->
    <script src="{{ asset('js/owl.carousel.min.js') }}"></script>
    <!-- Wow Min JS -->
    <script src="{{ asset('js/wow.min.js') }}"></script>
    <!-- Form Validator Min JS -->
    <script src="{{ asset('js/form-validator.min.js') }} "></script>
    <!-- Contact Form JS -->
    <script src="{{ asset('js/contact-form-script.js') }} "></script>
    <!-- Custom  JS -->
    <script src="{{ asset('js/custom.js') }} "></script>
    <!-- Public forms (contact page, booking modal) -->
    <script src="{{ asset('js/forms.js') }}"></script>

    @show

    @stack('scripts')


</body>

</html>