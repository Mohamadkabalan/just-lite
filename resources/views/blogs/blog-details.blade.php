@php
    $page="blog-details";
    $lang=app()->getLocale();
@endphp
@extends('layouts.master')

@section('page_title')
    Blog - {{ $blog->title_en }}
@endsection

@section('head')
@endsection

@section('seo')


@stop

@section('content-wrapper')
    @include('home.navbar')
    <!-- Service Details Area -->
    <div class="service-details-area pt-100 pb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="service-details-content">
                        @if(isset($blog->image))
                            <div class="service-preview-slider owl-carousel owl-theme">
                                    <div class="service-preview-item">
                                        <img src="{{ asset( 'storage/'.str_replace('\\', '/', $blog->image))  }}" alt="Blog Images">
                                    </div>
                            </div>
                        @endif
                        <h2>@if($lang=='en') {{ $blog->title_en }} @else {{ $blog->title_ar }} @endif</h2>
                        <div>
                            @if($lang=='en') {!! nl2br(e($blog->text_en)) !!} @else {!! nl2br(e($blog->text_ar)) !!} @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Service Details Area End -->


@endsection

@push('scripts')
@endpush