@php $lang=app()->getLocale(); @endphp
<!DOCTYPE html>
<html   @if($lang == 'ar') lang="ar" dir="rtl" @else lang="en"  @endif>

    <head>

        <!-- Required Meta Tags -->
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <!-- Bootstrap RTL CSS -->
        <link rel="stylesheet" href="{{ asset('css/bootstrap.rtl.min.css') }}">
        <!-- Animate Min CSS -->
        <link rel="stylesheet" href="{{ asset('css/animate.min.css') }}">
        <!-- Flaticon CSS -->
        <link rel="stylesheet" href="{{ asset('fonts/flaticon.css')  }}">
        <!-- BoxIcons Min CSS -->
        <link rel="stylesheet" href="{{ asset('css/boxicons.min.css') }}">
        <!-- Owl Carousel Min CSS -->
        <link rel="stylesheet" href="{{ asset('css/owl.carousel.min.css') }}">

        <link rel="stylesheet" href="{{ asset('css/owl.theme.default.min.css')  }}">
        <!-- Jquery Ui CSS -->
        <link rel="stylesheet" href="{{ asset('css/jquery-ui.min.css')  }}">
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

        <title>Just Lite |
        @hasSection('page_title')
        @yield('page_title')
        @endif
        </title>

        <link rel="alternate" hreflang="en" href="https://just-lite.com/">
        <link rel="alternate" hreflang="ar" href="https://just-lite.com/">
        <link rel="canonical" href="https://just-lite.com/">
        <meta property="og:locale" content="en_US">
        <meta property="og:type" content="website">
        <meta property="og:title" content="Home - Just Lite">
        <meta property="og:description" content="Welcome to the first step toward your healthy life journey Hello, my name is Layla Hanna. I am a registered dietitian, personal trainer and certified sport dietitian. My goal is to help you achieve a healthy lifestyle to reach your goal without any restrictions or food deprivation.">
        <meta property="og:url" content="https://just-lite.com/">
        <meta property="og:site_name" content="Just Lite">
        <meta property="article:modified_time" content="2021-10-21T09:37:54+00:00">
        <meta property="og:image" content="https://www.just-lite.com/images/just-lite.png">

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
    <!-- Jquery Ui JS -->
    <script src="{{ asset('js/jquery-ui.min.js') }} "></script>
    <!-- Ajaxchimp Min JS -->
    <script src="{{ asset('js/ajaxchimp.min.js') }}"></script>
    <!-- Form Validator Min JS -->
    <script src="{{ asset('js/form-validator.min.js') }} "></script>
    <!-- Contact Form JS -->
    <script src="{{ asset('js/contact-form-script.js') }} "></script>
    <!-- Custom  JS -->
    <script src="{{ asset('js/custom.js') }} "></script>

    @show

    @stack('scripts')


</body>

</html>