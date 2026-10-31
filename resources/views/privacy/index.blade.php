@php
    $lang = app()->getLocale(); $page = 'privacy';
    $digits = fn (string $s) => $lang == 'ar' ? strtr($s, ['0' => '٠', '1' => '١', '2' => '٢', '3' => '٣', '4' => '٤', '5' => '٥', '6' => '٦', '7' => '٧', '8' => '٨', '9' => '٩']) : $s;
    $replace = [
        'email' => config('site.email'),
        'phone' => __('translation.phone-info'),
        'months' => $digits((string) config('site.retention_months')),
    ];
@endphp
@extends('layouts.master')

@section('page_title', __('privacy.title'))
@section('meta_description', __('privacy.description'))

@section('content-wrapper')
    <!-- Inner Banner -->
    <div class="inner-banner inner-bg2">
        <div class="container">
            <div class="inner-title text-center">
                <h1>{{ __('privacy.title') }}</h1>
                <ul>
                    <li>
                        <a href="{{ localized_route('home') }}">{{ __('translation.home') }}</a>
                    </li>
                    <li>{{ __('privacy.title') }}</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Inner Banner End -->

    <div class="privacy-area pt-100 pb-70">
        <div class="container">
            <p class="privacy-updated">{{ __('privacy.updated') }}</p>
            <p>{{ __('privacy.intro') }}</p>

            @foreach(__('privacy.sections') as $section)
                <h2>{{ $section['heading'] }}</h2>
                @if(! empty($section['items']))
                    <ul>
                        @foreach($section['items'] as $item)
                            <li>{{ strtr($item, [':email' => $replace['email'], ':phone' => $replace['phone'], ':months' => $replace['months']]) }}</li>
                        @endforeach
                    </ul>
                @endif
                @foreach($section['paragraphs'] ?? [] as $paragraph)
                    <p>{{ strtr($paragraph, [':email' => $replace['email'], ':phone' => $replace['phone'], ':months' => $replace['months']]) }}</p>
                @endforeach
            @endforeach

            <p>
                <a href="#" class="default-btn" data-consent-reset>{{ __('translation.cookie-settings') }}</a>
            </p>
        </div>
    </div>
@endsection
