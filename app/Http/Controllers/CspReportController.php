<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Receives Content-Security-Policy violation reports (see
 * App\Http\Middleware\SecurityHeaders) and logs each distinct one at most
 * once a day, so the log shows what an enforced policy would block.
 */
class CspReportController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $report = json_decode($request->getContent(), true)['csp-report'] ?? null;

        if (is_array($report)) {
            $directive = Str::limit((string) ($report['effective-directive'] ?? $report['violated-directive'] ?? ''), 60, '');
            $blocked = Str::limit((string) ($report['blocked-uri'] ?? ''), 200, '');
            // The blocked host is enough to group reports; full URLs can hold personal data.
            $blockedHost = parse_url($blocked, PHP_URL_HOST) ?: $blocked;

            if ($directive !== '' && Cache::add('csp-report:'.sha1($directive.'|'.$blockedHost), true, now()->addDay())) {
                Log::warning('CSP (report-only) would block', [
                    'directive' => $directive,
                    'blocked' => $blockedHost,
                    'page' => parse_url((string) ($report['document-uri'] ?? ''), PHP_URL_PATH),
                ]);
            }
        }

        return response()->noContent();
    }
}
