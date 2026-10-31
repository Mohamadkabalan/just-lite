@php $lang=app()->getLocale(); $page='home';  @endphp
@extends('layouts.master')

{{-- No page_title: the home page uses translation.meta-title. --}}

@section('head')

@endsection

@section('seo')


@stop

@include('partials.business-schema')

@section('content-wrapper')
    <h1 class="visually-hidden">{{ __('translation.meta-title') }}</h1>
    @include('home.banner')
    {{--@include('home.banner-bottom-area')--}}
    @include('home.about')
    @include('home.info')
    @include('home.packages')
    {{--    @include('home.diet-plan-area')--}}
    {{--@include('home.appointment')--}}
    @include('home.testimonial')
    @include('home.bad-habits')
    @include('home.results')


@endsection
