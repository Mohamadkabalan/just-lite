# Just Lite – Full Website Audit

**Date:** 2026-10-31
**Code audited:** `master` at `2d56911` (Laravel 13.39.0, PHP 8.4.27), running in the local Docker copy with the production database dump.
**Not audited directly:** the live site https://just-lite.com. It was not contacted, and it still runs older code because the last deploy failed (see C1).

**How this was checked**
- **Code:** every controller, model, middleware, view, route and config file was read. Larastan (level 5), `composer audit`, `npm audit`, `composer outdated`, `npm outdated` and the test suite were run, and SQL queries were counted per page.
- **Browser:** Playwright with Chrome 156 visited every public page (all 19 records, Arabic and English) and 74 admin screens. It took screenshots at 375 / 768 / 1024 / 1440 px (317 files) and ran axe-core 4.14 on 78 public and 24 admin page views. It also tested the forms, error pages, maintenance mode and keyboard focus, checked every link, and measured load times, unthrottled and on an emulated slow 4G phone.
- **Analytics:** Google Tag Manager was blocked during all browser runs so the tests did not reach the real analytics. The `net::ERR_FAILED` console line on every page comes from that block, not from the site.

**What the audit changed locally, and put back:**
- No code was changed.
- Two throwaway accounts (one "admin", one "user" role) were created in the local database to test logins and permissions, then deleted.
- The form tests saved 2 contact messages and 2 bookings locally; they were deleted.
- Maintenance mode was switched on locally for one test and switched off again.
- The rate-limit and login-lockout counters were cleared.

> **Do not commit this report to the public repository** until C1 and C2 are fixed: it describes how the live site can currently be attacked.

---

## 1. Summary

### Issues per priority

| Priority | Count |
| --- | --- |
| 🔴 Critical | 2 |
| 🟠 High | 7 |
| 🟡 Medium | 24 |
| 🟢 Low | 22 |
| **Total** | **55** |

### Health score per category

| | Category | Score | Main reasons |
| --- | --- | --- | --- |
| A | Error handling & edge cases | 4/10 | Correct status codes, but every error page is Laravel's plain English default; no empty states |
| B | Bugs & code quality | 6/10 | Tests pass and Larastan is clean. But the booking date picker is broken, there's a placeholder regression, and the views hold logic and dead code |
| C | Security | 4/10 | The code is in good shape, but the live site doesn't have the fixes yet, and the "user" role can take over the admin |
| D | Performance | 4/10 | Home page is 5.2 MB; 17.8 s to fully load on a slow phone connection |
| E | Responsiveness | 7/10 | No sideways scrolling on any public page. English menu wraps at 1024 px; some small tap targets |
| F | UX & design consistency | 5/10 | Consistent theme. Language label is wrong, messages are partly English, no breadcrumbs on detail pages |
| G | SEO | 3/10 | Every page says it's the home page (canonical); no meta descriptions; English can't be indexed |
| H | Accessibility | 3/10 | Low colour contrast, unlabelled icons and fields, invisible focus on buttons |
| I | Forms & emails | 4/10 | Validation works. Date picker broken, no spam protection, no notification when someone books |
| J | Legal & compliance | 1/10 | No privacy policy or consent, but analytics and Google embeds load for everyone |
| K | Configuration & deployment | 5/10 | Current versions, good deploy script. Deploy is failing; no scheduled backups or error alerts |

### Top 10 things to fix first

1. **H1:** Remove the "1010 x 690" placeholder in the About section, or upload an About image, **before the next deploy**. Otherwise the deploy puts it on the live home page.
2. **C1:** Fix the deploy (SSH key), so the security fixes that are already in `master` reach the live site.
3. **C2:** Take user management away from the "user" role, or make that account a real admin on purpose.
4. **H7:** On the server, set `APP_ENV=production`, `APP_DEBUG=false` and `SESSION_SECURE_COOKIE=true`.
5. **H2:** Remove the jQuery UI calendar from the booking date field; bookings currently fail for people who use it.
6. **M8:** Send yourself an email or WhatsApp message for every new booking and contact message.
7. **H4 + M13:** Give every page its own canonical URL, title and meta description.
8. **H6:** Add a privacy policy and a consent banner, and only load Google Tag Manager after consent.
9. **H3:** Make branded, bilingual error pages (404, 403, 419, 429, 500, 503) with a link back home.
10. **M1:** Replace the three 1 MB banner PNGs with the prepared JPEGs, and resize the "Results" photos.

---

## 2. All issues, most important first

### 🔴 Critical

