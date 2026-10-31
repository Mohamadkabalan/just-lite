{{-- Cookie banner (public/js/consent.js). Hidden once the visitor has chosen;
     "Cookie settings" in the footer shows it again. --}}
<div id="consent-banner" class="consent-banner" role="region" aria-label="{{ __('translation.cookie-title') }}"
     data-gtm="{{ config('site.gtm_id') }}" @if(in_array(request()->cookie('site_consent'), ['granted', 'denied'], true)) hidden @endif>
    <div class="container">
        <p>
            {{ __('translation.cookie-text') }}
            <a href="{{ localized_route('privacy') }}">{{ __('translation.privacy-policy') }}</a>
        </p>
        <div class="consent-actions">
            <button type="button" class="default-btn" data-consent="granted">{{ __('translation.cookie-accept') }}</button>
            <button type="button" class="default-btn two" data-consent="denied">{{ __('translation.cookie-decline') }}</button>
        </div>
    </div>
</div>
