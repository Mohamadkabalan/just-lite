@php $infos = \App\Info::all();  @endphp
<div class="event-area pt-100 pb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="event-content">
                    <div class="row">
                       @foreach($infos as $key => $info)
                            @php $infoDetails = \App\InfoDetail::where('info_id',$info->id)->get();  @endphp
                        <div class="col-lg-12">
                            <div class="content">
                                <h2 class="text-green">@if($lang=='en') {{$info->title_en}} @else {{$info->title_ar}}  @endif</h2>
                                <ul class="p-0">
                                    @foreach($infoDetails as $infoDetail)
                                        <li><h3> @if($lang=='en') {{$infoDetail->list_en}} @else {{$info->list_ar}}  @endif</h3></li>
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
                    <img src="{{ asset('images/event/event-img.jpg')  }}" alt="Images">
                    <div class="sub-img">
                        <img src="{{ asset('images/event/event-sub-img.png')  }}" alt="Images">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>