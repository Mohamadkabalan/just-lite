@php $lang=app()->getLocale(); $page='diet-plan';  @endphp
@extends('layouts.master')

@section('page_title', ($lang == 'en' ? $diet->title_en : $diet->title_ar).' – '.__('translation.diet-plan'))
@section('meta_description', \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/u', ' ', strip_tags($lang == 'en' ? (string) $diet->text_en : (string) $diet->text_ar))), 155))
@if($diet->image)
@section('og_image', \App\Support\VoyagerImage::url($diet->image))
@endif

@section('head')
@endsection

@section('seo')


@stop

@section('content-wrapper')
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
                                    <img src="{{ asset('storage/'.str_replace('\\', '/', $diet->image)) }}" alt="Meal Images">
                                </div>
                            </div>
                        </div>
                    </div>
                    <h1>@if($lang=='en') {{$diet->title_en}} @else {{$diet->title_ar}} @endif</h1>
                    <p>
                        @if($lang=='en') {!! nl2br(e($diet->text_en)) !!}  @else {!! nl2br(e($diet->text_ar)) !!}  @endif
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