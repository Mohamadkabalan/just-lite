<?php

namespace Tests\Feature;

use App\Blog;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

/**
 * Language URLs, canonical/hreflang tags, titles, descriptions, structured data
 * and the sitemap. Runs against the staging/Docker database; writes roll back.
 */
class SeoTest extends TestCase
{
    use DatabaseTransactions;

    private const SITE = 'https://just-lite.com';

    public function test_each_language_has_its_own_urls(): void
    {
        $this->get('/about')->assertOk()->assertSee('<html lang="ar" dir="rtl">', false);
        $this->get('/en/about')->assertOk()->assertSee('<html lang="en" dir="ltr">', false);
        $this->get('/en')->assertOk()->assertSee('<html lang="en" dir="ltr">', false);

        // A language chosen earlier no longer changes what a URL shows.
        $this->withSession(['locale' => 'en'])->get('/about')->assertSee('<html lang="ar" dir="rtl">', false);
    }

    public function test_canonical_and_hreflang_point_at_the_page_itself(): void
    {
        $this->get('/about')->assertOk()
            ->assertSee('<link rel="canonical" href="'.self::SITE.'/about">', false)
            ->assertSee('<link rel="alternate" hreflang="ar" href="'.self::SITE.'/about">', false)
            ->assertSee('<link rel="alternate" hreflang="en" href="'.self::SITE.'/en/about">', false)
            ->assertSee('<link rel="alternate" hreflang="x-default" href="'.self::SITE.'/about">', false)
            ->assertSee('<meta property="og:url" content="'.self::SITE.'/about">', false);

        $this->get('/en')->assertOk()->assertSee('<link rel="canonical" href="'.self::SITE.'/en">', false);
        $this->get('/')->assertOk()->assertSee('<link rel="canonical" href="'.self::SITE.'/">', false);

        if (Blog::count() > 6) {
            $this->get('/en/blogs?page=2&utm_source=x')->assertOk()
                ->assertSee('<link rel="canonical" href="'.self::SITE.'/en/blogs?page=2">', false);
        }
    }

    public function test_pages_have_translated_titles_and_descriptions(): void
    {
        $this->get('/about')->assertOk()
            ->assertSee('<title>'.e(__('translation.about-us', [], 'ar')).' | Just Lite</title>', false)
            ->assertSee('<meta name="description" content="'.e(__('translation.about-description', [], 'ar')).'">', false);

        $this->get('/en/about')->assertOk()
            ->assertSee('<title>About Us | Just Lite</title>', false)
            ->assertSee('<meta property="og:locale" content="en_US">', false)
            ->assertSee('<meta name="twitter:card" content="summary_large_image">', false);

        $this->get('/en')->assertOk()->assertSee('<title>'.e(__('translation.meta-title', [], 'en')).'</title>', false);
    }

    public function test_blog_titles_are_escaped_exactly_once(): void
    {
        $blog = new Blog;
        $blog->title_en = 'Bread & "Butter"';
        $blog->title_ar = 'خبز';
        $blog->text_en = 'Toast <b>it</b> & enjoy.';
        $blog->text_ar = 'نص';
        $blog->save();

        $this->get('/en/blog-details/'.$blog->id)->assertOk()
            ->assertSee('<title>Bread &amp; &quot;Butter&quot; | Just Lite</title>', false)
            ->assertSee('<meta name="description" content="Toast it &amp; enjoy.">', false)
            ->assertSee('"@type":"BlogPosting"', false);

        $this->get('/blog-details/'.$blog->id)->assertOk()->assertSee('<title>خبز | Just Lite</title>', false);
    }

    public function test_links_stay_in_the_visitors_language(): void
    {
        $html = $this->get('/en/about')->assertOk()->getContent();

        foreach (['en.home', 'en.about', 'en.testimonials', 'en.blogs.index', 'en.contact-us'] as $name) {
            $this->assertStringContainsString('href="'.route($name).'"', $html, $name);
        }
        $this->assertStringNotContainsString('href="'.route('about').'" class="nav-link', $html);

        // The language menu opens the same page in the other language.
        $this->assertStringContainsString('href="'.url('/about').'" hreflang="ar"', $html);
        $this->assertStringContainsString('href="'.url('/en/about').'" hreflang="en"', $html);
        $this->assertStringContainsString('<span class="lang-name">English</span>', $html);
        $this->get('/about')->assertSee('<span class="lang-name">العربيّة</span>', false);
    }

    public function test_home_and_contact_describe_the_business(): void
    {
        foreach (['/', '/en/contact-us'] as $url) {
            $this->get($url)->assertOk()
                ->assertSee('"@type":"MedicalClinic"', false)
                ->assertSee('"telephone":"'.config('site.phone').'"', false);
        }
    }

    public function test_the_sitemap_lists_every_page_in_both_languages(): void
    {
        $blog = Blog::query()->orderBy('id')->firstOrFail();

        $response = $this->get('/sitemap.xml')->assertOk()->assertHeader('Content-Type', 'application/xml; charset=UTF-8');
        $xml = $response->getContent();

        $this->assertNotFalse(simplexml_load_string($xml), 'sitemap.xml is not valid XML');
        foreach (['/', '/en', '/about', '/en/about', '/blog-details/'.$blog->id, '/en/blog-details/'.$blog->id] as $path) {
            $this->assertStringContainsString('<loc>'.self::SITE.$path.'</loc>', $xml, $path);
        }
        $this->assertStringContainsString('hreflang="en" href="'.self::SITE.'/en/blog-details/'.$blog->id.'"', $xml);
        $this->assertStringNotContainsString('<loc>http://', $xml);
    }

    public function test_list_pages_past_the_end_are_not_found(): void
    {
        $this->get('/blogs?page=999')->assertNotFound();
        $this->get('/en/services?page=999')->assertNotFound();
    }
}
