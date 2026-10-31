{{-- schema.org data about the clinic, for search engines (home and contact pages). --}}
@push('structured-data')
@php
    $siteUrl = rtrim(config('site.url'), '/');
    $business = [
        '@context' => 'https://schema.org',
        '@type' => 'MedicalClinic',
        'name' => 'Just Lite',
        'description' => __('translation.meta-description'),
        'url' => \App\Support\SiteLocale::canonical(),
        'logo' => $siteUrl.'/images/logo.png',
        'image' => $siteUrl.'/images/just-lite.png',
        'telephone' => config('site.phone'),
        'email' => config('site.email'),
        'medicalSpecialty' => 'DietNutrition',
        'address' => [
            '@type' => 'PostalAddress',
            'streetAddress' => 'Mar Elias Street',
            'addressLocality' => 'El Mina, Tripoli',
            'addressRegion' => 'North Lebanon',
            'addressCountry' => 'LB',
        ],
        'founder' => ['@type' => 'Person', 'name' => 'Layla Hanna', 'jobTitle' => 'Registered Dietitian'],
        'sameAs' => array_values(config('site.social')),
    ];
@endphp
<script type="application/ld+json">{!! json_encode($business, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) !!}</script>
@endpush
