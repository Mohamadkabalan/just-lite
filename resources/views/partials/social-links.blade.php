{{-- Social profile links (config site.social), as <li> items. Pass $order to change the order. --}}
@php
    $names = ['facebook' => 'Facebook', 'instagram' => 'Instagram', 'linkedin' => 'LinkedIn'];
@endphp
@foreach($order ?? array_keys(config('site.social')) as $network)
    <li>
        <a href="{{ config('site.social.'.$network) }}" target="_blank" rel="noopener" aria-label="{{ $names[$network] }} {{ __('translation.new-tab') }}">
            <i class='bx bxl-{{ $network }}' aria-hidden="true"></i>
        </a>
    </li>
@endforeach
