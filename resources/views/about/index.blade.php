@php $lang=app()->getLocale(); $page='about';  @endphp
@extends('layouts.master')

@section('page_title', __('translation.about-us'))
@section('meta_description', __('translation.about-description'))

@section('head')

@endsection

@section('seo')


@stop

@section('content-wrapper')
    @include('home.navbar')
    @include('home.about')
@endsection