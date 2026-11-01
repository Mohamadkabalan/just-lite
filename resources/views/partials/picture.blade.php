{{--
    An image uploaded through Voyager: the WebP copy for browsers that take it
    (php artisan images:optimize), the original for the rest, and its width and
    height so the page doesn't jump while it loads.
    Parameters: path (Voyager path), size (e.g. "medium"), alt, fallback (URL
    when there is no image), class, loading ("lazy"), fetchpriority.
--}}
@php
    $size = $size ?? null;
    $src = \App\Support\VoyagerImage::url($path ?? null, $size) ?? ($fallback ?? null);
    $webp = \App\Support\VoyagerImage::webp($path ?? null, $size);
    $dims = \App\Support\VoyagerImage::size($path ?? null, $size);
@endphp
@if($src)
<picture>
    @if($webp)
    <source type="image/webp" srcset="{{ $webp }}">
    @endif
    <img src="{{ $src }}" alt="{{ $alt ?? '' }}"@if($dims) width="{{ $dims[0] }}" height="{{ $dims[1] }}"@endif @if(! empty($class)) class="{{ $class }}"@endif @if(! empty($loading)) loading="{{ $loading }}"@endif @if(! empty($fetchpriority)) fetchpriority="{{ $fetchpriority }}"@endif decoding="async">
</picture>
@endif
