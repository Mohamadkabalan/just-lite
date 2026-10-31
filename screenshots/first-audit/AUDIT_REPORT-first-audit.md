# Just Lite — Site Audit Report

**Scope:** `master` at `7031741` (Laravel 13.39.0, PHP 8.3, MariaDB 11.8 with a copy of the production database, production uploads from `public.zip`).
**Date:** 2026-10-31. **Mode:** inspect only. No project code was changed. This report and `screenshots/` are the only new files.

## Status after fixes (branch `fix/audit-issues`, not merged or pushed)

| Issue | Status |
|---|---|
| C1 Production runs old code | **Your action:** merge the branch into `master` and deploy (see "Still to do") |
| C2 Voyager Compass | **Fixed:** Compass is disabled in every environment (Voyager 1.8.0.4). Setting `APP_ENV=production` on the server is still recommended |
| H1 Two phone numbers | **Fixed:** +961 70 779 100 everywhere, set in `config/site.php` |
| H2 English home too wide, no tablet menu | **Fixed:** 375/768px wide, menu present |
| H3 Lost line breaks | **Fixed** on blog, service and diet-plan detail pages |
| H4 Package names cut off | **Fixed** (green shape 190 → 240px) |
| M1 5 MB pages | **Partly fixed:** `/blogs` 5.3 → 2.4 MB (medium thumbnails, lazy loading), and banners have a `srcset`. Home is still about 5 MB until the 1 MB PNG banners are re-uploaded as JPEG (about 100 KB each; files are ready) |
| M2 CDN Bootstrap on detail pages | **Fixed:** removed |
| M3 RTL Bootstrap in English | **Fixed:** English loads the LTR build |
| M4 `/shop` demo | **Fixed:** route and view removed (404) |
| M5 Empty About background | **Fixed** (no more `/storage/` 403). The blank half stays until an About image is uploaded in the admin |
| M6 Small tap targets | **Fixed:** 44×44 / 49×25 / 30×27px |
| M7 Deploy check, Docker PHP | **Fixed:** health checks require a rendered page, and Docker runs PHP 8.4. Checking the PHP handler on the server is still **your action** |
| M8 Missing service/diet-plan images | **Your action:** check `storage/app/public/services` and `weekly-diet-plans` on the server |

Before/after screenshots: `screenshots/` (before) and `screenshots/after-fixes/` (after; `/shop` is gone).

## Summary

| Level | Issues |
|---|---|
| 🔴 Critical | 2 |
| 🟠 High | 4 |
| 🟡 Medium | 8 |
| 🟢 Low | 13 |
| **Total** | **27** |

### Fix these first

1. **Deploy `master` to production (C1).** Production most likely still runs the old code. That code has the Voyager media-manager hole, which lets an uploaded PHP file execute, plus the old 500 errors. Check the PHP 8.4 switch (M7) before the first deploy.
2. **Close Voyager Compass for good (C2).** Confirm `APP_ENV=production` and `APP_DEBUG=false` on the server, and remove the Compass routes so a wrong `.env` can never expose it.
3. **Fix the phone number (H1).** The site shows two different numbers, and WhatsApp and "call" go to different ones.
4. **Fix the English home page on phones and tablets (H2).** A one-line CSS typo makes the page wider than the screen, and on tablets the menu disappears.
5. **Keep line breaks in recipes and service text (H3).** Every recipe is shown as one run-on paragraph.

**Already fine:**
- All 286 routes resolve to an existing controller method and view.
- 24/24 tests pass, and Larastan level 5 is clean.
- `composer audit` and `npm audit` report 0 vulnerabilities.
- No N+1 queries: every page runs 3–12 queries and none repeats.
- Every POST form has CSRF protection, and all admin routes require login.
- The CI fixture contains no personal data.
- No page has a viewport-meta problem.

---

## 🔴 Critical

### C1. Production is most likely still running the old, vulnerable code
- **Where:** production server; `.htaccess:6-8`, `.github/workflows/ci-cd.yml` (deploy job), `DEPLOYMENT.md`
- **What's wrong:** until commit `7031741`, the root `.htaccess` selected **PHP 8.1** for the web server, and Laravel 11–13 cannot run on 8.1. The deploy job also skips itself until the `DEPLOY_SSH_KEY` and `DEPLOY_SSH_USER` secrets are set. So unless a deploy has run since the merge, the live site still has every issue fixed on `master`:
  - Voyager media manager: an image with PHP appended, named `x.php`, is stored in `/storage` and **executes** (CVE-2024-55417; reproduced locally).
  - `/locale/<anything>` breaks the site for that visitor (500 on every page).
  - Detail pages return 500 for unknown ids.
  - No validation on the contact and appointment forms.
  - No rate limiting and no security headers.

  I did not contact production, so this is an inference from the repository.
