<!-- Diet Plan Area -->
<div class="diet-plan-area pt-100 pb-70">
    <div class="container">
        <div class="section-title text-center">
            <span> {{ __('translation.diet-plan') }}</span>
            <h2>{{ __('translation.our-weekly-diet-plan') }}</h2>
        </div>
        <div class="tab diet-tab pt-45">
            <div class="row">
                <div class="col-lg-12">
                    <ul class="tabs">
                        <li>{{ __('translation.saturday') }}</li>
                        <li>{{ __('translation.sunday') }}</li>
                        <li>{{ __('translation.monday') }}</li>
                        <li>{{ __('translation.tuesday') }}</li>
                        <li>{{ __('translation.wednesday') }}</li>
                        <li>{{ __('translation.thursday') }}</li>
                        <li>{{ __('translation.friday') }}</li>
                    </ul>
                </div>

                <div class="col-lg-12 col-md-12">
                    <div class="tab_content current active pt-45">
                        <div class="tabs_item current">
                            <div class="row align-items-center">
                               @php $dietPlanAreas =\App\WeeklyDietPlan::where('day','option1')->get();  @endphp
                               @foreach($dietPlanAreas as $dietPlanArea)
                                        <div class="col-lg-3 col-sm-6">
                                                <div class="diet-plan-card">
                                                    <div class="diet-plan-img">
                                                        <a href="/diet-plan/{{$dietPlanArea->id}}">
                                                            <img src="{{ asset('storage/'.str_replace('\\', '/', $dietPlanArea->image)) }}" alt="Images">
                                                        </a>
                                                        <div class="tag">
                                                            <a href="/diet-plan/{{$dietPlanArea->id}}"> @if($lang=='en') {{$dietPlanArea->title_en}} @else {{$dietPlanArea->title_ar}}  @endif</a>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="tabs_item">
                            <div class="row align-items-center">
                                @php $dietPlanAreas =\App\WeeklyDietPlan::where('day','option2')->get();  @endphp
                                @foreach($dietPlanAreas as $dietPlanArea)
                                        <div class="col-lg-3 col-sm-6">
                                                <div class="diet-plan-card">
                                                    <div class="diet-plan-img">
                                                        <a href="/diet-plan/{{$dietPlanArea->id}}">
                                                            <img src="{{ asset('storage/'.str_replace('\\', '/', $dietPlanArea->image)) }}" alt="Images">
                                                        </a>
                                                        <div class="tag">
                                                            <a href=""> @if($lang=='en') {{$dietPlanArea->title_en}} @else {{$dietPlanArea->title_ar}}  @endif</a>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="tabs_item">
                            <div class="row align-items-center">
                                @php $dietPlanAreas =\App\WeeklyDietPlan::where('day','option3')->get();  @endphp
                                @foreach($dietPlanAreas as $dietPlanArea)
                                        <div class="col-lg-3 col-sm-6">
                                                <div class="diet-plan-card">
                                                    <div class="diet-plan-img">
                                                        <a href="/diet-plan/{{$dietPlanArea->id}}">
                                                            <img src="{{ asset('storage/'.str_replace('\\', '/', $dietPlanArea->image)) }}" alt="Images">
                                                        </a>
                                                        <div class="tag">
                                                            <a href="/diet-plan/{{$dietPlanArea->id}}"> @if($lang=='en') {{$dietPlanArea->title_en}} @else {{$dietPlanArea->title_ar}}  @endif</a>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="tabs_item">
                            <div class="row align-items-center">
                                @php $dietPlanAreas =\App\WeeklyDietPlan::where('day','option4')->get();  @endphp
                                @foreach($dietPlanAreas as $dietPlanArea)
                                        <div class="col-lg-3 col-sm-6">
                                            <div class="diet-plan-card">
                                                <div class="diet-plan-img">
                                                    <a href="/diet-plan/{{$dietPlanArea->id}}">
                                                        <img src="{{ asset('storage/'.str_replace('\\', '/', $dietPlanArea->image)) }}" alt="Images">
                                                    </a>
                                                    <div class="tag">
                                                        <a href="/diet-plan/{{$dietPlanArea->id}}"> @if($lang=='en') {{$dietPlanArea->title_en}} @else {{$dietPlanArea->title_ar}}  @endif</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="tabs_item">
                            <div class="row align-items-center">
                                @php $dietPlanAreas =\App\WeeklyDietPlan::where('day','option5')->get();  @endphp
                                @foreach($dietPlanAreas as $dietPlanArea)
                                        <div class="col-lg-3 col-sm-6">
                                                <div class="diet-plan-card">
                                                    <div class="diet-plan-img">
                                                        <a href="/diet-plan/{{$dietPlanArea->id}}">
                                                            <img src="{{ asset('storage/'.str_replace('\\', '/', $dietPlanArea->image)) }}" alt="Images">
                                                        </a>
                                                        <div class="tag">
                                                            <a href="/diet-plan/{{$dietPlanArea->id}}"> @if($lang=='en') {{$dietPlanArea->title_en}} @else {{$dietPlanArea->title_ar}}  @endif</a>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="tabs_item">
                            <div class="row align-items-center">
                                @php $dietPlanAreas =\App\WeeklyDietPlan::where('day','option6')->get();  @endphp
                                @foreach($dietPlanAreas as $dietPlanArea)
                                        <div class="col-lg-3 col-sm-6">
                                                <div class="diet-plan-card">
                                                    <div class="diet-plan-img">
                                                        <a href="/diet-plan/{{$dietPlanArea->id}}">
                                                            <img src="{{ asset('storage/'.str_replace('\\', '/', $dietPlanArea->image)) }}" alt="Images">
                                                        </a>
                                                        <div class="tag">
                                                            <a href="/diet-plan/{{$dietPlanArea->id}}"> @if($lang=='en') {{$dietPlanArea->title_en}} @else {{$dietPlanArea->title_ar}}  @endif</a>
                                                        </div>
                                                    </div>
                                                </div>
                                        </div>
                                @endforeach
                            </div>
                        </div>

                        <div class="tabs_item">
                            <div class="row align-items-center">
                                @php $dietPlanAreas =\App\WeeklyDietPlan::where('day','option7')->get();  @endphp
                                @foreach($dietPlanAreas as $dietPlanArea)
                                     <div class="col-lg-3 col-sm-6">
                                             <div class="diet-plan-card">
                                                 <div class="diet-plan-img">
                                                     <a href="/diet-plan/{{$dietPlanArea->id}}">
                                                         <img src="{{ asset('storage/'.str_replace('\\', '/', $dietPlanArea->image)) }}" alt="Images">
                                                     </a>
                                                     <div class="tag">
                                                         <a href="/diet-plan/{{$dietPlanArea->id}}"> @if($lang=='en') {{$dietPlanArea->title_en}} @else {{$dietPlanArea->title_ar}}  @endif</a>
                                                     </div>
                                                 </div>
                                             </div>
                                        </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Diet Plan Area End -->
