@php $lang=app()->getLocale(); $page='home';  @endphp
@extends('layouts.master')

@section('page_title')
    Home
@endsection

@section('head')

@endsection

@section('seo')


@stop

@section('content-wrapper')
    @include('home.navbar')
    @include('home.banner')
    {{--@include('home.banner-bottom-area')--}}
    @include('home.about')
    @include('home.packages')
    {{--    @include('home.diet-plan-area')--}}
    @include('home.appointment')
    @include('home.testimonial')
    @include('home.bad-habits')


@endsection