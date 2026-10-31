{{--
    Every error page (404, 500, maintenance...). It must render even when the
    site is broken, so it uses no database, no session and no JavaScript: only
    the site's CSS, logo and translations. The language comes from the URL
    (/en/... is English), because a 404 never reaches the locale middleware.
--}}
@php
    $code = trim($__env->yieldContent('code'));
    $lang = request()->is('admin', 'admin/*') ? app()->getLocale() : \App\Support\SiteLocale::fromRequest(request());
    $key = \Illuminate\Support\Facades\Lang::has("errors.$code", $lang) ? $code : substr($code, 0, 1).'xx';
    $title = __("errors.$key.title", [], $lang);
    $message = __("errors.$key.message", [], $lang);
    $home = \App\Support\SiteLocale::localizedPath('/', $lang);
@endphp
<!DOCTYPE html>
<html lang="{{ $lang }}" dir="{{ $lang == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex">
    <title>{{ $title }} | Just Lite</title>
    <link rel="stylesheet" href="{{ asset($lang == 'ar' ? 'css/bootstrap.rtl.min.css' : 'css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/boxicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">
    @if($lang == 'ar')
    <link rel="stylesheet" href="{{ asset('css/rtl.css') }}">
    @endif
    <link rel="icon" type="image/png" href="{{ asset('images/favicon.png') }}">
    <style>
        .error-page { min-height: 100vh; display: flex; align-items: center; padding: 40px 0; background: #f5faf2; }
        .error-page .error-code { font-size: 96px; line-height: 1; font-weight: 700; color: var(--greenColor); margin: 28px 0 8px; }
        .error-page h1 { font-size: 32px; margin-bottom: 14px; }
        .error-page p { font-size: 18px; max-width: 560px; margin: 0 auto 28px; }
        .error-page .error-actions { display: flex; flex-wrap: wrap; gap: 12px; justify-content: center; }
        .error-page .error-actions a { min-height: 44px; display: inline-flex; align-items: center; gap: 8px; }
    </style>
</head>
<body>
    <main class="error-page">
        <div class="container text-center">
            <a href="{{ $home }}"><img src="{{ asset('images/logo.png') }}" alt="Just Lite" width="160" height="120" style="width: 160px; height: auto;"></a>
            <div class="error-code">{{ $code }}</div>
            <h1>{{ $title }}</h1>
            <p>{{ $message }}</p>
            <div class="error-actions">
                <a href="{{ $home }}" class="default-btn">{{ __('errors.home', [], $lang) }}</a>
                <a href="https://api.whatsapp.com/send?phone={{ ltrim(config('site.phone'), '+') }}" class="default-btn two" rel="noopener" target="_blank">
                    <i class="bx bxl-whatsapp" aria-hidden="true"></i>{{ __('errors.whatsapp', [], $lang) }}
                </a>
            </div>
        </div>
    </main>
</body>
</html>
