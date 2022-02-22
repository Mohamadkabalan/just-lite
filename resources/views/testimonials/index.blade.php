@php $lang=app()->getLocale(); $page='testimonials';  @endphp
@extends('layouts.master')

@section('page_title')
    Testimonials
@endsection

@section('head')

@endsection

@section('seo')


@stop

@section('content-wrapper')
    @include('home.navbar')
    @include('home.testimonial')
    <div class="work-area pt-100 pb-70">
        <div class="container">
            <div class="section-title text-center">
                <span>{{ __('translation.how-it-works') }}</span>
                <h2>{{ __('translation.easy-steps-for-happy-life')  }}</h2>
            </div>
            <div class="row pt-45 justify-content-center">
                <div class="col-lg-3 col-sm-6">
                    <div class="work-card work-bg1">
                        <div class="content">
                            <i class="flaticon-contact-book"></i>
                            <h3>{{ __('translation.contact-us')  }}</h3>
                            {{--<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed eiusmod tempor incididunt </p>--}}
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="work-card work-bg2">
                        <div class="content">
                            <i class="flaticon-calendar-1"></i>
                            <h3>{{ __('translation.appointment')  }}</h3>
                            {{--<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed eiusmod tempor incididunt </p>--}}
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="work-card work-bg3">
                        <div class="content">
                            <i class="flaticon-research"></i>
                            <h3>{{ __('translation.analysis')  }}</h3>
                            {{--<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed eiusmod tempor incididunt </p>--}}
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-sm-6">
                    <div class="work-card work-bg4">
                        <div class="content">
                            <i class="flaticon-happiness"></i>
                            <h3>{{ __('translation.happy-life')  }}</h3>
                            {{--<p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed eiusmod tempor incididunt </p>--}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection