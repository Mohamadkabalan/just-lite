<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Browsers post Content-Security-Policy violations here (no session or CSRF
// token in the api group); 60 per minute per IP via the api limiter.
Route::post('/csp-report', 'App\Http\Controllers\CspReportController')->name('csp-report');
