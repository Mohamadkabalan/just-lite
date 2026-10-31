@php $lang=app()->getLocale(); $page='contact-us';  @endphp
@extends('layouts.master')

@section('page_title', __('translation.contact-us'))
@section('meta_description', __('translation.contact-description'))

@include('partials.business-schema')

@section('head')

@endsection

@section('seo')


@stop

@section('content-wrapper')
    @include('home.navbar')
    <!-- Inner Banner -->
    <div class="inner-banner inner-bg2">
        <div class="container">
            <div class="inner-title text-center">
                <h3>{{ __('translation.contact-us') }}</h3>
                <ul>
                    <li>
                        <a href="{{ localized_route('home') }}">{{ __('translation.home') }}</a>
                    </li>
                    <li>{{ __('translation.contact-us') }}</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Inner Banner End -->

    <!-- Contact Area -->
    <div class="contact-area pt-100 pb-70">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-4">
                    <div class="contact-info">
                        <span>{{ __('translation.contact-info') }}</span>
                        <h2>{{ __('translation.connect-with-us') }}</h2>
                        {{--<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam imperdiet varius mi, ut hendrerit magna mollis ac. </p>--}}
                        <ul>
                            <li>
                                <div class="content">
                                    <i class='bx bx-phone-call'></i>
                                    <h3>{{ __('translation.phone') }}</h3>
                                    <a href="tel:{{ config('site.phone') }}">{{ __('translation.phone-info') }}</a>
                                </div>
                            </li>

                            <li>
                                <div class="content">
                                    <i class='bx bxs-map'></i>
                                    <h3>{{ __('translation.address') }}</h3>
                                    <span>{{ __('translation.address-info') }}</span>
                                </div>
                            </li>

                            <li>
                                <div class="content">
                                    <i class='bx bx-message'></i>
                                    <h3>{{ __('translation.email') }}</h3>
                                    <a href="mailto:Layla.G.Hanna@hotmail.com">Layla.G.Hanna@hotmail.com</a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="contact-form">
                        <h3>{{ __('translation.connect-with-us') }}!</h3>
                        <form id="contactForm" method="post" action="{{ localized_route('contact.store') }}" data-ajax-form
                              data-invalid-message="{{ __('translation.form-check-fields') }}"
                              data-messages="{{ json_encode(['failed' => __('translation.form-failed'), 'tooMany' => __('translation.form-too-many'), 'check' => __('translation.form-check-fields')]) }}">
                            @csrf
                            @include('partials.form-guard')
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="name" class="visually-hidden">{{ __('translation.name') }}</label>
                                        <input type="text" name="name" id="name" class="form-control" required autocomplete="name" data-error="{{ __('translation.enter-name') }}" placeholder="{{ __('translation.name') }}*">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="email" class="visually-hidden">{{ __('translation.email') }}</label>
                                        <input type="email" name="email" id="email" class="form-control" required autocomplete="email" data-error="{{ __('translation.enter-email') }}" placeholder="{{ __('translation.email') }}*">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="phone_number" class="visually-hidden">{{ __('translation.phone') }}</label>
                                        <input type="tel" name="phone_number" id="phone_number" required autocomplete="tel" inputmode="tel" data-error="{{ __('translation.enter-phone') }}" class="form-control" placeholder="{{ __('translation.phone') }}*">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="msg_subject" class="visually-hidden">{{ __('translation.your-subject') }}</label>
                                        <input type="text" name="msg_subject" id="msg_subject" class="form-control" required data-error="{{ __('translation.enter-subject') }}" placeholder="{{ __('translation.your-subject') }}*">
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

                                <div class="col-lg-12 col-md-12">
                                    <div class="form-group">
                                        <label for="message" class="visually-hidden">{{ __('translation.your-message') }}</label>
                                        <textarea name="message" class="form-control" id="message" cols="30" rows="5" required data-error="{{ __('translation.enter-message') }}" placeholder="{{ __('translation.your-message') }}*"></textarea>
                                        <div class="help-block with-errors"></div>
                                    </div>
                                </div>

{{--                                <div class="col-lg-12 col-md-12">
                                    <div class="agree-label">
                                        <input type="checkbox" id="chb1">
                                        <label for="chb1">
                                            Accept <a href="terms-condition.html">Terms & Conditions</a> And <a href="privacy-policy.html">Privacy Policy.</a>
                                        </label>
                                    </div>
                                </div>--}}

                                <div class="col-lg-12 col-md-12">
                                    @include('partials.form-privacy')
                                    <button type="submit" class="default-btn">
                                        {{ __('translation.send-message') }}
                                    </button>
                                    <p id="msgSubmit" class="form-status h4 mt-3" role="status" aria-live="polite"></p>
                                    {{--<div class="clearfix"></div>--}}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Contact Area End -->

    <!-- Map Area -->
    <div class="map-area">
        <div class="container-fluid m-0 p-0">
            {{-- Google Maps only loads when asked (or after accepting cookies): public/js/consent.js. --}}
            <div class="map-placeholder"
                 data-map-src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d13160.043677475345!2d35.8095201!3d34.4518707!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xb9018011838f2074!2sJust%20Lite%20L.D%20by%20Layla%20Hanna!5e0!3m2!1sen!2slb!4v1644612187566!5m2!1sen!2slb"
                 data-map-title="{{ __('translation.map-title') }}">
                <p>{{ __('translation.address-info') }}</p>
                <div class="map-actions">
                    <button type="button" class="default-btn" data-map-load>{{ __('translation.show-map') }}</button>
                    <a class="default-btn two" href="https://www.google.com/maps/search/?api=1&amp;query=34.4518707,35.8095201" target="_blank" rel="noopener">{{ __('translation.open-map') }}</a>
                </div>
            </div>
        </div>
    </div>
    <!-- Map Area End -->
@endsection

{{-- The contact form is sent by public/js/forms.js. --}}
