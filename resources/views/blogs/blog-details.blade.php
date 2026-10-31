@php
    $page="blog-details";
    $lang=app()->getLocale();
@endphp
@extends('layouts.master')

@php
    $blogTitle = $lang == 'en' ? $blog->title_en : $blog->title_ar;
    $blogImage = \App\Support\VoyagerImage::url($blog->image);
@endphp
@section('page_title', $blogTitle)
@section('meta_description', \Illuminate\Support\Str::limit(trim(preg_replace('/\s+/u', ' ', strip_tags($lang == 'en' ? (string) $blog->text_en : (string) $blog->text_ar))), 155))
@section('og_type', 'article')
@if($blogImage)
@section('og_image', $blogImage)
@endif

@push('structured-data')
@php
    $article = [
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'headline' => $blogTitle,
        'inLanguage' => $lang,
        'mainEntityOfPage' => \App\Support\SiteLocale::canonical(),
        'author' => ['@type' => 'Person', 'name' => 'Layla Hanna'],
        'publisher' => ['@type' => 'Organization', 'name' => 'Just Lite', 'logo' => ['@type' => 'ImageObject', 'url' => rtrim(config('site.url'), '/').'/images/logo.png']],
    ];
    if ($blogImage) {
        $article['image'] = rtrim(config('site.url'), '/').parse_url($blogImage, PHP_URL_PATH);
    }
    if ($blog->created_at) {
        $article['datePublished'] = $blog->created_at->toAtomString();
    }
    if ($blog->updated_at) {
        $article['dateModified'] = $blog->updated_at->toAtomString();
    }
@endphp
<script type="application/ld+json">{!! json_encode($article, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
@endpush

@section('head')
@endsection

@section('seo')


@stop

@section('content-wrapper')
    <!-- Service Details Area -->
    <div class="service-details-area pt-100 pb-70">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="service-details-content">
                        @if(isset($blog->image))
                            <div class="service-preview-slider owl-carousel owl-theme">
                                    <div class="service-preview-item">
                                        @include('partials.picture', ['path' => $blog->image, 'alt' => $blogTitle, 'fetchpriority' => 'high'])
                                    </div>
                            </div>
                        @endif
                        <h1>{{ $blogTitle }}</h1>
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