- **Suggested fix:**
  1. Finish the one-time setup in `DEPLOYMENT.md`: deploy key, secrets, and optionally a required reviewer.
  2. Check M7.
  3. Run the workflow on `master`.
  4. Afterwards, look in `storage/app/public` on the server for any `.php`, `.phtml` or `.phar` files that may already have been uploaded.
- **Effort:** medium

### C2. Voyager Compass remote code execution is still in the code, guarded only by `APP_ENV`
- **Where:** `packages/voyager/routes/voyager.php:123-130`, `packages/voyager/src/Http/Controllers/VoyagerCompassController.php:24`, production `.env`
- **What's wrong:** Compass (CVE-2025-32931 command execution, CVE-2024-55415 path traversal, CVE-2024-55416 XSS) has no upstream fix. It's only blocked when `APP_ENV` is not `local`. If production's `.env` says `APP_ENV=local`, any admin account can run commands on the server. I couldn't see the production `.env`.
- **Suggested fix:** set `APP_ENV=production` and `APP_DEBUG=false` on the server, then run `php artisan config:cache`. Also remove the Compass route group, or make the controller always return 403, so this no longer depends on configuration. Record it in `packages/voyager/PATCHES.md`.
- **Effort:** quick

---

## 🟠 High

### H1. Two different phone numbers on the site
- **Where:** `resources/lang/ar/translation.php:40` (shows ٩٦١٧٠٧٧٩١٠٠+ = **+961 70 779 100**), `resources/lang/en/translation.php:40` (**+961 79 779 100**), `resources/views/contact-us/index.blade.php:49` (`tel:+96179779100`), `resources/views/layouts/footer/index.blade.php:136,187` (WhatsApp `96170779100`)
- **What's wrong:** Arabic visitors see a different number from English visitors. The contact page's "call" link and the WhatsApp buttons go to different numbers, so one of them reaches the wrong person (or nobody).
- **Suggested fix:** confirm the correct number with the owner and put it in one place, such as a `config/site.php` value or a Voyager setting. Use it for the displayed text, `tel:` and WhatsApp links.
- **Effort:** quick

### H2. English home page is wider than the screen on phones and tablets, and loses its menu on tablets
- **Where:** `public/css/responsive.css:149-150` (inside `@media (max-width: 767px)`) and `:1080-1081` (inside `@media (min-width: 768px) and (max-width: 991px)`); `/` in English at 375px and 768px. Screenshots: `home-en-mobile.png`, `home-en-tablet.png`
- **What's wrong:** `transform: translateX(-50%), translateY(0);` has a comma, so it's invalid CSS and the browser drops it. The banner slider dots keep `left: 50%` with nothing correcting it, and end up past the right edge.
  - **Phones:** the page becomes **525px** wide, so Chrome zooms the whole page out to about 71%, with a blank strip on the right.
  - **Tablets:** the page becomes **1114px** wide. The menu script decides it's on a desktop, while the CSS still hides the desktop menu, so **there is no navigation at all**.

  Arabic is unaffected because `rtl.css` overrides the rule.
- **Suggested fix:** change both lines to `transform: translateX(-50%) translateY(0);` (no comma), or set `left: auto` in those two media queries. Then re-shoot `home-en-mobile` and `home-en-tablet`.
- **Effort:** quick

### H3. Recipes and service descriptions lose their line breaks
- **Where:** `resources/views/blogs/blog-details.blade.php:37`, `resources/views/services/service-details.blade.php:41`, `resources/views/diet-plan/plan-details.blade.php:37`, `resources/views/blogs/blogs-content.blade.php:13`, `resources/views/services/services-content.blade.php:13`; e.g. `/blog-details/2`
- **What's wrong:** the stored texts contain line breaks (9–18 per recipe), but the views print them with `{{ }}` only. Ingredient lists and steps run together into one long paragraph.
- **Suggested fix:** add `style="white-space: pre-line"` (or a CSS class) to the text containers. Alternatively output `{!! nl2br(e($text)) !!}`, which stays escaped.
- **Effort:** quick

### H4. Package names on the home page run off the green header on mobile
- **Where:** `resources/views/home/packages.blade.php:13-15`, `public/css/style.css:3931-3945` (`.pricing-card::before`, 190px tall with 400px radii); `/` at 375px in both languages
- **What's wrong:** long package names wrap onto two lines. The white text runs past the edges of the green half-circle onto the white card, so the start and end of the name disappear. For example, English shows "rogram For 3 Mont…", and the Arabic name of the 3-month program loses letters at both ends.
- **Suggested fix:** on small screens, make the green shape taller or square-cornered behind the title (for example `height: 230px` and smaller radii under 576px), or give the title span horizontal padding and a smaller font.
- **Effort:** quick

---

## 🟡 Medium

### M1. Home and blog pages weigh over 5 MB
- **Where:** `/` (5.2 MB, 56 requests), `/blogs` (5.3 MB); `storage/app/public/banners/January2023/*.png` (~1 MB each), `resources/views/blogs/blogs-content.blade.php:6`
- **What's wrong:** the three home banners are 1020×860 PNG photos of about 1 MB each. The blog list loads the full-size originals (700–900 KB each), even though Voyager already created `-medium` (528px) and `-small` copies. On mobile data that's slow and expensive.
- **Suggested fix:**
  - Re-export the banners as WebP or JPEG (about 100–200 KB each).
  - Show the `-medium` copies in the blog and service lists. They're built from raw query rows (`DB::table(...)`), so either load `App\Blog` / `App\Service` models with Voyager's `Resizable` trait and call `->thumbnail('medium')`, or insert `-medium` before the file extension.
  - Add `loading="lazy"` to below-the-fold images.
- **Effort:** medium

### M2. Blog and service detail pages load a second, conflicting Bootstrap from a CDN
- **Where:** `resources/views/blogs/blog-details.blade.php:12`, `resources/views/services/service-details.blade.php:12`
- **What's wrong:** these pages load Bootstrap **5.0.2 (LTR)** from jsDelivr on top of the site's own Bootstrap 5.1.3 RTL build. Three visible effects:
  - Footer links become underlined and the font changes.
  - Footer padding is doubled (48px on both sides).
  - In English on a 375px phone the page is 382px wide, which causes slight sideways scrolling.

  The file also has no integrity hash.
- **Suggested fix:** delete both `<link>` lines; the layout already loads Bootstrap.
- **Effort:** quick

### M3. English pages use the right-to-left Bootstrap build
- **Where:** `resources/views/layouts/master.blade.php:13`
- **What's wrong:** `bootstrap.rtl.min.css` is loaded for both languages. In English, Bootstrap's direction-aware utilities are mirrored. I confirmed this in the browser: the footer's `ps-5` puts its padding on the right instead of the left, and `text-start`, `me-*` and `float-start` are flipped the same way.
- **Suggested fix:** load `bootstrap.min.css` (same version, 5.1.3) when the locale isn't `ar`, the same way `rtl.css` is already conditional. Then check the English pages for spacing.
- **Effort:** quick

### M4. `/shop` is a leftover template demo that anyone can open
- **Where:** `routes/web.php:43-45`, `resources/views/shop/index.blade.php`; `/shop`
- **What's wrong:** it lists fake products such as "Lemon $50.00" and "Apple $30.00", with 6 broken images. About 30 links point to `.html` pages that don't exist, and the filters and pagination do nothing. Nothing links to it and it isn't in the sitemap, but `robots.txt` allows it to be indexed.
- **Suggested fix:** remove the route (and view) until there's a real shop, or return 404.
- **Effort:** quick

### M5. "About" section shows an empty half and triggers a 403 on every view
- **Where:** `resources/views/home/about.blade.php:5`; `/` and `/about`
- **What's wrong:** `abouts.image` is empty, so the view writes `background-image: url(storage/)`. The section has a large blank area with a lone play button, and every visit sends a request to `/storage/` that gets a 403.
- **Suggested fix:** upload an About image in the admin. Also wrap the `<style>` block in `@if($about->image)` and use `asset()`.
- **Effort:** quick

### M6. Several controls are too small to tap
- **Where:** footer social icons, 20×26px (all pages); testimonial slider dots, 49×**5**px (`public/css/style.css:3806`, `/` and `/testimonials`); results carousel buttons, 30×23px (`/`). Mobile and tablet.
- **What's wrong:** these are below the 24×24px minimum (WCAG 2.2) and far below the recommended 44×44px, so they're hard to hit on a phone.
- **Suggested fix:** give these links and dots a bigger hit area with padding. A dot can stay visually small with a 24–44px transparent target around it.
- **Effort:** quick

### M7. Check the first deploy's PHP switch and health check
- **Where:** `.htaccess:6-8` (`AddHandler application/x-httpd-ea-php84___lsphp`), `scripts/deploy.sh:70-80`, `docker-compose.yml:17` / `docker/app/Dockerfile:6`
- **What's wrong:**
  - The handler name follows cPanel/EasyApache naming, and nothing confirms Hostinger serves PHP 8.4 under it.
  - The deploy health check only checks for HTTP 200. A page served with the wrong PHP, or a maintenance page with a 200 status, would pass.
  - The local Docker stack uses PHP 8.3 while CI and production use 8.4.
- **Suggested fix:**
  - On the server, compare `php -v` under `/opt/alt/php84` with what the web server actually runs, for example via `php artisan about` and the hPanel PHP selector.
  - Make `health_check` also look for a known string in the page, such as the site title.
  - Set the Docker default to `PHP_VERSION=8.4`.
- **Effort:** quick

### M8. Service and diet-plan images are missing from the uploads copy
- **Where:** `storage/app/public/services/`, `storage/app/public/weekly-diet-plans/`; `/services`, `/service-details/*`, `/diet-plan/*`
- **What's wrong:** these folders aren't in `public.zip`, so those images return 404 locally. I can't tell whether they exist on the server.
- **Suggested fix:** check that the folders exist on the server. If they're missing there too, re-upload the images in the admin.
- **Effort:** quick

---

## 🟢 Low

### L1. Unused and leftover files
- **Where:**
  - Views no page includes: `resources/views/welcome.blade.php`, `home/appointment.blade.php`, `home/blog.blade.php`, `home/banner-bottom-area.blade.php`, `home/diet-plan-area.blade.php`.
  - `server.php` (not needed since Laravel 10), and `resources/css/app.css` (empty).
  - Sass sources and source maps that are publicly downloadable: `public/css/*.scss` and `*.map`.
  - IDE settings committed to a public repo: `.idea/`.
- **What's wrong:** dead code makes the project harder to read. The `.scss` and `.map` files are served to anyone who asks.
- **Suggested fix:** delete them, and add `.idea/` to `.gitignore`.
- **Effort:** quick

### L2. Packages that may be unused
- **Where:** `composer.json` (`laravel/sail`, `laravel/sanctum`), `routes/api.php`, `app/Models/User.php:9-13`
- **What's wrong:**
  - Sail has been replaced by the Docker setup.
  - Sanctum only backs `GET /api/user`. There are no tokens (`personal_access_tokens` is empty) and no API clients.
  - An additive Sanctum migration (`expires_at`) is pending and will run on the next deploy. It's safe.
- **Suggested fix:** remove `laravel/sail`. If no API is planned, also remove Sanctum, `routes/api.php` and `HasApiTokens`.
- **Effort:** quick

### L3. Duplicated code
- **Where:**
  - 37 copies of `@if($lang=='en') {{ x_en }} @else {{ x_ar }} @endif` across 13 views.
  - `BlogsController` and `ServicesController` share the same index and pagination logic.
  - `blog-details` and `service-details` views are nearly identical.
  - The appointment form markup exists twice (the footer modal and the unused `home/appointment`).
  - Every home section queries the database inside Blade (e.g. `home/banner.blade.php:1`).
- **What's wrong:** every change has to be made in several places, which is how the `title_Ar`/`title_ar` mismatch happened earlier.
- **Suggested fix:**
  - Add a small model trait or accessor, e.g. `$model->localized('title')`.
  - Move queries into controllers or a view composer.
  - Share one detail-page partial.
- **Effort:** medium

### L4. Security hardening not yet done
- **Where:** `config/session.php:171` (`SESSION_SECURE_COOKIE` unset), `config/session.php` (no `serialization` key, so `php` is used), `config/cache.php` (no `serializable_classes`), `app/Http/Middleware/SecurityHeaders.php` (no CSP)
- **What's wrong:** these are defence-in-depth gaps rather than open holes:
  - The session cookie isn't marked Secure.
  - Cache unserialization is unrestricted, and sessions use PHP serialization.
  - There's no Content-Security-Policy.
- **Suggested fix:**
  - Set `SESSION_SECURE_COOKIE=true` in the production `.env`.
  - Switch sessions to `json`; this logs admins out once.
  - Allow-list Voyager's `Menu`/`MenuItem` classes, then set `serializable_classes`.
  - Add a CSP in report-only mode first.
