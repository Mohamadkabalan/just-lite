@php $lang=app()->getLocale(); $page='services';  @endphp
@forelse($data as $row)
    <div class="col-lg-4 col-md-6">
        <div class="services-card">
            <a href="{{ localized_route('blogs.blog-details', $row->id) }}">
                @include('partials.picture', ['path' => $row->image, 'size' => 'medium', 'alt' => $lang == 'en' ? $row->title_en : $row->title_ar, 'fallback' => asset('images/services/services-img1.jpg'), 'loading' => 'lazy'])
            </a>
            <div class="content">
                <h3>
                    <a href="{{ localized_route('blogs.blog-details', $row->id) }}">@if($lang=='en') {{$row->title_en}} @else {{$row->title_ar}}  @endif </a>
                </h3>
                <p>
                    @if($lang=='en') {{$row->text_en}} @else {{$row->text_ar}}  @endif
                </p>
                <a href="{{ localized_route('blogs.blog-details', $row->id) }}" class="learn-btn">{{ __('translation.learn-more') }}</a>
            </div>
        </div>
    </div>
@empty
    <p class="col-12 text-center empty-state">{{ __('translation.no-posts') }}</p>
@endforelse
@if($data->hasPages())
<div class="col-lg-12 col-md-12 text-center">
    <div class="d-flex justify-content-center pagination-area">
        {!! $data->links() !!}
    </div>
</div>
@endif