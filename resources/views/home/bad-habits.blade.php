<div class="faq-area pt-100 pb-70">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-title text-center">
                    <h2>{{ __('translation.bad-habits')  }}</h2>
                </div>
            </div>
        </div>
        <div class="row pt-4">
            <div class="col-lg-6">
                <div class="faq-accordion">
                    @php $badHabit =\App\BadHabit::all(); $avg=intval($badHabit->count() / 2); @endphp
                    <ul class="accordion">
                        @for($i=0;$i <$avg;$i++)
                        <li class="accordion-item">
                            <a class="accordion-title" href="javascript:void(0)">
                                <i class="bx bx-plus"></i>
                                @if($lang=='en') {{$badHabit[$i]->title_en}} @else {{$badHabit[$i]->title_ar}}  @endif
                            </a>
                            <div class="accordion-content show" style="display: none;">
                                <p>
                                    @if($lang=='en') {{$badHabit[$i]->text_en}} @else {{$badHabit[$i]->text_ar}}  @endif
                                </p>
                            </div>
                        </li>
                        @endfor
                    </ul>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="faq-accordion">
                    <ul class="accordion">
                        @for($i=$avg;$i< $badHabit->count();$i++)
                            <li class="accordion-item">
                                <a class="accordion-title" href="javascript:void(0)">
                                    <i class="bx bx-plus"></i>
                                    @if($lang=='en') {{$badHabit[$i]->title_en}} @else {{$badHabit[$i]->title_ar}}  @endif
                                </a>
                                <div class="accordion-content show" style="display: none;">
                                    <p>
                                        @if($lang=='en') {{$badHabit[$i]->text_en}} @else {{$badHabit[$i]->text_ar}}  @endif
                                    </p>
                                </div>
                            </li>
                        @endfor
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>