- **Effort:** medium

### L5. Raw HTML for package features
- **Where:** `resources/views/home/packages.blade.php:18` (`{!! $package->features_en !!}`)
- **What's wrong:** admin-entered HTML is output unescaped. That's acceptable while only trusted admins edit packages, but it's stored XSS if that ever changes. The other `{!! !!}` uses (pagination links, a static translation) are safe.
- **Suggested fix:** keep it as is for now, or run the HTML through a purifier (e.g. `mews/purifier`) if more people get admin access.
- **Effort:** quick

### L6. A third-party Mailchimp list is still wired up
- **Where:** `public/js/custom.js:316`
- **What's wrong:** `ajaxChimp` points at the template vendor's (EnvyTheme) demo Mailchimp list. The newsletter form is commented out, so nothing is sent today. If it's turned back on, visitors' emails go to a third party.
- **Suggested fix:** remove the `ajaxChimp` block (and `ajaxchimp.min.js`), or point it at your own list.
- **Effort:** quick

### L7. No browser caching for static files
- **Where:** `public/.htaccess`
- **What's wrong:** `public/.htaccess` sets no caching rules, so locally CSS, JS, fonts and images are sent without `Cache-Control` or `Expires`, and repeat visitors revalidate every file. I didn't check whether the production server adds defaults.
- **Suggested fix:** add `mod_expires` rules to `public/.htaccess`. LiteSpeed honours them. For example, a 1 month lifetime for images, fonts, CSS and JS.
- **Effort:** quick

### L8. Error pages are generic and English-only
- **Where:** `resources/views/errors/*.blade.php`; e.g. `/this-page-does-not-exist`
- **What's wrong:** the 404 and other error pages are Laravel's default "Not Found" screen, with no logo, no navigation and no Arabic.
- **Suggested fix:** create a `404.blade.php` (and `500`) that extends `layouts.master` and uses the translation files.
- **Effort:** quick

### L9. Orphan pages and an inconsistent sitemap
- **Where:** `public/sitemap.xml`, `resources/views/home/navbar.blade.php:39-48`, `resources/views/diet-plan/plan-details.blade.php:1`
- **What's wrong:**
  - `/services` and `/diet-plan/*` aren't linked from anywhere (the "Services" and "Shop" menu items are commented out).
  - `/services` is still in the sitemap, and the sitemap uses `http://www.just-lite.com` while the site lives at `https://just-lite.com`.
  - The diet-plan page highlights "Blogs" in the menu.
- **Suggested fix:**
  - Decide whether Services should be in the menu.
  - Regenerate the sitemap with `https://just-lite.com` URLs.
  - Set `$page` correctly on the diet-plan page.
- **Effort:** quick

### L10. Admin lists load every row at once
- **Where:** Voyager BREAD settings (`data_types.server_side = 0` for all 17 BREADs)
- **What's wrong:** the Contacts (93 rows) and Appointments (59) lists load everything into the browser. That's fine now but slow as they grow.
- **Suggested fix:** enable "Server-side pagination" for Contacts and Appointments in *Admin → BREAD*.
- **Effort:** quick

### L11. Small content and polish issues
- **Where:** `resources/lang/*/translation.php:41`, `/` testimonials, `/blog-details/*` on desktop, the WhatsApp button on mobile
- **What's wrong:**
  - The copyright year is hard-coded to 2022.
  - The only testimonial is in English on the Arabic page, and its full stop jumps to the start (".down 10 kg").
  - Blog photos (1054px) are stretched to 1296px on desktop and look soft.
  - The floating WhatsApp button covers text while scrolling on phones.
- **Suggested fix:**
  - Use `date('Y')` for the copyright year.
  - Add an Arabic testimonial, or wrap English quotes in `dir="ltr"`.
  - Cap the image width or upload larger photos.
  - Give the page bottom padding equal to the button size on mobile.
- **Effort:** quick

### L12. Missing index
- **Where:** `info_details.info_id`
- **What's wrong:** the column used to join details to infos has no index. That's harmless at 13 rows.
- **Suggested fix:** add an index in a migration when convenient.
- **Effort:** quick

### L13. Minor outdated packages
- **Where:** `vite` 8.3.4 → 8.3.5, `larastan/larastan` 3.13.0 → 3.13.2
- **What's wrong:** patch releases are available. `guzzlehttp/guzzle` 8 also exists, but Laravel 13 still requires Guzzle 7, so it isn't a real upgrade option.
- **Suggested fix:** update with the next routine dependency bump.
- **Effort:** quick

