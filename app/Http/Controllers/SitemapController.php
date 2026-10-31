<?php

namespace App\Http\Controllers;

use App\Blog;
use App\Support\SiteLocale;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

/**
 * sitemap.xml, built from the database so new blog posts appear on their own.
 * Every page is listed in both languages with hreflang alternates.
 *
 * The services and diet-plan pages are left out: the site's menu no longer
 * links to them. Add them to pages() if they come back.
 */
class SitemapController extends Controller
{
    public function __invoke(): Response
    {
        $urls = [];

        foreach ($this->pages() as [$path, $lastModified]) {
            $alternates = [];
            foreach (config('app.available_locales') as $locale) {
                $alternates[$locale] = rtrim((string) config('site.url'), '/').(SiteLocale::localizedPath($path, $locale) ?: '/');
            }

            foreach ($alternates as $locale => $loc) {
                $urls[] = ['loc' => $loc, 'lastmod' => $lastModified?->toAtomString(), 'alternates' => $alternates];
            }
        }

        return response()
            ->view('sitemap', ['urls' => $urls])
            ->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    /**
     * Base paths (without the language prefix) and when their content last changed.
     *
     * @return array<int, array{0: string, 1: Carbon|null}>
     */
    private function pages(): array
    {
        $latestBlog = Blog::query()->max('updated_at');
        $latestBlog = $latestBlog ? Carbon::parse($latestBlog) : null;

        $pages = [
            ['/', null],
            ['/about', null],
            ['/testimonials', null],
            ['/blogs', $latestBlog],
            ['/contact-us', null],
            ['/privacy-policy', null],
        ];

        foreach (Blog::query()->orderBy('id')->get(['id', 'updated_at']) as $blog) {
            $pages[] = ['/blog-details/'.$blog->id, $blog->updated_at];
        }

        return $pages;
    }
}
