@php $infos = \App\Info::with('details')->get();  @endphp
<div class="event-area pt-100 pb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="event-content">
                    <div class="row">
                       @foreach($infos as $key => $info)
                            @php $infoDetails = $info->details;  @endphp
                        <div class="col-lg-12">
                            <div class="content">
                                <h3 class="text-green">@if($lang=='en') {{$info->title_en}} @else {{$info->title_ar}}  @endif</h3>
                                <ul class="p-0">
                                    @foreach($infoDetails as $infoDetail)
                                        <li class="info-item">@if($lang=='en') {{$infoDetail->list_en}} @else {{$infoDetail->list_ar}}  @endif</li>
                                    @endforeach
                                </ul>

                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="event-img">
                    <img src="{{ asset('images/event/event-img.jpg') }}" alt="" width="750" height="800" loading="lazy" decoding="async">
                    <div class="sub-img">
                        <img src="{{ asset('images/event/event-sub-img.png') }}" alt="" width="325" height="326" loading="lazy" decoding="async">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>