<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * The page loads only the CSS/JS it uses, with ?v= URLs that can be cached
 * for a year (public/.htaccess).
 */
class AssetTest extends TestCase
{
    use DatabaseTransactions;

    public function test_css_and_js_urls_are_versioned(): void
    {
        $html = $this->get('/')->assertOk()->getContent();

        foreach (['css/style.css', 'css/responsive.css', 'css/rtl.css', 'js/custom.js', 'js/forms.js', 'js/consent.js'] as $path) {
            $this->assertStringContainsString(asset($path).'?v='.filemtime(public_path($path)), $html, $path);
        }
        $this->assertSame(0, preg_match('#(href|src)="[^"]+\.(css|js)"#', $html), 'every stylesheet and script is versioned');
    }

    public function test_unused_libraries_are_not_loaded(): void
    {
        $html = $this->get('/en')->assertOk()->getContent();

        foreach (['animate.min.css', 'odometer', 'appear.min.js', 'wow.min.js', 'jquery-ui', 'ajaxchimp', 'fonts.googleapis.com'] as $library) {
            $this->assertStringNotContainsString($library, $html, $library);
        }
    }
}
