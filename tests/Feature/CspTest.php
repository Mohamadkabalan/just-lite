<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

/**
 * The report-only Content-Security-Policy on public pages and its report endpoint.
 */
class CspTest extends TestCase
{
    use DatabaseTransactions;

    public function test_public_pages_carry_a_report_only_policy(): void
    {
        foreach (['/', '/en/about', '/contact-us'] as $url) {
            $policy = (string) $this->get($url)->assertOk()->headers->get('Content-Security-Policy-Report-Only');

            $this->assertStringContainsString("default-src 'self'", $policy, $url);
            $this->assertStringContainsString("object-src 'none'", $policy);
            $this->assertStringContainsString('frame-src https://www.google.com https://www.youtube-nocookie.com', $policy);
            $this->assertStringContainsString('report-uri '.url('/api/csp-report'), $policy);
        }

        $this->get('/admin/login')->assertOk()->assertHeaderMissing('Content-Security-Policy-Report-Only');
    }

    public function test_violation_reports_are_logged_once_a_day(): void
    {
        config(['cache.default' => 'array']);
        Log::spy();
        $report = ['csp-report' => [
            'document-uri' => 'https://just-lite.com/about?email=someone@example.test',
            'effective-directive' => 'script-src-elem',
            'blocked-uri' => 'https://tracker.example/x.js?id=123',
        ]];

        $this->call('POST', '/api/csp-report', [], [], [], ['CONTENT_TYPE' => 'application/csp-report'], json_encode($report))->assertNoContent();
        $this->call('POST', '/api/csp-report', [], [], [], ['CONTENT_TYPE' => 'application/csp-report'], json_encode($report))->assertNoContent();
        $this->call('POST', '/api/csp-report', [], [], [], ['CONTENT_TYPE' => 'application/csp-report'], 'not json')->assertNoContent();

        Log::shouldHaveReceived('warning')->once()->withArgs(fn ($message, $context) => $context === [
            'directive' => 'script-src-elem',
            'blocked' => 'tracker.example',
            'page' => '/about',
        ]);
    }
}
