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
                @foreach($data as $row)

                <div class="col-lg-4 col-md-6">
                    <div class="services-card">
                        <a href="/service-details/{{$row->id}}">
                            <img src="@if(isset(json_decode($row->images)[0])) {{ asset( 'storage/'.str_replace('\\', '/', json_decode($row->images)[0])) }} @else {{ asset('images/services/services-img1.jpg')  }}  @endif " alt="Images">
                        </a>
                        <div class="content">
                            <h3>
                                <a href="service-details.html">@if($lang=='en') {{$row->title_en}} @else {{$row->title_Ar}}  @endif </a>
                            </h3>
                            <p>
                                @if($lang=='en') {{$row->text_en}} @else {{$row->text_ar}}  @endif
                            </p>
                            <a href="/service-details/{{$row->id}}" class="learn-btn">{{ __('translation.learn-more') }}</a>
                        </div>
                    </div>
                </div>
                @endforeach

                <div class="col-lg-12 col-md-12 text-center">
                        <div class="d-flex justify-content-center pagination-area">
                                {!! $data->links() !!}
                        </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Services Area End -->

@endsection

@push('scripts')
    <script>
        $(document).ready(function(){

            $(document).on('click', '.d-flex a', function(event){
                event.preventDefault();
                var page = $(this).attr('href').split('page=')[1];
                fetch_data(page);
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