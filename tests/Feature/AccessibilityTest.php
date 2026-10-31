<?php

namespace Tests\Feature;

use App\Blog;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Page structure for screen readers and keyboards: one h1, landmarks, a skip
 * link and names for icon-only links. The browser checks (axe-core, contrast,
 * focus) are in the audit scripts; these keep the markup from regressing.
 */
class AccessibilityTest extends TestCase
{
    use DatabaseTransactions;

    public function test_every_page_has_one_h1_a_main_landmark_and_a_skip_link(): void
    {
        $blog = Blog::query()->orderBy('id')->firstOrFail();

        foreach (['/', '/about', '/testimonials', '/services', '/blogs', '/contact-us', '/privacy-policy', '/blog-details/'.$blog->id, '/en', '/en/contact-us'] as $url) {
            $html = $this->get($url)->assertOk()->getContent();

            $this->assertSame(1, preg_match_all('/<h1[\s>]/', $html), "one h1 on {$url}");
            $this->assertSame(1, substr_count($html, '<main id="main-content"'), "main landmark on {$url}");
            $this->assertStringContainsString('<a href="#main-content" class="skip-link', $html, $url);
            $this->assertSame(1, substr_count($html, '<header class="site-header">'), "one header on {$url}");
            $this->assertDoesNotMatchRegularExpression('#<h([1-6])[^>]*>\s*</h\1>#', $html, "empty heading on {$url}");
        }
    }

    public function test_icon_only_links_have_names(): void
    {
        $html = $this->get('/en')->assertOk()->getContent();

        foreach (['Facebook', 'Instagram', 'LinkedIn'] as $network) {
            $this->assertSame(2, substr_count($html, 'aria-label="'.$network.' (opens in a new tab)"'), $network.' in header and footer');
        }
        $this->assertStringContainsString('class="scroll-whatsapp" aria-label="Chat with us on WhatsApp', $html);
        $this->assertStringContainsString('aria-label="Close"', $html);
        $this->assertStringContainsString('data-slide-label="Slide %n"', $html);
        $this->assertStringContainsString('data-menu-label="Menu"', $html);
    }
}