#### C1 · The live site still runs the old, vulnerable code
- **Category:** Security / Deployment
- **Location:** production server; GitHub Actions run [41228862268](https://github.com/Mohamadkabalan/just-lite/actions/runs/41228862268), step "Deploy over SSH"
- **What's wrong:** `master` is pushed, but the deploy step failed after 1 second with SSH exit code 255, so the server never received the new code. The live site still lacks:
  - the upload fix for CVE-2024-55417 (an uploaded PHP file can run on the server)
  - Compass switched off
  - the output-escaping (XSS) fixes
  - the security headers
  - the form rate limits
- **Why it matters:** these are known, published Voyager vulnerabilities, and attackers scan for them automatically.
- **Fix:**
  1. First fix H1, or upload an About image.
  2. Open the failed run while signed in to GitHub and read the SSH error.
  3. Correct `DEPLOY_SSH_KEY` (the whole key file) and `DEPLOY_SSH_USER`, or add the public key to the server's `~/.ssh/authorized_keys` with the `command=` line from `DEPLOYMENT.md`.
  4. Click "Re-run failed jobs".
  5. After the deploy, search the server's `storage/app/public` for `.php`, `.phtml` and `.phar` files.
- **Effort:** quick

#### C2 · Any account with the "user" role can take over the admin account
- **Category:** Security / Authorization
- **Location:** Admin → Roles → "user" (database `permission_role`); `packages/voyager/src/Policies/UserPolicy.php:49`
- **What's wrong:** the "user" role has 77 of the admin's 95 permissions, including `browse/read/edit/add/delete_users`, all contact messages, all appointments and the media manager. It lacks only the BREAD builder, database, Compass, menus, roles and settings.
  - Voyager lets anyone with `edit_users` change *another* user's role and password.
  - Tested with a throwaway "user" account: it could list every user, read every contact message and booking, and open the admin's edit form, which offers the "Administrator" role and a new password. Nothing was saved.
  - One real account in the database has this role.
- **Why it matters:** whoever has that login (or steals its password) can make themselves admin, lock you out, and read clients' personal and health-related messages.
- **Fix:**
  - If the account should be a full admin, give it the admin role on purpose.
  - Otherwise, in Admin → Roles → user, untick at least all `users` permissions. Also untick `contacts`, `appointments` and `media` unless that person needs them.
  - Change that account's password, and make sure you know who uses it.
- **Effort:** quick

### 🟠 High

#### H1 · About section shows a "1010 x 690 – Powered by HTML.COM" placeholder (regression)
- **Category:** Bug (regression from my commit `eec655f`)
- **Location:**
  - `resources/views/home/about.blade.php:3-9`
  - `public/css/style.css:1696-1708`
  - visible on `/` and `/about` at 992 px and wider (see `home-1024.png`, `home-en-1024.png`)
- **What's wrong:**
  - The fix for M5 of the first audit stopped the broken `url(storage/)` background, which had left that half of the section plain white.
  - Without that line, the theme's default background now shows: `images/about/about-bg.png`, a grey "1010 x 690" placeholder graphic.
  - There's no About image in the database, so the placeholder shows.
  - It's on `master` and not live yet, but the next successful deploy would publish it.
- **Why it matters:** it's on the home page and looks broken.
- **Fix:** either
  - upload an About image in Admin → Abouts before deploying (no code change), or
  - output `.about-area::before { background-image: none; }` when `$about->image` is empty.
- **Effort:** quick

#### H2 · Booking form: picking a date in the calendar leaves the date empty
- **Category:** Bug / Forms
- **Location:**
  - `public/js/custom.js:190` (`$('#datetimepicker').datepicker()`)
  - `resources/views/layouts/footer/index.blade.php:38`
- **What's wrong:** the date field is a native `type="date"` input, and jQuery UI also attaches its own calendar to it.
  - Choosing a day in the jQuery UI calendar writes `mm/dd/yyyy`, which a date input rejects, so the field stays empty (tested in Arabic and English).
  - On phones the jQuery UI calendar opens *behind* the modal (`form-booking-datepicker-375.png`).
  - The form then refuses to submit ("required"), and people don't know why.
- **Why it matters:** booking is the site's main goal.
- **Fix:** remove the `.datepicker()` call and keep the native date input, with `min` set to today. jQuery UI (254 KB on every page) can then be removed entirely.
- **Effort:** quick

#### H3 · Error pages are Laravel's plain English defaults with no way back
- **Category:** Error handling
- **Location:** `resources/views/errors/*.blade.php` (published Laravel defaults)
- **What's wrong:**
  - 404 (fake URL, deleted blog `/blog-details/3`, `/diet-plan/999`), 403, 429 and 503 show a grey page that says only e.g. "404 | NOT FOUND", in English, with `lang="en"`. It has no logo, no menu and no link home (`error-404-375.png`, `error-503-maintenance-375.png`).
  - Statuses without a view, such as 405, fall back to an unstyled generic page in production.
- **Why it matters:** visitors who follow an old or mistyped link leave the site.
- **Fix:** make one branded error layout using the site header and footer, in Arabic and English, with "Back to home" and contact links. Use it for 401/403/404/419/429/500/503, plus a `4xx`/`5xx` fallback.
- **Effort:** medium

#### H4 · Every page tells Google it is the home page
- **Category:** SEO (rated High because it's a bug that can hide pages from search)
- **Location:** `resources/views/layouts/master.blade.php:52-62`
- **What's wrong:**
  - `<link rel="canonical" href="https://just-lite.com/">` is on every page, including About, Blogs, every recipe and Contact.
  - `og:title` is always "Home - Just Lite" and `og:url` is always the home page.
  - `og:locale` is always `en_US`, and `article:modified_time` is fixed at 2021.
- **Why it matters:** search engines treat the other pages as duplicates of the home page and may leave them out of results. Shared links all preview as the home page.
- **Fix:**
  - Canonical: `url()->current()`, plus `?page=` on paginated lists.
  - Per-page `og:title` / `og:description` / `og:image` / `og:url`.
  - `og:locale` per language; remove `article:modified_time`.
- **Effort:** quick

#### H5 · English menu breaks onto two lines on small laptops (992–1199 px)
- **Category:** Responsiveness
- **Location:** `resources/views/home/navbar.blade.php`, nav styles in `public/css/style.css`; `home-en-1024.png`, `about-en-1024.png`
- **What's wrong:** at 1024 px in English, "About Us" and "Contact Us" wrap onto two lines, and the menu crowds the "Book An Appointment" button. Arabic fits.
- **Why it matters:** this is a common laptop and tablet-landscape width, and it looks broken in the header.
- **Fix:**
  - Add `white-space: nowrap` to the menu links.
  - Reduce the link padding and font size between 992 and 1199 px, or switch to the hamburger menu below 1200 px.
- **Effort:** quick

#### H6 · No privacy policy or consent, while analytics and Google embeds load for every visitor
- **Category:** Legal & compliance
- **Location:**
  - `layouts/master.blade.php:75-89` (Google Tag Manager)
  - `public/css/style.css:26-27` (Google Fonts `@import`)
  - `contact-us/index.blade.php:141` (Google Maps)
  - About video (YouTube)
- **What's wrong:**
  - Google Tag Manager, Google Fonts and Google Maps load before any consent, and send visitors' IP addresses to Google.
  - There's no privacy policy, terms or imprint page, and no notice on the forms. The forms collect names, emails, phone numbers and health-related messages, and the data is kept indefinitely.
- **Why it matters:**
  - The site says it has clients "all over the world". For visitors in the EU, GDPR requires consent for analytics and a privacy notice for the forms.
  - Lebanon's Law 81/2018 also covers personal data.
- **Fix:**
  - Add a privacy policy page in Arabic and English, linked in the footer and under both forms.
  - Add a consent banner that only loads GTM after "accept" (GTM Consent Mode).
  - Self-host the two fonts, and use `youtube-nocookie.com`.
  - Decide how long to keep messages and bookings.
- **Effort:** medium

#### H7 · Production settings can't be confirmed, and debug output would expose the code
- **Category:** Security / Configuration
- **Location:** the server's `.env`; `.env.example`
- **What's wrong:**
  - Locally, a 405 error shows the full debug page, with source code, request headers, queries and versions (`error-405-en-1440.png`).
  - If production has `APP_DEBUG=true`, any error shows the same to visitors; it would be Laravel's own debug page, since Ignition isn't installed there.
  - `SESSION_SECURE_COOKIE` isn't set anywhere, so the session cookie isn't marked "Secure".
  - These were not checked on the server, as instructed.
- **Why it matters:** debug pages hand attackers file paths, settings and database details.
- **Fix:** on the server, set `APP_ENV=production`, `APP_DEBUG=false`, `SESSION_SECURE_COOKIE=true`, `LOG_LEVEL=warning`. Then run `php artisan config:cache`.
- **Effort:** quick

### 🟡 Medium

#### M1 · Pages are heavy and slow on phones
- **Category:** Performance
- **Location:**
  - the home banners (`storage/banners/January2023/*.png`, about 1 MB each)
  - the Results carousel (`home/results.blade.php:23`: six 1920 px photos shown at 500 px, all loaded immediately)
  - blog detail images (700 KB PNG)
  - `/blogs` (six 200–370 KB PNG thumbnails)
- **What's wrong:**

  | Page | Weight | Slow 4G phone: first paint / main image (LCP) / fully loaded |
  | --- | --- | --- |
  | Home | 5.2 MB | 4.1 s / 4.6 s / 17.8 s |
  | Blogs | 2.4 MB | 2.4 s / 5.3 s / 11.1 s |
  | Blog detail | 1.2 MB | 2.1 s / 7.1 s / 7.1 s |
  | Other pages | 0.5–0.8 MB | 2.4–2.9 s / 2.4–4.8 s / 4.2–5.3 s |

  - No image uses WebP/AVIF, none has `width`/`height`, and only the list thumbnails use `loading="lazy"`.
  - These numbers come from Lighthouse's slow 4G profile: 150 ms latency, 1.6 Mbps, 4× slower CPU. The local server is slower than production (Docker on Windows), so the first-byte part is pessimistic.
- **Why it matters:** most visitors are on phones; slow pages lose bookings and rank lower in Google.
- **Fix:**
  - Upload the three prepared JPEG banners (about 100 KB each, in `optimized-banners/` in the scratchpad), or WebP versions.
  - Resize the Results photos to about 1000 px and lazy-load all slides after the first.
  - Convert blog images to JPEG/WebP.
  - Add `width`/`height` to `<img>` tags.
- **Effort:** medium

#### M2 · Render-blocking, unminified CSS and JavaScript; Vite isn't used for the site
- **Category:** Performance
- **Location:** `layouts/master.blade.php:13-44` and `:128-152`; `public/css/style.css`
- **What's wrong:**
  - 13 separate stylesheets (12 in English) block the first paint.
  - `style.css` (166 KB, unminified) uses `@import` for Google Fonts, a chained, blocking request.
  - 13 script files load on every page, including jQuery UI (254 KB) used only for the broken date picker (H2), and Ajaxchimp and newsletter code for a form that doesn't exist.
  - The Vite build only compiles the unused `resources/css/app.css` and `resources/js/app.js`.
- **Why it matters:** about 2 s of the slow-4G first paint is spent waiting for CSS and fonts.
- **Fix:**
  - Bundle the theme CSS/JS through Vite (`@vite`), with fingerprinted file names and long cache headers.
  - Drop jQuery UI and Ajaxchimp.
  - Load the fonts with `<link rel="preconnect">` + `display=swap`, or self-host them.
- **Effort:** medium

#### M3 · English carousels run right-to-left
- **Category:** UX / Responsiveness
- **Location:** `public/js/custom.js:37, 52, 66, 93, 112, 137` (`rtl: true` on every Owl carousel)
- **What's wrong:** in English, the hero text is right-aligned, the dots sit on the wrong side, and the testimonials and results slide the "Arabic" way (`home-en-1024.png`, `home-en-1440.png`).
- **Fix:** `rtl: document.documentElement.dir === 'rtl'`
- **Effort:** quick

#### M4 · Language switcher always says "English"
- **Category:** UX
- **Location:** `public/js/custom.js:22`
- **What's wrong:** the script copies the *first* menu item ("English") into the button, so it reads "English" on the Arabic site too (on all 18 Arabic page views checked).
- **Why it matters:** visitors can't tell which language they're in, or how to switch.
- **Fix:** print the current language's name in the Blade header (`layouts/header/index.blade.php:46`) and remove that line.
- **Effort:** quick

#### M5 · Form messages are partly English on the Arabic site; booking feedback is a browser alert
- **Category:** Forms / UX
- **Location:**
  - `contact-us/index.blade.php:80-108` (`data-error="Please Enter Your Name"` …)
  - `public/js/contact-form-script.js:12`
  - `ContactController.php:21,24`
  - `AppointmentController.php:24,27`
  - `layouts/footer/index.blade.php:207-214`
- **What's wrong:**
  - Empty-field messages ("Please Enter Your Name", "Did you fill up the form properly?") are always in English.
  - Success messages ("Your message has been sent successfully", "appointment added successfully") are always in English.
  - The rate-limit case only says "Message not sent, please try again.".
  - Server-side validation is translated, but shows as a single line below the button rather than next to the field.
  - Bookings report through `alert()`, and the modal stays open after success (`form-contact-empty-375.png`, `form-booking-after-submit-375.png`).
- **Fix:**
  - Put `__()` messages in `data-error` and in the JSON responses.
  - Show booking results inside the modal and close it on success.
  - Place server errors under the right field.
  - Show "too many attempts, try again in a minute" on 429.
- **Effort:** medium

#### M6 · Bookings accept past dates, and phone fields accept any text
- **Category:** Forms / Validation
- **Location:**
  - `app/Http/Requests/StoreAppointmentRequest.php:25-26`
  - `StoreContactRequest.php:24`
  - the inputs `type="text"` for phone
- **What's wrong:** a booking for 2020-01-15 was accepted and saved (test row, deleted afterwards). Phone accepts any string up to 255 characters.
- **Fix:**
  - `'appointment_date' => ['required', 'date', 'after_or_equal:today']`, plus `min` on the input.
  - Phone: `regex:/^[0-9 +()-]{6,20}$/` and `type="tel"`.
- **Effort:** quick

#### M7 · No spam protection on the public forms
- **Category:** Forms / Security
- **Location:** `contact-us/index.blade.php`, the booking modal, `RouteServiceProvider.php:55`
- **What's wrong:** the only protection is 10 submissions per minute per IP. A bot that rotates IPs can fill the admin with spam.
- **Fix:** add a honeypot field plus a minimum fill time, or Cloudflare Turnstile.
- **Effort:** quick

#### M8 · You're not told when someone books or writes
- **Category:** Forms / Business
- **Location:** `AppointmentController::store`, `ContactController::store` (no mail or notification anywhere in `app/`)
- **What's wrong:** new bookings and messages only appear in the admin. Nobody is notified.
- **Why it matters:** leads go unanswered until someone happens to check the admin.
- **Fix:** send an email (queued) or a WhatsApp/Telegram message to the clinic for every new booking and contact message.
- **Effort:** medium

#### M9 · Colour contrast fails WCAG AA
- **Category:** Accessibility
- **Location:** brand green `#72ae44` in `public/css/style.css`: top bar, buttons, small green labels
- **What's wrong:** white text on `#72ae44` is 2.67:1 (AA needs 4.5:1 for normal text); green labels on white fail too. axe reports 281 failing elements across 78 public page views.
- **Fix:** use a darker green (around `#4e7f2a`) for text and button backgrounds; keep the light green for decoration.
- **Effort:** medium

#### M10 · Icon-only links and buttons have no names for screen readers
- **Category:** Accessibility
- **Location:**
  - header and footer social links (`layouts/header/index.blade.php:18-30`, `footer/index.blade.php:82-96`)
  - the WhatsApp button (`footer:187`)
  - the menu toggle
  - the modal close buttons
  - the Owl slider dots
  - the video play button (`home/about.blade.php:52`)
  - the Maps `<iframe>` (no `title`)
- **What's wrong:** axe flags 572 unnamed links and 52 unnamed buttons. A screen reader announces them as "link" or "button" with no name. The testimonial name headings are also empty (86 empty `<h3>`).
- **Fix:** add `aria-label` (e.g. "Facebook", "WhatsApp", "Open menu", "Close", "Slide 1") and `title` on the iframe, and hide empty headings.
- **Effort:** quick

#### M11 · Form fields have no labels and miss mobile keyboard hints
- **Category:** Accessibility / Mobile forms
- **Location:** contact form and booking modal inputs
- **What's wrong:**
  - Fields have only placeholders, which disappear when typing; there are no `<label>`s.
  - Phone is `type="text"`, so phones show the full keyboard.
  - No `autocomplete` (name, email, tel).
  - Inputs are 15 px, so iPhones zoom in when a field is tapped.
- **Fix:**
  - Add visible or visually-hidden `<label>`s.
  - Phone: `type="tel"` and `autocomplete="tel"`; `autocomplete="name"` and `"email"` on the other fields.
  - Input font-size 16 px.
- **Effort:** quick

#### M12 · Keyboard users can't see where they are, and the heading structure is broken
- **Category:** Accessibility
- **Location:** `public/css/style.css:66-73` (`button:focus { outline: 0 }`); all page templates
- **What's wrong:**
  - About 20 of the first 45 Tab stops on the home page have no visible focus ring (buttons, "Book Now", slider dots) (`keyboard-focus-1440.png`).
  - There's no skip link and no `<main>` landmark.
  - The home page has 7 `<h1>`s (one per banner plus carousel clones); every other page has none.
  - Headings skip levels (h3→h6 in the info lists, h2→h5 "Before & After").
- **Fix:**
  - Restore a visible `:focus-visible` style.
  - Wrap content in `<main>` and add a skip link.
  - One `<h1>` per page (page title, or the first banner only), with headings in order.
- **Effort:** medium

#### M13 · No meta descriptions; titles are English-only and sometimes wrong
- **Category:** SEO
- **Location:** `layouts/master.blade.php:46-50`; every page's `@section('page_title')`
- **What's wrong:**
  - No page has a meta description.
  - Titles are always English ("Just Lite | Home") even in Arabic.
  - Recipe titles always use the English name.
  - Diet-plan pages are titled "Blogs".
  - There are no Twitter card tags and no structured data (`LocalBusiness`/`Dietitian`, `Recipe`).
- **Fix:**
  - Translated titles and descriptions per page (recipes: first 150 characters of the text).
  - Add `twitter:card`.
  - JSON-LD for the business (address, phone, opening hours) and for recipes.
- **Effort:** medium

#### M14 · English pages can't be found in Google
- **Category:** SEO
- **Location:** `routes/web.php:20-24` (language stored in the session); `master.blade.php:52-53` (hreflang)
- **What's wrong:** both languages share the same URLs. Google always gets Arabic (the default), and both `hreflang` tags point to the home page.
- **Fix:** put the language in the URL (`/en/about`, or `?lang=en` as a minimum), with correct `hreflang` pairs on every page.
- **Effort:** large

#### M15 · sitemap.xml is from 2022 and robots.txt doesn't mention it
- **Category:** SEO
- **Location:** `public/sitemap.xml`, `public/robots.txt`
- **What's wrong:**
  - The sitemap has 6 URLs on `http://www.just-lite.com` (not https, not the canonical host), last modified 2022.
  - It has none of the 19 recipe, service and diet pages, but does include `/services`, which isn't in the menu.
- **Fix:**
  - Generate the sitemap from the database (e.g. `spatie/laravel-sitemap`) with https, non-www URLs.
  - Add `Sitemap: https://just-lite.com/sitemap.xml` to robots.txt.
- **Effort:** quick

#### M16 · Admin: nothing is validated, and passwords have no rules
- **Category:** Security / Data quality
- **Location:** Admin → BREAD settings (all 17 BREADs have 0 validation rules); users BREAD `password` field
- **What's wrong:**
  - Admins can save empty blogs, packages or banners, which show as blank cards on the site.
  - User passwords can be one character.
  - There's no password reset flow (a locked-out admin needs database access) and no two-factor login.
- **Fix:**
  - Add `required` and length rules to the title and text fields in each BREAD.
  - Add `min:12` to the user password.
  - Consider a 2FA package for the admin.
- **Effort:** medium

#### M17 · Errors are logged to one growing file, and nobody is alerted
- **Category:** Configuration / Monitoring
- **Location:** `config/logging.php` (`stack` → `single`), `.env.example` (`LOG_LEVEL=debug`)
- **What's wrong:** one `laravel.log` grows forever at debug level. Nothing alerts you to errors, and the exception handler is empty.
- **Fix:**
  - `LOG_CHANNEL=daily` (14 days), `LOG_LEVEL=warning`.
  - Add an error tracker (Sentry or Flare free tier), or an email on critical errors.
- **Effort:** quick

#### M18 · No scheduled backups, and uploaded images are never backed up
- **Category:** Deployment
- **Location:** `app/Console/Kernel.php:16-19` (empty schedule); `scripts/deploy.sh:170`
- **What's wrong:**
  - The database is only backed up during a deploy, and the backups sit on the same server.
  - `storage/app/public` (all uploads) isn't backed up by anything in the project. Several folders are already missing from the copy you gave me (M23).
- **Fix:**
  - Schedule `db:backup` daily (cron `* * * * * php artisan schedule:run`).
  - Copy backups and uploads off the server weekly.
  - Check what Hostinger's own backup covers.
- **Effort:** medium

#### M19 · No empty states
- **Category:** Error handling / UX
- **Location:**
  - `blogs/blogs-content.blade.php`, `services/services-content.blade.php`
  - `home/about.blade.php:24-46`
  - `home/testimonial.blade.php:17`
  - `home/banner.blade.php:2` and `home/results.blade.php:2` (`@if($banners)` is always true)
- **What's wrong:**
  - An empty list shows only the pagination (`empty-blogs-page-375.png`), and `/blogs?page=99` returns 200 with nothing.
  - "Nutrition Special Feature" shows a heading with no items, because the table is empty.
  - Testimonials show empty name headings.
- **Fix:**
  - Use `@forelse` with a "No posts yet" message.
  - Return 404 for pages past the end.
  - Hide sections whose data is empty (`->isNotEmpty()`).
- **Effort:** quick

#### M20 · Small tap targets on phones
- **Category:** Responsiveness / Accessibility
- **Location:** 375 px, every page
- **What's wrong:** these are under the requested 44 px:
  - social icons in the top bar: 28×28 px
  - menu toggle: 35×40
  - language button: 32 px tall
  - breadcrumb links in the page banners: 24–29 px tall
  - desktop banner dots: 12×24

  No target is under WCAG's 24 px minimum on phones.
- **Fix:** give these links a 44 px minimum size, with padding rather than bigger icons.
- **Effort:** quick

#### M21 · The footer credit link now goes to an unrelated domain
- **Category:** Broken link / Security
- **Location:** `layouts/footer/index.blade.php:179` (`http://itvision.me`)
- **What's wrong:** `itvision.me` permanently redirects (301) to `https://kaigyo.me/`. That site sits behind a Cloudflare challenge, so I couldn't see what it is.
- **Why it matters:** if the agency's domain has changed hands, every page links to a stranger's site.
- **Fix:** confirm the agency's current address, or remove the link. Add `rel="noopener"` to `target="_blank"` links.
- **Effort:** quick

#### M22 · Cookie and header hardening
- **Category:** Security
- **Location:** `config/session.php:171`; `app/Http/Middleware/SecurityHeaders.php`
- **What's wrong:**
  - The session cookie isn't "Secure" unless `SESSION_SECURE_COOKIE` is set (see H7).
  - There's no Content-Security-Policy.
  - HSTS has no `includeSubDomains`.
  - None of the headers are live yet (C1).
- **Fix:**
  - Set the env value.
  - Start a CSP in report-only mode listing the CDNs actually used (GTM, Google Fonts, Maps, YouTube).
- **Effort:** medium

#### M23 · Uploaded images are missing (local copy; check the server)
- **Category:** Bugs / Broken images
- **Location:** `storage/app/public/services/February2022/`, `weekly-diet-plans/February2022/`, `testimonials/February2022/`, `users/default.png`
- **What's wrong:**
  - These files are referenced in the database but missing from the copy you gave me. Service 9 and all 5 diet-plan pages show a broken image (`diet-plan-375.png`).
  - The admin's avatar is broken on every admin page.
- **Fix:** check that these folders exist on the server, and include them in backups (M18).
- **Effort:** quick

#### M24 · `public/storage` is a tracked folder; the server may use a symlink
- **Category:** Deployment
- **Location:** `public/storage/.gitignore` (tracked since 2022); `scripts/deploy.sh:152`; `config/filesystems.php:69-71`
- **What's wrong:**
  - Uploads are saved to `storage/app/public` and served from `public/storage`.
  - If the server has the usual `storage:link` symlink in place of the tracked folder, `git status` reports the tracked file as deleted. `deploy.sh` then refuses to deploy ("Tracked files were edited on the server").
  - If it's a real folder instead, uploads wouldn't show at all.
- **Fix:**
  - On the server, run `ls -la public/storage` and `git status` once SSH works.
  - If it's a symlink, `git rm --cached public/storage/.gitignore` and add `/public/storage` to `.gitignore`.
- **Effort:** quick

### 🟢 Low

#### L1 · Dead code and unused files
- **Category:** Code quality
- **Location:**
  - Unused views:
    - `resources/views/welcome.blade.php`
    - `home/blog.blade.php` (lorem ipsum, links to `blog-details.html`)
    - `home/banner-bottom-area.blade.php`
    - `home/diet-plan-area.blade.php`
    - `home/appointment.blade.php`
  - The search modal (`master.blade.php:108-124`), which nothing opens.
  - Newsletter, Ajaxchimp and `go-top` code in `custom.js:274-330`.
  - Two commented-out blocks in the header and footer.
  - `routes/api.php` (`/api/user`) and Sanctum, unused.
  - `tests/*/ExampleTest.php`.
  - Published mail and notification views.
  - The `/services` page, which the menu no longer links to.
  - The `diet-services` translation key.
- **Fix:** delete them, or link `/services` back into the menu if it's wanted.
- **Effort:** quick

#### L2 · Database queries and logic live in Blade views and route closures
- **Category:** Code quality
- **Location:**
  - `home/banner.blade.php:1`, `about.blade.php:1,29`, `info.blade.php:1`, `packages.blade.php:1`, `testimonial.blade.php:1`, `bad-habits.blade.php:13`, `results.blade.php:1`
  - `routes/web.php:16-63` (5 closures)
  - `$page` and `$lang` set in every view
- **Fix:** a `HomeController`/`PageController`, plus a view composer for the shared header data.
- **Effort:** medium

#### L3 · Hard-coded values
- **Category:** Code quality
- **Location:**
  - the email address in `contact-us/index.blade.php:65` and `footer:144`
  - social URLs duplicated in the header and footer
  - GTM ID (`master.blade.php:80,87`)
  - domain in the canonical and OG tags
  - Maps embed URL
  - agency link
  - `$` currency (`packages.blade.php:14`)
- **Fix:** move them into `config/site.php`, next to the phone number.
- **Effort:** quick

#### L4 · Copyright year is stuck at 2022
- **Category:** UX
- **Location:** `resources/lang/{ar,en}/translation.php:41`
- **Fix:** `© {{ date('Y') }}`, with the year as a placeholder in the translation.
- **Effort:** quick

#### L5 · Footer menu opens the site's own pages in new tabs
- **Category:** UX
- **Location:** `layouts/footer/index.blade.php:106-122` (`target="_blank"` on `/`, `/about` …)
- **Fix:** remove `target="_blank"` from internal links.
- **Effort:** quick

#### L6 · Without JavaScript the site is a spinner, and forms post to the wrong URL
- **Category:** Bugs
- **Location:** `.preloader` (hidden only by JS); `contact-us/index.blade.php:75` (no `action`); `footer:14` (`action=""`)
- **What's wrong:** with JS off, the preloader covers every page. The contact form would post to `/contact-us`, which returns 405 (`contact-no-js-375.png`).
- **Fix:**
  - Hide the preloader with CSS by default (or in a `<noscript>` style).
  - Set `action="{{ route('contact.store') }}"` and `action="{{ route('appointment.store') }}"`.
- **Effort:** quick

#### L7 · Admin: one N+1 query, client-side tables, sideways scrolling on phones
- **Category:** Performance / Responsiveness
- **Location:** Admin → Info Details; all BREAD lists; create/edit forms at 375 px
- **What's wrong:**
  - Info Details runs one extra query per row (13 for 13 rows).
  - Every BREAD has "server side pagination" off, so all rows load at once (93 contacts today).
  - At 375 px, 23 admin screens (dashboard, settings and all create/edit forms) are 378–460 px wide.
- **Fix:**
  - Turn on server-side pagination for contacts and appointments.
  - Eager-load the `infos` relationship.
  - Accept the phone layout, or wrap wide editors.
- **Effort:** quick

#### L8 · Missing database indexes
- **Category:** Performance
- **Location:** `info_details.info_id` (no index, no foreign key), `weekly_diet_plans.day`
- **Fix:** add the index and foreign key in a migration. The tables are tiny, so this is housekeeping.
- **Effort:** quick

#### L9 · Favicon set is incomplete
- **Category:** UX
- **Location:** `public/favicon.ico` is a 0-byte file; there's no `apple-touch-icon` or web manifest
- **Fix:** generate an `.ico`, a 180 px Apple touch icon and a `site.webmanifest`.
- **Effort:** quick

#### L10 · Content issues
- **Category:** Content (Admin)
- **What's wrong:**
  - The Arabic testimonial text is in English (with the full stop on the wrong side).
  - A package still says "Welcome the year 2023".
  - Results images use the English title as `alt` in both languages.
  - Some Arabic texts start with a stray "." (e.g. diet plan "توست الأفوكادو").
- **Fix:** edit in the admin.
- **Effort:** quick

#### L11 · Decorative leaves and the WhatsApp button cover text
- **Category:** Responsiveness
- **Location:** `.banner-shape-two`, `.about-shape` images; `.scroll-whatsapp`
- **What's wrong:**
  - At 1024–1440 px, leaf shapes overlap the hero and About paragraphs (`home-1024.png`).
  - On phones and at 1024 px, the floating WhatsApp button covers the start of lines and the "About Us" label (`home-en-1024.png`).
- **Fix:** move the shapes behind the text with `z-index` or hide them below 1200 px; shrink the WhatsApp button or give it a margin.
- **Effort:** quick

#### L12 · Blog and service cards show the whole text
- **Category:** UX
- **Location:** `blogs/blogs-content.blade.php:13`, `services/services-content.blade.php:13`
- **What's wrong:** full recipes appear on the list cards, making cards uneven and the page long.
- **Fix:** `Str::limit(strip_tags($text), 140)`.
- **Effort:** quick

#### L13 · Meaningless alt texts
- **Category:** Accessibility / SEO
- **Location:** 29 images: "Images", "Banner Images", "About Images", "Pricing Shape", "flag", "logo"
- **Fix:** use `alt=""` for decorative shapes and flags (the language names are already written next to them). Use real descriptions for photos (banner title, recipe name).
- **Effort:** quick

#### L14 · Server timezone is UTC
- **Category:** Configuration
- **Location:** `config/app.php:70`
- **What's wrong:** booking and message times in the admin are two to three hours off Lebanon time.
- **Fix:** `'timezone' => 'Asia/Beirut'`
- **Effort:** quick

#### L15 · README is Laravel's boilerplate; `.env.example` is outdated
- **Category:** Documentation
- **Location:** `README.md`, `.env.example`
- **What's wrong:**
  - The README says nothing about this project.
  - `.env.example` has `APP_NAME=Laravel`.
  - It lacks `APP_Languages`, `Fallback_Languages`, `SESSION_SECURE_COOKIE` and `BCRYPT_ROUNDS`.
  - It defaults to `APP_DEBUG=true` and `LOG_LEVEL=debug`.
- **Fix:** a short README (Docker setup, tests, link to `DEPLOYMENT.md`) and a corrected `.env.example`.
- **Effort:** quick

#### L16 · Small package updates; front-end libraries aren't tracked
- **Category:** Configuration
- **What's wrong:**
  - Available: `larastan` 3.13.0 → 3.13.2 and `vite` 8.3.4 → 8.3.5.
  - Majors also exist for Guzzle 8 and PHPUnit 13; no need to move yet.
  - The site's real front-end libraries are copied files in `public/js`, so `npm audit` can't see them: Bootstrap 5.1.3, jQuery 3.6.0, Owl 2.3.4, Magnific Popup 1.1.0, WOW 1.3.0, Odometer 0.4.8. None has a known vulnerability today.
- **Fix:** update the two patches. Move the libraries into npm/Vite (see M2).
- **Effort:** quick

#### L17 · Oversized logo and flag images
- **Category:** Performance
- **Location:** `public/images/logo.png` (1890×1417, 82 KB, shown at 160 px); `uk.png`/`uae.png` (64 px, shown at 20 px)
- **Fix:** export the logo at 320 px wide, or as SVG.
- **Effort:** quick

#### L18 · Results carousel uses a relative image path
- **Category:** Bugs
- **Location:** `home/results.blade.php:23` (`src=" storage/..."`, with a leading space and no leading `/`)
- **What's wrong:** it works only because the section is on `/`; on any other URL the images would break.
- **Fix:** `VoyagerImage::url($result->image)`.
- **Effort:** quick

#### L19 · Accordion and slider controls use the wrong elements
- **Category:** Accessibility
- **Location:** `home/bad-habits.blade.php:17,36` (`<a href="javascript:void(0)">`)
- **What's wrong:** the accordions are links with no `aria-expanded`. Hidden dropdown items stay in the Tab order.
- **Fix:** `<button aria-expanded>` for accordions.
- **Effort:** quick

#### L20 · Test coverage gaps
- **Category:** Code quality
- **What's wrong:** all 31 tests pass, but nothing tests:
  - role permissions (C2)
  - the login lockout
  - error pages
  - the booking date (H2, M6)
  - the About placeholder (H1)
  - the BREAD image-field uploads

  The two `ExampleTest`s are boilerplate.
- **Fix:** add feature tests for these.
- **Effort:** medium

#### L21 · AJAX pagination doesn't update the address or scroll position
- **Category:** UX
- **Location:** `blogs/index.blade.php:55-71`, `services/index.blade.php:56-72`
- **What's wrong:** after "page 2", the URL still says page 1. Back and refresh lose the place, and the view stays at the bottom.
- **Fix:** plain links (the server already paginates), or `history.pushState` and scroll to the list.
- **Effort:** quick

#### L22 · Package features are printed as raw HTML
- **Category:** Security
- **Location:** `home/packages.blade.php:18` (`{!! $package->features_en !!}`)
- **What's wrong:** HTML typed in the admin runs on the home page. That's acceptable only while every admin account is trusted, and with C2 more people can edit packages. axe also flags the list markup (text directly inside `<ul>`).
- **Fix:** store the features as plain lines and render `<li>{{ }}</li>`, or sanitise with an allow-list (HTMLPurifier).
- **Effort:** quick

---

## 3. Full checklist results

### Step 1 · Site map

| Part | What's there |
| --- | --- |
| Routes | 285 in total: 20 public, 265 Voyager admin (17 BREADs × 13 routes, plus media, menus, settings, database, BREAD builder, Compass, login and profile). Ignition's `_ignition/*` routes exist only locally; they're dev-only and not installed in production |
| Controllers | `AppointmentController`, `BlogsController`, `ContactController`, `DietPlanController`, `ServicesController` (plus Voyager's) |
| Models | `App\Models\User` (extends Voyager's), and in `app/`: About, Appointment, BadHabit, Banner, Blog, Contact, Info, InfoDetail, NutritionSpecialFeature, Package, Result, Service, Testimonial, WeeklyDietPlan |
| Form requests | `StoreAppointmentRequest`, `StoreContactRequest` |
| Middleware | Authenticate, EncryptCookies, Localization, PreventRequestForgery, PreventRequestsDuringMaintenance, RedirectIfAuthenticated, SecurityHeaders, TrimStrings, TrustHosts (off), TrustProxies |
| Jobs / mail / notifications | None |
| Console | `db:backup` (`app/Console/Commands/BackupDatabase.php`); no scheduled tasks |
| Views | 26 site views (5 unused, L1); 8 error views (Laravel defaults); published mail and pagination views |
| Config | app, auth, broadcasting, cache, cors, database, filesystems, flare, hashing, ignition, logging, mail, queue, sanctum, services, session, site, tinker, view, voyager |
| How it ran | Docker (Apache + PHP 8.4.27, MariaDB 11.8) at `localhost:8080`. `npm run dev` isn't needed: no page uses `@vite`, and `npm run build` works |

**Public pages (unthrottled local load, 1440 px, Arabic / English):**

| Page | Status | Load (ms) | Weight | Requests | Console errors / failed requests |
| --- | --- | --- | --- | --- | --- |
| `/` | 200 | 1728 / 1908 | 5.2 MB | 57 | none (only the blocked GTM) |
| `/about` | 200 | 1381 / 1212 | 550 KB | 41 | none |
| `/testimonials` | 200 | 1432 / 1408 | 790 KB | 39 | none |
| `/services` | 200 | 1848 / 1481 | 594 KB | 39 | 404 `storage/services/February2022/3WgbQw5BIh3AX1trkQzG.jpg` |
| `/blogs` | 200 | 1563 / 1623 | 2.4 MB | 43 | none |
| `/contact-us` | 200 | 1592 / 1882 | 572 KB | 37 | none |
| `/blog-details/{2,4,5,7,8,9,11}` | 200 | 1242–1449 | 1.2 MB (id 2) | 37 | none |
| `/service-details/{9…15}` | 200 | 1857 / 1941 (id 9) | 500 KB | 37 | id 9: 404 image (M23) |
| `/diet-plan/{2…6}` | 200 | 1525 / 1901 (id 2) | 490 KB | 37 | every id: 404 image (M23) |
| `/blogs?page=2`, `/services?page=2` | 200 | – | – | – | none |
| `/blog-details/3` (deleted), `/diet-plan/999`, `/service-details/abc`, `/this-page-does-not-exist`, `/locale/xx`, `/blogs/fetch_data` (non-AJAX) | 404 | – | – | – | correct status, default page (H3) |

**Admin:**
- 198 page views: 25 lists and tools at 4 widths, plus 49 create/edit/show forms at 2 widths. Logged in as a throwaway admin.
- 194 returned 200. `/admin/compass` returned 404, as intended (disabled).
- Median load is 2.4 s locally (max 5.4 s).
- Failed requests are only the missing uploads (M23).
- `storage/logs/laravel.log` gained no new entries during the whole crawl.

### A. Error handling & edge cases

| Item | Result | Note |
| --- | --- | --- |
| Custom 404/403/419/429/500/503 pages matching the design | ❌ | All are Laravel's published defaults: English, unstyled, no way back (H3) |
| Fake URL / deleted record / no permission shows a friendly page | ❌ | Statuses are right (404 for the fake URL, deleted blog 3, `/diet-plan/999`; 403 for a "user" account on Settings; a non-admin without `browse_admin` is sent to `/`), but the pages aren't friendly (H3) |
| Empty states | ❌ | Empty lists show only pagination; empty "Nutrition Special Feature" heading; empty testimonial names (M19) |
| Flash / success / error messages after every form action | ❌ | Messages exist for every form (contact: inline; booking: `alert()`), but are partly English on the Arabic site and not next to the field (M5) |
| Maintenance mode page | ❌ | `artisan down` shows the default English "503 SERVICE UNAVAILABLE", with no design and no `Retry-After` (`error-503-maintenance-375.png`) (H3) |

### B. Bugs & code quality

| Item | Result | Note |
| --- | --- | --- |
| Every route resolves to a working controller and view | ✅ | All 20 public routes and all 74 admin screens respond as expected (Compass 404 is intended); `/services` works but isn't in the menu (L1) |
| Broken links and missing images | ❌ | 27 internal links OK. External: Facebook 400 and LinkedIn 999 are bot blocks (fine in a browser); `itvision.me` redirects to an unrelated domain (M21). Missing upload images (M23) |
| Errors and warnings in `laravel.log` | ✅ | No new entries during about 400 page views and the form tests. Older entries today came from code fixed since, and from my own config typo during the earlier fixes. The production log wasn't available |
| Null checks, relationships, validation | ❌ | Past booking dates accepted (M6); empty `about.video` gives `href=""`; `@if($banners)` is always true (M19); relationships are fine (`Info::details` eager-loaded) |
| N+1 queries | ❌ | Public pages: 3–12 queries, no repeats ✅. Admin Info Details: 13 repeated queries (L7) |
| Larastan level 5 | ✅ | 0 errors on `app/` and `routes/`. Blade views (where much of the logic is) aren't analysed |
| Dead code, unused views or routes, duplicated logic | ❌ | 5 unused views, search modal, newsletter JS, API route, duplicated header/footer social links, two copies of the booking form (L1, L3) |
| Logic in Blade views or routes | ❌ | 8 queries run inside 7 Blade partials; 5 route closures (L2) |
| Hard-coded values | ❌ | Email, social links, GTM ID, domain, map, year, currency (L3, L4) |
| Existing tests; missing coverage | ✅ | 31 tests, 147 assertions, all pass. Gaps: permissions, login lockout, error pages, booking date (L20) |

### C. Security

| Item | Result | Note |
| --- | --- | --- |
| `composer audit` and `npm audit` | ✅ | No advisories, nothing abandoned. The copied front-end libraries aren't covered by npm (L16) |
| APP_DEBUG / APP_ENV for production | ❌ | Not verifiable from here; set `APP_ENV=production`, `APP_DEBUG=false` (H7) |
| Mass assignment | ✅ | Content models have no `$fillable`, so everything is guarded; controllers assign fields one by one; `User` allows only name/email/password; Voyager assigns field by field |
| SQL injection | ✅ | No raw SQL in `app/`, `routes/` or views; all queries use the query builder with bindings |
| XSS | ❌ | All user input is escaped (recipe and service texts use `nl2br(e())`). Package features are printed as raw admin HTML (L22) |
| CSRF on every form | ✅ | `@csrf` on both POST forms. Laravel 13 also checks the browser's `Sec-Fetch-Site`, so a token-less same-site post is accepted and a cross-site one rejected. The search form is GET and unused |
| Authorization on sensitive routes, including direct ID access | ❌ | Guests are redirected to login everywhere, but the "user" role can read every contact and booking by ID and edit every user (C2) |
| Rate limiting on login, register, reset, contact, API | ✅ | Login: 5 tries, then about 60 s lockout (tested). Forms: 10/min/IP (tested, the 11th gets 429). API: 60/min. No register or reset routes exist |
| File uploads | ✅ | In code: script names blocked (`UploadGuard`), images only in the media manager, image fields re-encoded, random file names, `storage/app/public/.htaccess` blocks scripts. **Not live yet** (C1) |
| Secrets in git; `.env` ignored | ✅ | `.env`, `.env.testing` and the database dumps are ignored; no secrets in history; the public CI fixture has no personal data |
| Security headers | ❌ | nosniff, `X-Frame-Options`, `Referrer-Policy`, `Permissions-Policy` and HSTS (HTTPS only) are set; no CSP; not live yet (M22, C1) |
| Session / cookie settings | ❌ | `httponly` ✅, `samesite=lax` ✅, `secure` only if `SESSION_SECURE_COOKIE` is set (H7) |
| Password rules, email verification, password reset | ❌ | No password rules for admin-created users, no reset flow, no 2FA (M16). Email verification ➖ (no public sign-up) |

### D. Performance

| Item | Result | Note |
| --- | --- | --- |
| Load time and weight of every page | ❌ | Home 5.2 MB and 17.8 s fully loaded on slow 4G; blogs 2.4 MB / 11 s; others 0.5–1.2 MB (M1; table in Step 1) |
| Images | ❌ | 1 MB PNG banners, 1920 px photos shown at 500 px, no WebP/AVIF, no `width`/`height`, lazy loading only on list thumbnails (M1, L17) |
| CSS/JS | ❌ | 13 blocking stylesheets, unminified 166 KB `style.css` with a font `@import`, 254 KB jQuery UI everywhere, theme not built with Vite (M2) |
| Caching | ✅ | Deploy caches config and views; routes are deliberately not cached (Voyager adds routes from the database); Apache gzips text. No query caching needed at 3–12 queries per page |
| Missing database indexes | ❌ | `info_details.info_id`, `weekly_diet_plans.day` (L8). Every other foreign key is indexed |
| Heavy tasks in queues | ➖ | The app sends no email and has no exports or other heavy tasks; `QUEUE_CONNECTION=sync` is fine |
| Pagination on long lists | ✅ | Blogs and services: 6 per page. Admin lists load all rows client-side (L7) |

### E. Responsiveness (72 page screenshots, plus menus, modals and forms)

| Item | Result | Note |
| --- | --- | --- |
| Horizontal scrolling | ✅ | None on any of the 72 public page views. Admin create/edit forms scroll sideways at 375 px (L7) |
| Text, images or tables overflowing | ✅ | No element wider than the screen; decorative shapes are clipped |
| Navigation or menu broken on mobile | ✅ | Hamburger menu at 375 and 768 px in both languages; 5 links, each 55 px tall |
| Overlapping or cut-off elements | ❌ | English menu wraps at 1024 px (H5); "1010 x 690" placeholder (H1); leaves and the WhatsApp button over text (L11); booking calendar behind the modal (H2) |
| Tap targets under 44 px | ❌ | Social icons 28 px, menu toggle 35×40, language button 32 px, breadcrumbs 24–29 px (M20) |
| Font sizes too small on mobile | ✅ | No text under 12 px; body text 15 px minimum |
| Forms hard to use on mobile | ❌ | Phone isn't `type="tel"`, no `autocomplete`, 15 px inputs make iPhones zoom, date picker broken (M11, H2) |
| Modals and dropdowns fit the screen | ❌ | Booking modal (0–375 px) and language menu fit ✅; the jQuery UI calendar opens behind the modal (H2) |
| Viewport meta tag | ✅ | `width=device-width, initial-scale=1.0` on every page (and on the error pages) |

### F. UX & design consistency

| Item | Result | Note |
| --- | --- | --- |
| Consistent fonts, colours, buttons, spacing | ✅ | One theme throughout; exceptions are the error pages (H3) and the About page, which has no page banner unlike the other inner pages |
| Loading states, no double submit | ✅ | Submit buttons are disabled during the request (double-click sent 1 request). No spinner |
| Validation messages next to the right fields; old input kept | ❌ | Client-side messages sit under each field but are English; server errors show as one line under the button; old input is kept ✅ (M5) |
| Confirmation before deleting | ✅ | Voyager asks in a modal before deleting (`admin-contacts-delete-confirm-1440.png`) |
| Favicon and app icons | ❌ | `favicon.png` ✅; `favicon.ico` empty; no Apple touch icon or manifest (L9) |
| Breadcrumbs / back navigation | ❌ | List pages have breadcrumbs in their banner; recipe, service and diet pages have no breadcrumb or back link |
| Footer: current year, contact, legal links | ❌ | Contact details ✅ (one phone number everywhere); year is 2022 (L4); no legal links (H6) |

### G. SEO

| Item | Result | Note |
| --- | --- | --- |
| Unique `<title>` and meta description | ❌ | Titles exist but are English-only, repeated, and wrong on diet plans; no descriptions anywhere (M13) |
| One `<h1>`, correct heading order | ❌ | Home has 7, other pages 0; skips h3→h6 and h2→h5 (M12) |
| Open Graph and Twitter cards | ❌ | Same "Home" OG tags on every page; no Twitter tags (H4, M13) |
| Canonical URLs | ❌ | Every page points to `https://just-lite.com/` (H4) |
| `sitemap.xml` and `robots.txt` | ❌ | Sitemap from 2022 with 6 http-www URLs; robots doesn't reference it (M15) |
| Alt text on images | ❌ | Every image has `alt`, but the text is generic ("Images", "Banner Images") (L13) |
| Clean, readable URLs | ✅ | Short paths. Records use IDs rather than slugs (`/blog-details/2`), and both languages share one URL (M14) |
| Structured data | ❌ | None; `LocalBusiness` and `Recipe` would fit (M13) |
| HTTPS and www / non-www redirects | ❌ | Not testable without contacting the live site. In code there are no redirect rules in `.htaccess`, and the host is inconsistent: canonical `https://just-lite.com`, OG image `https://www.`, sitemap `http://www.` (M15). Check the redirect in hPanel |

### H. Accessibility

| Item | Result | Note |
| --- | --- | --- |
| Colour contrast (WCAG AA) | ❌ | White on brand green is 2.67:1; 281 failing elements (M9) |
| Labels on all form fields | ❌ | Placeholders only, on the site; 103 unlabelled fields in the admin (M11) |
| Keyboard navigation and visible focus | ❌ | Everything is reachable by Tab, but buttons show no focus ring; no skip link (M12) |
| `lang` attribute on `<html>` | ✅ | `lang="ar" dir="rtl"` / `lang="en"`; error pages are always `lang="en"` (H3) |
| ARIA labels on icon-only buttons | ❌ | Social, WhatsApp, menu toggle, modal close, slider dots, play button (M10) |
| axe-core on every page | ❌ | **Public, 78 page views:** `link-name` (572 elements), `region` (311), `color-contrast` (281), `empty-heading` (86), `landmark-one-main` (74), `page-has-heading-one` (70), `button-name` (52), `heading-order` (12), `landmark-unique` (12), `list` (10), `frame-title` (4). **Admin, 24 screens:** `link-name` (512), `color-contrast` (215), `label` (103), `image-alt` (62), `button-name` (25), `link-in-text-block` (25), `select-name` (1), `aria-input-field-name` (1) |

### I. Forms & emails

| Item | Result | Note |
| --- | --- | --- |
| Every form: validation, error display, success message, spam protection | ❌ | Validation ✅ (server and client), success message ✅, errors partly English (M5), past dates accepted (M6), date picker broken (H2), no honeypot or captcha (M7), no notification to you (M8) |
| Email templates (branded, sender, links, mobile) | ➖ | The site sends no email at all; the published mail templates are unused |
| Email sent through a queue | ➖ | No email is sent. If M8 adds one, send it with `ShouldQueue` |

### J. Legal & compliance

| Item | Result | Note |
| --- | --- | --- |
| Privacy policy, terms, imprint | ❌ | None exist (H6) |
| Cookie consent when analytics are used | ❌ | GTM, Google Fonts and Maps load without consent (H6) |
| Data export / deletion for accounts | ➖ | No public accounts. Contact messages and bookings are kept forever, with no deletion routine (H6) |

### K. Configuration & deployment

| Item | Result | Note |
| --- | --- | --- |
| Laravel and PHP versions | ✅ | Laravel 13.39.0 (current major) on PHP 8.4.27 (supported to the end of 2028). The live server's PHP 8.4 handler is still unconfirmed (C1) |
| Outdated or abandoned packages | ✅ | Nothing abandoned; two patch updates and two optional majors (L16) |
| Logging setup, error monitoring | ❌ | Single file, debug level, no alerts (M17) |
| Queue and scheduler | ➖ | No jobs and no scheduled tasks, so no worker or cron is needed today; backups should become one (M18) |
| Backups | ❌ | Database only at deploy, kept on the same server; uploads never backed up (M18) |
| README with setup and deploy steps | ❌ | `DEPLOYMENT.md` is good; `README.md` is Laravel's boilerplate (L15) |
| `.env.example` complete | ❌ | Missing app-specific keys, insecure defaults (L15) |

---

## 4. Screenshots index

All files are in `screenshots/`. Arabic (the default language) is `{page}-{width}.png`; English is `{page}-en-{width}.png`. Full-page captures, device scale 1.

### Public pages

| Page | URL | Arabic | Notes (Arabic) | English | Notes (English) |
| --- | --- | --- | --- | --- | --- |
| Home | `/` | [375](screenshots/home-375.png) · [768](screenshots/home-768.png) · [1024](screenshots/home-1024.png) · [1440](screenshots/home-1440.png) | ≥992 px: "1010 x 690" placeholder in About (H1); leaves over the hero text at 1024 (L11); language button says "English" (M4) | [375](screenshots/home-en-375.png) · [768](screenshots/home-en-768.png) · [1024](screenshots/home-en-1024.png) · [1440](screenshots/home-en-1440.png) | 1024: menu wraps onto two lines (H5); hero right-aligned and sliders run right-to-left (M3); placeholder (H1) |
| About | `/about` | [375](screenshots/about-375.png) · [768](screenshots/about-768.png) · [1024](screenshots/about-1024.png) · [1440](screenshots/about-1440.png) | ≥992 px: placeholder (H1); empty "Nutrition Special Feature" heading (M19); no page banner, unlike the other inner pages | [375](screenshots/about-en-375.png) · [768](screenshots/about-en-768.png) · [1024](screenshots/about-en-1024.png) · [1440](screenshots/about-en-1440.png) | 1024: menu wraps (H5); placeholder (H1) |
| Testimonials | `/testimonials` | [375](screenshots/testimonials-375.png) · [768](screenshots/testimonials-768.png) · [1024](screenshots/testimonials-1024.png) · [1440](screenshots/testimonials-1440.png) | Empty name headings (M19); the Arabic testimonial text is English (L10) | [375](screenshots/testimonials-en-375.png) · [768](screenshots/testimonials-en-768.png) · [1024](screenshots/testimonials-en-1024.png) · [1440](screenshots/testimonials-en-1440.png) | 1024: menu wraps (H5); slider runs right-to-left (M3) |
| Services | `/services` | [375](screenshots/services-375.png) · [768](screenshots/services-768.png) · [1024](screenshots/services-1024.png) · [1440](screenshots/services-1440.png) | Service 9 image missing (M23); full text on cards (L12) | [375](screenshots/services-en-375.png) · [768](screenshots/services-en-768.png) · [1024](screenshots/services-en-1024.png) · [1440](screenshots/services-en-1440.png) | 1024: menu wraps (H5) |
| Blogs | `/blogs` | [375](screenshots/blogs-375.png) · [768](screenshots/blogs-768.png) · [1024](screenshots/blogs-1024.png) · [1440](screenshots/blogs-1440.png) | 2.4 MB of PNG thumbnails (M1); full recipe text on cards, uneven heights (L12) | [375](screenshots/blogs-en-375.png) · [768](screenshots/blogs-en-768.png) · [1024](screenshots/blogs-en-1024.png) · [1440](screenshots/blogs-en-1440.png) | 1024: menu wraps (H5) |
| Contact | `/contact-us` | [375](screenshots/contact-us-375.png) · [768](screenshots/contact-us-768.png) · [1024](screenshots/contact-us-1024.png) · [1440](screenshots/contact-us-1440.png) | Fields have placeholders only (M11); the map shows blank because it loads lazily (capture artifact) | [375](screenshots/contact-us-en-375.png) · [768](screenshots/contact-us-en-768.png) · [1024](screenshots/contact-us-en-1024.png) · [1440](screenshots/contact-us-en-1440.png) | 1024: menu wraps (H5) |
| Blog detail | `/blog-details/2` | [375](screenshots/blog-details-375.png) · [768](screenshots/blog-details-768.png) · [1024](screenshots/blog-details-1024.png) · [1440](screenshots/blog-details-1440.png) | No breadcrumb or back link (F); 700 KB PNG upscaled to 1296 px (M1) | [375](screenshots/blog-details-en-375.png) · [768](screenshots/blog-details-en-768.png) · [1024](screenshots/blog-details-en-1024.png) · [1440](screenshots/blog-details-en-1440.png) | Title in English in both languages (M13) |
| Service detail | `/service-details/9` | [375](screenshots/service-details-375.png) · [768](screenshots/service-details-768.png) · [1024](screenshots/service-details-1024.png) · [1440](screenshots/service-details-1440.png) | Image missing in the local copy (M23) | [375](screenshots/service-details-en-375.png) · [768](screenshots/service-details-en-768.png) · [1024](screenshots/service-details-en-1024.png) · [1440](screenshots/service-details-en-1440.png) | Same |
| Diet plan | `/diet-plan/2` | [375](screenshots/diet-plan-375.png) · [768](screenshots/diet-plan-768.png) · [1024](screenshots/diet-plan-1024.png) · [1440](screenshots/diet-plan-1440.png) | Image missing in the local copy (M23); page title says "Blogs" (M13); stray "." at the start of the text (L10) | [375](screenshots/diet-plan-en-375.png) · [768](screenshots/diet-plan-en-768.png) · [1024](screenshots/diet-plan-en-1024.png) · [1440](screenshots/diet-plan-en-1440.png) | Same |

### Menus, modals, forms and keyboard

| Screenshot | What it shows | Notes |
| --- | --- | --- |
| [home-375-menu-open.png](screenshots/home-375-menu-open.png) | Mobile menu open (Arabic, 375) | OK: 5 links, 55 px tall |
| [home-768-menu-open.png](screenshots/home-768-menu-open.png) | Mobile menu open (Arabic, 768) | OK |
| [home-en-375-menu-open.png](screenshots/home-en-375-menu-open.png) | Mobile menu open (English, 375) | OK |
| [home-en-768-menu-open.png](screenshots/home-en-768-menu-open.png) | Mobile menu open (English, 768) | OK |
| [home-375-booking-modal.png](screenshots/home-375-booking-modal.png) | Booking modal (Arabic, 375) | Fits the screen |
| [home-768-booking-modal.png](screenshots/home-768-booking-modal.png) | Booking modal (Arabic, 768) | Fits the screen |
| [home-en-375-booking-modal.png](screenshots/home-en-375-booking-modal.png) | Booking modal (English, 375) | Fits the screen |
| [home-en-768-booking-modal.png](screenshots/home-en-768-booking-modal.png) | Booking modal (English, 768) | Fits the screen |
| [form-booking-datepicker-375.png](screenshots/form-booking-datepicker-375.png) | Booking date field tapped (Arabic) | jQuery UI calendar opens behind the modal (H2) |
| [form-booking-datepicker-en-375.png](screenshots/form-booking-datepicker-en-375.png) | Booking date field tapped (English) | Same (H2) |
| [form-booking-after-submit-375.png](screenshots/form-booking-after-submit-375.png) | After a successful booking (Arabic) | Modal stays open; the message was an English `alert()` (M5) |
| [form-booking-after-submit-en-375.png](screenshots/form-booking-after-submit-en-375.png) | After a successful booking (English) | Same (M5) |
| [form-contact-empty-375.png](screenshots/form-contact-empty-375.png) | Contact form sent empty (Arabic) | Field messages in English on the Arabic page (M5) |
| [form-contact-empty-en-375.png](screenshots/form-contact-empty-en-375.png) | Contact form sent empty (English) | OK |
| [form-contact-server-error-375.png](screenshots/form-contact-server-error-375.png) | Server-side validation error (Arabic) | Translated, but one line under the button (M5) |
| [form-contact-server-error-en-375.png](screenshots/form-contact-server-error-en-375.png) | Server-side validation error (English) | Same |
| [form-contact-success-375.png](screenshots/form-contact-success-375.png) | Contact form success (Arabic) | Success message in English (M5) |
| [form-contact-success-en-375.png](screenshots/form-contact-success-en-375.png) | Contact form success (English) | OK |
| [contact-no-js-375.png](screenshots/contact-no-js-375.png) | Contact page with JavaScript off | Preloader covers the page (L6) |
| [keyboard-focus-1440.png](screenshots/keyboard-focus-1440.png) | Keyboard focus after 7 Tab presses (1440) | No visible focus ring on buttons (M12) |
| [keyboard-focus-375.png](screenshots/keyboard-focus-375.png) | Keyboard focus after 7 Tab presses (375) | Same (M12) |

### Error pages and edge cases

| Screenshot | What it shows | Notes |
| --- | --- | --- |
| [error-404-375.png](screenshots/error-404-375.png) | Fake URL, Arabic, 375 | Default English page, no way back (H3) |
| [error-404-en-1440.png](screenshots/error-404-en-1440.png) | Fake URL, English, 1440 | Same (H3) |
| [error-404-deleted-blog-375.png](screenshots/error-404-deleted-blog-375.png) | `/blog-details/3` (deleted record) | 404 ✅, default page (H3) |
| [error-404-deleted-blog-en-1440.png](screenshots/error-404-deleted-blog-en-1440.png) | Same, English | Same |
| [error-404-text-id-375.png](screenshots/error-404-text-id-375.png) | `/service-details/abc` | 404 ✅, default page |
| [error-404-text-id-en-1440.png](screenshots/error-404-text-id-en-1440.png) | Same, English | Same |
| [error-404-diet-375.png](screenshots/error-404-diet-375.png) | `/diet-plan/999` | 404 ✅, default page |
| [error-404-diet-en-1440.png](screenshots/error-404-diet-en-1440.png) | Same, English | Same |
| [error-404-fetch-data-375.png](screenshots/error-404-fetch-data-375.png) | `/blogs/fetch_data` without AJAX | 404 ✅ |
| [error-404-fetch-data-en-1440.png](screenshots/error-404-fetch-data-en-1440.png) | Same, English | Same |
| [error-locale-375.png](screenshots/error-locale-375.png) | `/locale/xx` | 404 ✅ |
| [error-locale-en-1440.png](screenshots/error-locale-en-1440.png) | Same, English | Same |
| [error-405-375.png](screenshots/error-405-375.png) | GET on a POST-only URL (local debug on) | Full debug page: what visitors would see if production had `APP_DEBUG=true` (H7) |
| [error-405-en-1440.png](screenshots/error-405-en-1440.png) | Same, 1440 | Same (H7) |
| [error-403-1440.png](screenshots/error-403-1440.png) | "user" role opening Settings | Voyager 403 inside the admin |
| [error-419-375.png](screenshots/error-419-375.png) | Contact form posted without its token | Shows the contact page: Laravel 13 accepted the same-site post (section 5) |
| [error-429-375.png](screenshots/error-429-375.png) | 12th form post within a minute | Default English page (H3) |
| [error-500-375.png](screenshots/error-500-375.png) | The 500 view, rendered on its own | Default English page (H3) |
| [error-500-1440.png](screenshots/error-500-1440.png) | Same, 1440 | Same |
| [error-503-maintenance-375.png](screenshots/error-503-maintenance-375.png) | Maintenance mode (`artisan down`) | Default English page, no `Retry-After` (A, H3) |
| [error-503-maintenance-1440.png](screenshots/error-503-maintenance-1440.png) | Same, 1440 | Same |
| [empty-blogs-page-375.png](screenshots/empty-blogs-page-375.png) | `/blogs?page=99` | Empty list, pagination only, status 200 (M19) |
| [empty-blogs-page-en-1440.png](screenshots/empty-blogs-page-en-1440.png) | Same, English | Same |
| [empty-services-page-375.png](screenshots/empty-services-page-375.png) | `/services?page=99` | Same (M19) |
| [empty-services-page-en-1440.png](screenshots/empty-services-page-en-1440.png) | Same, English | Same |
| [admin-login-1440.png](screenshots/admin-login-1440.png) | Admin login | Captured while the form fades in |
| [admin-login-locked-1440.png](screenshots/admin-login-locked-1440.png) | Admin login after 7 wrong passwords | Lockout message confirmed in the page text (captured mid-animation) |
| [admin-contacts-delete-confirm-1440.png](screenshots/admin-contacts-delete-confirm-1440.png) | Delete button on Contacts | Confirmation modal ✅ |

### Admin (Voyager): 74 screens

Lists, dashboard and tools at all four widths; create, edit and show forms at 375 and 1440. Every admin page also loads the missing default avatar (M23).

| Screen | Screenshots | Notes |
| --- | --- | --- |
| abouts | [375](screenshots/admin-abouts-375.png) · [768](screenshots/admin-abouts-768.png) · [1024](screenshots/admin-abouts-1024.png) · [1440](screenshots/admin-abouts-1440.png) | – |
| abouts-create | [375](screenshots/admin-abouts-create-375.png) · – · – · [1440](screenshots/admin-abouts-create-1440.png) | 375: wider than the screen (L7) |
| abouts-edit | [375](screenshots/admin-abouts-edit-375.png) · – · – · [1440](screenshots/admin-abouts-edit-1440.png) | 375: wider than the screen (L7) |
| abouts-show | [375](screenshots/admin-abouts-show-375.png) · – · – · [1440](screenshots/admin-abouts-show-1440.png) | – |
| appointments | [375](screenshots/admin-appointments-375.png) · [768](screenshots/admin-appointments-768.png) · [1024](screenshots/admin-appointments-1024.png) · [1440](screenshots/admin-appointments-1440.png) | – |
| appointments-create | [375](screenshots/admin-appointments-create-375.png) · – · – · [1440](screenshots/admin-appointments-create-1440.png) | – |
| appointments-edit | [375](screenshots/admin-appointments-edit-375.png) · – · – · [1440](screenshots/admin-appointments-edit-1440.png) | – |
| appointments-show | [375](screenshots/admin-appointments-show-375.png) · – · – · [1440](screenshots/admin-appointments-show-1440.png) | – |
| bad-habits | [375](screenshots/admin-bad-habits-375.png) · [768](screenshots/admin-bad-habits-768.png) · [1024](screenshots/admin-bad-habits-1024.png) · [1440](screenshots/admin-bad-habits-1440.png) | – |
| bad-habits-create | [375](screenshots/admin-bad-habits-create-375.png) · – · – · [1440](screenshots/admin-bad-habits-create-1440.png) | – |
| bad-habits-edit | [375](screenshots/admin-bad-habits-edit-375.png) · – · – · [1440](screenshots/admin-bad-habits-edit-1440.png) | – |
| bad-habits-show | [375](screenshots/admin-bad-habits-show-375.png) · – · – · [1440](screenshots/admin-bad-habits-show-1440.png) | – |
| banners | [375](screenshots/admin-banners-375.png) · [768](screenshots/admin-banners-768.png) · [1024](screenshots/admin-banners-1024.png) · [1440](screenshots/admin-banners-1440.png) | – |
| banners-create | [375](screenshots/admin-banners-create-375.png) · – · – · [1440](screenshots/admin-banners-create-1440.png) | 375: wider than the screen (L7) |
| banners-edit | [375](screenshots/admin-banners-edit-375.png) · – · – · [1440](screenshots/admin-banners-edit-1440.png) | 375: wider than the screen (L7) |
| banners-show | [375](screenshots/admin-banners-show-375.png) · – · – · [1440](screenshots/admin-banners-show-1440.png) | – |
| blogs | [375](screenshots/admin-blogs-375.png) · [768](screenshots/admin-blogs-768.png) · [1024](screenshots/admin-blogs-1024.png) · [1440](screenshots/admin-blogs-1440.png) | – |
| blogs-create | [375](screenshots/admin-blogs-create-375.png) · – · – · [1440](screenshots/admin-blogs-create-1440.png) | 375: wider than the screen (L7) |
| blogs-edit | [375](screenshots/admin-blogs-edit-375.png) · – · – · [1440](screenshots/admin-blogs-edit-1440.png) | 375: wider than the screen (L7) |
| blogs-show | [375](screenshots/admin-blogs-show-375.png) · – · – · [1440](screenshots/admin-blogs-show-1440.png) | – |
| bread | [375](screenshots/admin-bread-375.png) · [768](screenshots/admin-bread-768.png) · [1024](screenshots/admin-bread-1024.png) · [1440](screenshots/admin-bread-1440.png) | 375: wider than the screen (L7) |
| compass | [375](screenshots/admin-compass-375.png) · [768](screenshots/admin-compass-768.png) · [1024](screenshots/admin-compass-1024.png) · [1440](screenshots/admin-compass-1440.png) | 404 on purpose (Compass disabled) |
| contacts | [375](screenshots/admin-contacts-375.png) · [768](screenshots/admin-contacts-768.png) · [1024](screenshots/admin-contacts-1024.png) · [1440](screenshots/admin-contacts-1440.png) | – |
| contacts-create | [375](screenshots/admin-contacts-create-375.png) · – · – · [1440](screenshots/admin-contacts-create-1440.png) | – |
| contacts-edit | [375](screenshots/admin-contacts-edit-375.png) · – · – · [1440](screenshots/admin-contacts-edit-1440.png) | – |
| contacts-show | [375](screenshots/admin-contacts-show-375.png) · – · – · [1440](screenshots/admin-contacts-show-1440.png) | – |
| dashboard | [375](screenshots/admin-dashboard-375.png) · [768](screenshots/admin-dashboard-768.png) · [1024](screenshots/admin-dashboard-1024.png) · [1440](screenshots/admin-dashboard-1440.png) | 375: wider than the screen (L7) |
| database | [375](screenshots/admin-database-375.png) · [768](screenshots/admin-database-768.png) · [1024](screenshots/admin-database-1024.png) · [1440](screenshots/admin-database-1440.png) | 375: wider than the screen (L7) |
| info-details | [375](screenshots/admin-info-details-375.png) · [768](screenshots/admin-info-details-768.png) · [1024](screenshots/admin-info-details-1024.png) · [1440](screenshots/admin-info-details-1440.png) | N+1: one query per row (L7) |
| info-details-create | [375](screenshots/admin-info-details-create-375.png) · – · – · [1440](screenshots/admin-info-details-create-1440.png) | – |
| info-details-edit | [375](screenshots/admin-info-details-edit-375.png) · – · – · [1440](screenshots/admin-info-details-edit-1440.png) | – |
| info-details-show | [375](screenshots/admin-info-details-show-375.png) · – · – · [1440](screenshots/admin-info-details-show-1440.png) | – |
| infos | [375](screenshots/admin-infos-375.png) · [768](screenshots/admin-infos-768.png) · [1024](screenshots/admin-infos-1024.png) · [1440](screenshots/admin-infos-1440.png) | – |
| infos-create | [375](screenshots/admin-infos-create-375.png) · – · – · [1440](screenshots/admin-infos-create-1440.png) | 375: wider than the screen (L7) |
| infos-edit | [375](screenshots/admin-infos-edit-375.png) · – · – · [1440](screenshots/admin-infos-edit-1440.png) | 375: wider than the screen (L7) |
| infos-show | [375](screenshots/admin-infos-show-375.png) · – · – · [1440](screenshots/admin-infos-show-1440.png) | – |
| media | [375](screenshots/admin-media-375.png) · [768](screenshots/admin-media-768.png) · [1024](screenshots/admin-media-1024.png) · [1440](screenshots/admin-media-1440.png) | – |
| menu-builder | [375](screenshots/admin-menu-builder-375.png) · [768](screenshots/admin-menu-builder-768.png) · [1024](screenshots/admin-menu-builder-1024.png) · [1440](screenshots/admin-menu-builder-1440.png) | – |
| menus | [375](screenshots/admin-menus-375.png) · [768](screenshots/admin-menus-768.png) · [1024](screenshots/admin-menus-1024.png) · [1440](screenshots/admin-menus-1440.png) | – |
| menus-create | [375](screenshots/admin-menus-create-375.png) · – · – · [1440](screenshots/admin-menus-create-1440.png) | – |
| menus-edit | [375](screenshots/admin-menus-edit-375.png) · – · – · [1440](screenshots/admin-menus-edit-1440.png) | – |
| menus-show | [375](screenshots/admin-menus-show-375.png) · – · – · [1440](screenshots/admin-menus-show-1440.png) | – |
| nutrition-special-features | [375](screenshots/admin-nutrition-special-features-375.png) · [768](screenshots/admin-nutrition-special-features-768.png) · [1024](screenshots/admin-nutrition-special-features-1024.png) · [1440](screenshots/admin-nutrition-special-features-1440.png) | – |
| nutrition-special-features-create | [375](screenshots/admin-nutrition-special-features-create-375.png) · – · – · [1440](screenshots/admin-nutrition-special-features-create-1440.png) | – |
| packages | [375](screenshots/admin-packages-375.png) · [768](screenshots/admin-packages-768.png) · [1024](screenshots/admin-packages-1024.png) · [1440](screenshots/admin-packages-1440.png) | – |
| packages-create | [375](screenshots/admin-packages-create-375.png) · – · – · [1440](screenshots/admin-packages-create-1440.png) | 375: wider than the screen (L7) |
| packages-edit | [375](screenshots/admin-packages-edit-375.png) · – · – · [1440](screenshots/admin-packages-edit-1440.png) | 375: wider than the screen (L7) |
| packages-show | [375](screenshots/admin-packages-show-375.png) · – · – · [1440](screenshots/admin-packages-show-1440.png) | – |
| profile | [375](screenshots/admin-profile-375.png) · [768](screenshots/admin-profile-768.png) · [1024](screenshots/admin-profile-1024.png) · [1440](screenshots/admin-profile-1440.png) | – |
| results | [375](screenshots/admin-results-375.png) · [768](screenshots/admin-results-768.png) · [1024](screenshots/admin-results-1024.png) · [1440](screenshots/admin-results-1440.png) | – |
| results-create | [375](screenshots/admin-results-create-375.png) · – · – · [1440](screenshots/admin-results-create-1440.png) | 375: wider than the screen (L7) |
| results-edit | [375](screenshots/admin-results-edit-375.png) · – · – · [1440](screenshots/admin-results-edit-1440.png) | 375: wider than the screen (L7) |
| results-show | [375](screenshots/admin-results-show-375.png) · – · – · [1440](screenshots/admin-results-show-1440.png) | – |
| roles | [375](screenshots/admin-roles-375.png) · [768](screenshots/admin-roles-768.png) · [1024](screenshots/admin-roles-1024.png) · [1440](screenshots/admin-roles-1440.png) | – |
| roles-create | [375](screenshots/admin-roles-create-375.png) · – · – · [1440](screenshots/admin-roles-create-1440.png) | – |
| roles-edit | [375](screenshots/admin-roles-edit-375.png) · – · – · [1440](screenshots/admin-roles-edit-1440.png) | – |
| roles-show | [375](screenshots/admin-roles-show-375.png) · – · – · [1440](screenshots/admin-roles-show-1440.png) | – |
| services | [375](screenshots/admin-services-375.png) · [768](screenshots/admin-services-768.png) · [1024](screenshots/admin-services-1024.png) · [1440](screenshots/admin-services-1440.png) | – |
| services-create | [375](screenshots/admin-services-create-375.png) · – · – · [1440](screenshots/admin-services-create-1440.png) | 375: wider than the screen (L7) |
| services-edit | [375](screenshots/admin-services-edit-375.png) · – · – · [1440](screenshots/admin-services-edit-1440.png) | 375: wider than the screen (L7) |
| services-show | [375](screenshots/admin-services-show-375.png) · – · – · [1440](screenshots/admin-services-show-1440.png) | – |
| settings | [375](screenshots/admin-settings-375.png) · [768](screenshots/admin-settings-768.png) · [1024](screenshots/admin-settings-1024.png) · [1440](screenshots/admin-settings-1440.png) | 375: wider than the screen (L7) |
| testimonials | [375](screenshots/admin-testimonials-375.png) · [768](screenshots/admin-testimonials-768.png) · [1024](screenshots/admin-testimonials-1024.png) · [1440](screenshots/admin-testimonials-1440.png) | – |
| testimonials-create | [375](screenshots/admin-testimonials-create-375.png) · – · – · [1440](screenshots/admin-testimonials-create-1440.png) | 375: wider than the screen (L7) |
| testimonials-edit | [375](screenshots/admin-testimonials-edit-375.png) · – · – · [1440](screenshots/admin-testimonials-edit-1440.png) | 375: wider than the screen (L7) |
| testimonials-show | [375](screenshots/admin-testimonials-show-375.png) · – · – · [1440](screenshots/admin-testimonials-show-1440.png) | – |
| users | [375](screenshots/admin-users-375.png) · [768](screenshots/admin-users-768.png) · [1024](screenshots/admin-users-1024.png) · [1440](screenshots/admin-users-1440.png) | – |
| users-create | [375](screenshots/admin-users-create-375.png) · – · – · [1440](screenshots/admin-users-create-1440.png) | – |
| users-edit | [375](screenshots/admin-users-edit-375.png) · – · – · [1440](screenshots/admin-users-edit-1440.png) | Role dropdown and password, also open to the "user" role (C2) |
| users-show | [375](screenshots/admin-users-show-375.png) · – · – · [1440](screenshots/admin-users-show-1440.png) | 375: wider than the screen (L7) |
| weekly-diet-plans | [375](screenshots/admin-weekly-diet-plans-375.png) · [768](screenshots/admin-weekly-diet-plans-768.png) · [1024](screenshots/admin-weekly-diet-plans-1024.png) · [1440](screenshots/admin-weekly-diet-plans-1440.png) | – |
| weekly-diet-plans-create | [375](screenshots/admin-weekly-diet-plans-create-375.png) · – · – · [1440](screenshots/admin-weekly-diet-plans-create-1440.png) | 375: wider than the screen (L7) |
| weekly-diet-plans-edit | [375](screenshots/admin-weekly-diet-plans-edit-375.png) · – · – · [1440](screenshots/admin-weekly-diet-plans-edit-1440.png) | 375: wider than the screen (L7) |
| weekly-diet-plans-show | [375](screenshots/admin-weekly-diet-plans-show-375.png) · – · – · [1440](screenshots/admin-weekly-diet-plans-show-1440.png) | – |

---

## 5. Other findings (not on the checklist)

- **The live site is behind `master`.** Everything in sections 2–3 describes the code at `2d56911`. Until C1 is fixed, the live site is also missing every fix from the first audit (escaping, rate limits, headers, Voyager patches, the phone number, the mobile layout fixes).
- **A regression from the previous fixes (H1).** The test written for that fix checked that the inline style was gone, not what the section then looks like. Add a test that the placeholder is never shown (L20).
- **The footer agency domain now redirects elsewhere (M21).** Worth checking soon.
- **`public/storage` vs. a symlink on the server (M24)** may make the next deploy refuse to run. Check it at the same time as the SSH fix.
- **Laravel 13 accepts same-site form posts without a CSRF token.** It checks the browser's `Sec-Fetch-Site` header instead, so the "419 page expired" screen rarely appears. A token-less post from the site was accepted and redirected back with validation errors (`error-419-375.png` therefore shows the contact page). Cross-site posts are still rejected.
- **The "user" role in context (C2).** The two roles were probably set up quickly in 2022. If more staff accounts are planned, create a dedicated "editor" role with content permissions only.
- **Timings are from local Docker on Windows**, where the first byte takes 0.45–1.3 s because of slow file access. Production on LiteSpeed is likely faster for that part. Image weight and render-blocking dominate either way.
- **Screenshot artifacts:** in full-page screenshots, the sticky header sometimes appears in the middle of the page, and maps may show as blank because they load lazily. These are capture artifacts, not site bugs.
- **Previous audit:** the first report and its screenshots are kept in `screenshots/first-audit/` (`AUDIT_REPORT-first-audit.md`), and the after-fix screenshots in `screenshots/after-fixes/`.

---

**Checklist confirmation:** I re-read sections A–K of the request. All 79 items (A 5, B 10, C 13, D 7, E 9, F 7, G 9, H 6, I 3, J 3, K 7) have a result in section 3. Step 1 (site map, page log) is in section 3 as well.
