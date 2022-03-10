@php $lang=app()->getLocale(); $page='services';  @endphp
@extends('layouts.master')

@section('page_title')
    Services
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
                <h3>{{ __('translation.services') }}</h3>
                <ul>
                    <li>
                        <a href="/">{{ __('translation.home')  }}</a>
                    </li>
                    <li>{{ __('translation.services')}}</li>
                </ul>
            </div>
        </div>
    </div>
    <!-- Inner Banner End -->

    <!-- Services Area -->
    <div class="services-area pt-100 pb-70">
        <div class="container">
            <div class="section-title text-center">
                <span>{{ __('translation.what-we-offer')  }}</span>
                <h2></h2>
            </div>

            <div id="data-content" class="data-content row pt-45 justify-content-center">

                @include('services.services-content')
            </div>
        </div>
    </div>
    <!-- Services Area End -->

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
                    url:"/services/fetch_data?page="+page,
                    success:function(data)
                    {
                        $('#data-content').html(data);
                    }
                });
            }

        });
    </script>
@endpush