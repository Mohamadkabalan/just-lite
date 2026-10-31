/*==============================================================*/
// Cookie consent
/*==============================================================*/
// Google Tag Manager (analytics) only loads after the visitor accepts it in
// the banner. The choice is kept for a year in the "site_consent" cookie,
// which the server also reads so returning visitors who accepted get GTM in
// the page itself (layouts/master.blade.php). The Google map on the contact
// page loads when the visitor asks for it, or straight away after "accept".
(function () {
    'use strict';

    var COOKIE = 'site_consent';
    var banner = document.getElementById('consent-banner');
    var gtmId = banner ? banner.getAttribute('data-gtm') : null;

    function choice() {
        var match = document.cookie.match(/(?:^|;\s*)site_consent=(granted|denied)/);
        return match ? match[1] : null;
    }

    function remember(value) {
        document.cookie = COOKIE + '=' + value + '; Max-Age=31536000; Path=/; SameSite=Lax' + (location.protocol === 'https:' ? '; Secure' : '');
    }

    function loadGtm() {
        if (!gtmId || window.google_tag_manager) {
            return;
        }
        window.dataLayer = window.dataLayer || [];
        window.dataLayer.push({ 'gtm.start': new Date().getTime(), event: 'gtm.js' });
        var script = document.createElement('script');
        script.async = true;
        script.src = 'https://www.googletagmanager.com/gtm.js?id=' + encodeURIComponent(gtmId);
        document.head.appendChild(script);
    }

    function loadMaps() {
        var holders = document.querySelectorAll('[data-map-src]');
        for (var i = 0; i < holders.length; i++) {
            var frame = document.createElement('iframe');
            frame.src = holders[i].getAttribute('data-map-src');
            frame.title = holders[i].getAttribute('data-map-title') || '';
            frame.loading = 'lazy';
            frame.allowFullscreen = true;
            holders[i].replaceWith(frame);
        }
    }

    function setBanner(visible) {
        if (banner) {
            banner.hidden = !visible;
        }
    }

    document.addEventListener('click', function (event) {
        var target = event.target.closest('[data-consent], [data-consent-reset], [data-map-load]');
        if (!target) {
            return;
        }
        event.preventDefault();

        if (target.hasAttribute('data-map-load')) {
            loadMaps();
        } else if (target.hasAttribute('data-consent-reset')) {
            setBanner(true);
            var first = banner && banner.querySelector('button');
            if (first) {
                first.focus();
            }
        } else {
            var value = target.getAttribute('data-consent') === 'granted' ? 'granted' : 'denied';
            remember(value);
            setBanner(false);
            if (value === 'granted') {
                loadGtm();
                loadMaps();
            } else if (window.google_tag_manager) {
                // GTM is already running in this page; a reload stops it.
                location.reload();
            }
        }
    });

    var current = choice();
    setBanner(current === null);
    if (current === 'granted') {
        loadMaps();
    }
}());
