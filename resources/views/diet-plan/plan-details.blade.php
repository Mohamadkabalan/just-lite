@php $lang=app()->getLocale(); $page='blogs';  @endphp
@extends('layouts.master')

@section('page_title')
    Blogs
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
            <div class="col-lg-8">
                <div class="service-details-content">
                    <div class="service-article-img-area">
                        <div class="row">
                            <div class="col-lg-6 col-md-6">
                                <div class="service-article-img">
                                    <img src="{{ asset($diet->image) }}" alt="Meal Images">
                                </div>
                            </div>
                        </div>
                    </div>
                    <p>
                        @if($lang=='en') {{$diet->title_en}}  @else {{$diet->title_ar}}  @endif
                    </p>
                    <p>
                        @if($lang=='en') {{$diet->text_en}}  @else {{$diet->text_ar}}  @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Service Details Area End -->
@endsection

@push('scripts')

@endpush