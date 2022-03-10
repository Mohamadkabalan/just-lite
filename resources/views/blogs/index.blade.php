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
    <!-- Inner Banner -->
    <div class="inner-banner inner-bg8">
        <div class="container">
            <div class="inner-title text-center">
                <h3>{{ __('translation.blogs') }}</h3>
                <ul>
                    <li>
                        <a href="/">{{ __('translation.home') }}</a>
                    </li>
                    <li>{{ __('translation.blogs') }}</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Inner Banner End -->

    <!-- Blogs Area -->
    <div class="services-area pt-100 pb-70">
        <div class="container">
            <div class="section-title text-center">
                <span>{{ __('translation.latest-new') }}</span>
                <h2>{{ __('translation.latest-recipe-from-blog') }}</h2>
            </div>

            <div id="data-content" class="data-content row pt-45 justify-content-center">
                   @include('blogs.blogs-content')
            </div>
        </div>
    </div>
    <!-- Blogs Area End -->

@endsection

@push('scripts')
    <script>
        $(document).ready(function(){

            $(document).on('click', '.page-link', function(event){
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                if(page){
                    fetch_data(page);
                }
            });

            function fetch_data(page)
            {
                $.ajax({
                    url:"/blogs/fetch_data?page="+page,
                    success:function(data)
                    {
                        $('#data-content').html(data);
                    }
                });
            }

        });
    </script>
@endpush