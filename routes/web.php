<?php

use App\Support\SiteLocale;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Old language links (/locale/en): open the same page in that language.
Route::get('locale/{locale}', function (string $locale) {
    abort_unless(in_array($locale, config('app.available_locales'), true), 404);
    Session::put('locale', $locale);

    $previous = Request::create(url()->previous());

    // The admin has no language URLs; it reads the session.
    if ($previous->is('admin', 'admin/*')) {
        return redirect()->back();
    }

    return redirect(SiteLocale::switchUrl($locale, $previous));
})->name('switchLan');

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Route::get('sitemap.xml', 'App\Http\Controllers\SitemapController')->name('sitemap');

// The public site, once in Arabic at the root and once in English under /en
// (route names prefixed "en."). See App\Support\SiteLocale.
$site = function () {
    Route::get('/', function () {
        return view('home.index');
    })->name('home');

    Route::post('/appointment/create', 'App\Http\Controllers\AppointmentController@store')
        ->middleware('throttle:public-forms')
        ->name('appointment.store');

    Route::get('/about', function () {
        return view('about.index');
    })->name('about');

    Route::get('/testimonials', function () {
        return view('testimonials.index');
    })->name('testimonials');

    Route::get('/services', 'App\Http\Controllers\ServicesController@index')
        ->name('services.index');
    Route::get('/services/fetch_data', 'App\Http\Controllers\ServicesController@fetch_data')
        ->name('services.fetch-data');
    Route::get('/service-details/{id}', 'App\Http\Controllers\ServicesController@serviceDetails')
        ->name('services.service-details');

    Route::get('/blogs', 'App\Http\Controllers\BlogsController@index')
        ->name('blogs.index');
    Route::get('/blogs/fetch_data', 'App\Http\Controllers\BlogsController@fetchData')
        ->name('blogs.fetch-data');
    Route::get('/blog-details/{id}', 'App\Http\Controllers\BlogsController@blogDetails')
        ->name('blogs.blog-details');

    Route::get('/diet-plan/{id}', 'App\Http\Controllers\DietPlanController@fetchDiet')
        ->name('diet-plan.show');

    Route::get('/contact-us', function () {
        return view('contact-us.index');
    })->name('contact-us');

    Route::post('/contact/create', 'App\Http\Controllers\ContactController@store')
        ->middleware('throttle:public-forms')
        ->name('contact.store');

    Route::get('/privacy-policy', function () {
        return view('privacy.index');
    })->name('privacy');
};

Route::middleware('site.locale')->group($site);
Route::middleware('site.locale')->prefix(SiteLocale::PREFIXED)->name(SiteLocale::PREFIXED.'.')->group($site);
