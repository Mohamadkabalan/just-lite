<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Route;
use RuntimeException;
use Tests\TestCase;

/**
 * The branded error pages: in the visitor's language, with a way back.
 */
class ErrorPageTest extends TestCase
{
    use DatabaseTransactions;

    public function test_missing_pages_explain_themselves_in_the_right_language(): void
    {
        $this->get('/this-page-does-not-exist')->assertNotFound()
            ->assertSee('<html lang="ar" dir="rtl">', false)
            ->assertSee(__('errors.404.title', [], 'ar'))
            ->assertSee(__('errors.home', [], 'ar'))
            ->assertSee('href="/"', false)
            ->assertSee('css/style.css', false)
            ->assertSee('<meta name="robots" content="noindex">', false);

        foreach (['/en/this-page-does-not-exist', '/en/blog-details/999999999'] as $url) {
            $this->get($url)->assertNotFound()
                ->assertSee('<html lang="en" dir="ltr">', false)
                ->assertSee('Page not found')
                ->assertSee('href="/en"', false);
        }
    }

    public function test_other_statuses_use_the_same_page(): void
    {
        config(['app.debug' => false]);
        Route::get('/__test/server-error', fn () => throw new RuntimeException('boom'));
        Route::get('/__test/maintenance', fn () => abort(503));

        $this->get('/contact/create')->assertStatus(405)->assertSee('405')->assertSee(__('errors.4xx.title', [], 'ar'));
        $this->get('/__test/server-error')->assertStatus(500)->assertSee(__('errors.500.title', [], 'ar'))->assertDontSee('boom');
        $this->get('/__test/maintenance')->assertStatus(503)->assertSee(__('errors.503.title', [], 'ar'));
    }
}
