@php $lang=app()->getLocale(); $page='about';  @endphp
@extends('layouts.master')

@section('page_title')
    About
@endsection

@section('head')

@endsection

@section('seo')


@stop

@section('content-wrapper')
    @include('home.navbar')
    @include('home.about')
@endsection