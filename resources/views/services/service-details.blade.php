@php
$page="services-details";
$lang=app()->getLocale();
$services=\App\Service::paginate(6);
@endphp
@extends('layouts.master')

@section('page_title')
    Services
@endsection

@section('head')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">
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
                        @if(isset(json_decode($service->images)[0]))
                            @php $images=json_decode($service->images); @endphp
                        <div class="service-preview-slider owl-carousel owl-theme">
                            @foreach($images as $image)

                            <div class="service-preview-item">
                                <img src="{{ asset( 'storage/'.str_replace('\\', '/', $image))  }}" alt="Blog Images">
                            </div>
                            @endforeach
                        </div>
                        @endif
                        <h2>@if($lang=='en') {{ $service->title_en }} @else {{ $service->title_ar }} @endif</h2>
                        <div>
                            @if($lang=='en') {{ $service->text_en }} @else {{ $service->text_ar }} @endif
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