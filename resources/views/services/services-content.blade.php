@php $lang=app()->getLocale(); $page='services';  @endphp
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