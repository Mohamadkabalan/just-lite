@php $lang=app()->getLocale(); @endphp
<!DOCTYPE html>
<html lang="{{ $lang }}" dir="{{ $lang == 'ar' ? 'rtl' : 'ltr' }}">

    <head>

        <!-- Required Meta Tags -->
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        {{-- CSS and JS use asset_versioned() (?v=file time): public/.htaccess lets
             browsers cache them for a year, and a changed file gets a new URL. --}}
        <!-- Bootstrap CSS (5.1.3): the RTL build for Arabic, the LTR build otherwise -->
        @if($lang == 'ar')
        <link rel="stylesheet" href="{{ asset_versioned('css/bootstrap.rtl.min.css') }}">
        @else
        <link rel="stylesheet" href="{{ asset_versioned('css/bootstrap.min.css') }}">
        @endif
        <!-- Flaticon CSS -->
        <link rel="stylesheet" href="{{ asset_versioned('fonts/flaticon.css') }}">
        <!-- BoxIcons Min CSS -->
        <link rel="stylesheet" href="{{ asset_versioned('css/boxicons.min.css') }}">
        <!-- Owl Carousel Min CSS -->
        <link rel="stylesheet" href="{{ asset_versioned('css/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ asset_versioned('css/owl.theme.default.min.css') }}">
        <!-- Magnific Popup CSS -->
        <link rel="stylesheet" href="{{ asset_versioned('css/magnific-popup.min.css') }}">
        <!-- Meanmenu CSS -->
        <link rel="stylesheet" href="{{ asset_versioned('css/meanmenu.min.css') }}">
        <!-- Style CSS -->
        <link rel="stylesheet" href="{{ asset_versioned('css/style.css') }}">
        <!-- Responsive CSS -->
        <link rel="stylesheet" href="{{ asset_versioned('css/responsive.css') }}">

        @if(app()->getLocale() == 'ar')
        <!-- RTL CSS -->
        <link rel="stylesheet" href="{{ asset_versioned('css/rtl.css') }}">
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

        @php
            // Analytics only with consent (cookie banner, public/js/consent.js).
            $consent = request()->cookie('site_consent');
            $gtmId = config('site.gtm_id');
        @endphp
        @if($gtmId && $consent === 'granted')
        <!-- Google Tag Manager -->
        <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
                    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
                j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
                'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
            })(window,document,'script','dataLayer',@json($gtmId));</script>
        <!-- End Google Tag Manager -->
        @endif

    </head>

<body data-slide-label="{{ __('translation.slide', ['number' => '%n']) }}">
@if($gtmId && $consent === 'granted')
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id={{ $gtmId }}"
                  height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
@endif
    <!-- Preloader -->
    <div class="preloader">
        <div class="spinner">
            <div class="dot1"></div>
            <div class="dot2"></div>
        </div>
    </div>
    <!-- Preloader End -->
    <a href="#main-content" class="skip-link visually-hidden-focusable">{{ __('translation.skip-to-content') }}</a>
    <header class="site-header">
        @include('layouts.header.index')
        @include('home.navbar')
    </header>
    <main id="main-content" tabindex="-1">
        @yield('content-wrapper')
    </main>

    @section('footer')

    @include('layouts.footer.index')

    @include('partials.consent-banner')

    <!-- Jquery Min JS -->
    <script src="{{ asset_versioned('js/jquery.min.js') }}"></script>
    <!-- Bootstrap Bundle Min JS -->
    <script src="{{ asset_versioned('js/bootstrap.bundle.min.js') }}"></script>
    <!-- Magnific Popup JS -->
    <script src="{{ asset_versioned('js/magnific-popup.min.js') }}"></script>
    <!-- Meanmenu JS -->
    <script src="{{ asset_versioned('js/meanmenu.min.js') }}"></script>
    <!-- Owl Carousel JS -->
    <script src="{{ asset_versioned('js/owl.carousel.min.js') }}"></script>
    <!-- Form Validator Min JS -->
    <script src="{{ asset_versioned('js/form-validator.min.js') }}"></script>
    <!-- Contact Form JS -->
    <script src="{{ asset_versioned('js/contact-form-script.js') }}"></script>
    <!-- Custom  JS -->
    <script src="{{ asset_versioned('js/custom.js') }}"></script>
    <!-- Public forms (contact page, booking modal) -->
    <script src="{{ asset_versioned('js/forms.js') }}"></script>
    <!-- Cookie consent: analytics and the Google map -->
    <script src="{{ asset_versioned('js/consent.js') }}"></script>

    @show

    @stack('scripts')


</body>

</html>