---

## Checked and fine

- **Routes:** all 286 resolve to an existing controller method, 7 are closures, and every referenced view exists.
- **Admin protection:** of 265 admin routes, only `admin/login` and `admin/voyager-assets` are public, as intended.
- **CSRF:** every write route is in the `web` group. The two `_ignition/*` exceptions are dev-only, and the deploy uses `composer install --no-dev`.
- **Tests:** `php artisan test` passes 24 tests (106 assertions), and Larastan level 5 reports no errors.
- **Audits:** `composer audit` and `npm audit` report 0 vulnerabilities.
- **Queries:** pages run between 3 (`/shop`, `/contact-us`) and 12 (`/`) queries, with no query repeated, so there's no N+1.
- **Forms:** contact and appointment validation, rate limiting (10 per minute per IP) and CSRF are in place, and the admin login is throttled (5 attempts).
- **Uploads:** the copy of production uploads (71 files) holds only PNG/JPG images and no scripts. `/storage` refuses to serve `.php` files, including double extensions like `x.php.jpg`.
- **CI fixture:** `tests/fixtures/ci-database.sql` has no rows in personal tables and no emails or phone numbers.
- **Secrets:** `.env` has never been committed, and the tracked files contain no keys or tokens.
- **Responsive basics:** every page has `<meta name="viewport" content="width=device-width, initial-scale=1.0">`. There's no horizontal scrolling in Arabic at any size, or in English outside H2/M2. The mobile menu opens with all 5 links, each 55px tall. The booking modal fits 375px and 768px screens.
- **Logs:** the local `storage/logs/laravel.log` only holds errors I triggered while probing before the earlier fixes (14:13–14:25). Nothing has been logged since.

## Not covered

- **Production logs:** I didn't read the production `storage/logs/laravel.log`. Downloading it would show real visitor errors.
- **Production `.env`:** the production `.env` and PHP version weren't checked (C1, C2, M7).
- **Server folders:** whether the `services/` and `weekly-diet-plans/` uploads exist on the server wasn't checked (M8).

## How this was checked

- **App:** the Docker stack in this repo (`docker compose up -d`), at http://localhost:8080. Docker stood in for `php artisan serve` because there's no PHP on the Windows host. `npm run dev` wasn't needed, because no page includes `@vite` and the site's CSS/JS is served from `public/`.
- **Links:** I crawled all public pages in both languages and checked every internal link and asset.
- **Screenshots:** Playwright 1.64.0 with Chrome 156, installed outside the project so `package.json` is unchanged. Each page was scrolled through so scroll-triggered animations rendered. Google Tag Manager and Analytics were blocked so test visits didn't reach your real analytics. No form was submitted.
- **Query counts:** taken by handling each page inside the app container with a query listener.

### Screenshots (`screenshots/`)

- **Naming:** `{page}-{size}.png` is Arabic, the site default and right-to-left. `{page}-en-{size}.png` is English.
- **Sizes:** `mobile` is 375px, `tablet` is 768px, `desktop` is 1440px.
- **Pages:** `home`, `about`, `testimonials`, `shop`, `services`, `blogs`, `contact-us`, `blog-details` (`/blog-details/2`), `service-details` (`/service-details/9`), `diet-plan` (`/diet-plan/2`).
- **Extras:** `home[-en]-{mobile,tablet}-menu-open.png` and `home[-en]-{mobile,tablet}-booking-modal.png`. There's no `home-en-tablet-menu-open.png`, because that view has no menu (H2).
- **Odd sizes are evidence:** `home-en-mobile.png` is 525px wide and `home-en-tablet.png` is 1114px wide. Those are the overflow in H2.
- **Map area:** the Google Map on `/contact-us` appears blank in full-page captures. The map itself loads (embed and tiles return 200), so this isn't a site issue.

### Ruled out (false positives)

- **LinkedIn link:** returns HTTP 999 to automated requests; it works in a browser.
- **Google Tag Manager:** the `gtm.js?id=` URL with an empty id is assembled at runtime; the real container ID is in the page.
- **Google Maps:** the Node link checker got a 404 from the embed, but it renders in Chrome.
- **Hero alignment:** the English hero heading's right alignment isn't caused by the RTL Bootstrap. It computes `text-align: start` either way.
