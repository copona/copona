# Changelog

## 0.3.0 — unreleased

### Core merged back

- `copona/core` now lives in this repository under `src/` (history kept via
  `git subtree`), so core fixes no longer need a separate tag and lock bump.
  Its dependencies are required directly; the unused `braintree/braintree_php`
  was dropped.
- The web installer and `php copona install` share one installer
  (`src/Classes/Install.php`). The web installer now runs Phinx migrations
  again (`phinx.php` had been refusing them with a 403), and
  `php copona cache:clear` no longer crashes.

### Security

- **Laravel 12.** `copona/core` is now on its tagged `v0.3.0` release, which moves
  `laravel/framework` from 10.50.2 to 12.x (CVE-2025-27515; Laravel 10 no longer
  receives security fixes). 0.2.0 depended on an untagged `copona/core` dev
  branch, which is why installing it needed `--stability=dev`. (#287, #288)
- Dependency bumps: `guzzlehttp/guzzle` 7.15.3, `league/commonmark` 2.10.0,
  `twig/twig` 3.28, plus npm updates for Tiptap, PostCSS, immutable and
  linkify-it. (#285, #286, #290, #292–#295)

### Storefront

- Redesigned cart: two-column layout with an order summary sidebar, pill
  quantity controls that update the cart instantly, and no separate Update
  button. The shipping estimator on the cart page has been removed. (#280, #281)
- Add-to-cart now shows a toast with the product name and a View Cart button. (#284)
- Bootstrap 5.3.3 compiled from SCSS with Copona design tokens, replacing the
  stock `bootstrap.min.css` (only the modules the templates use). (#282)
- UI polish across the top bar, cart dropdown, search page, category page,
  social share icons and coupon/voucher/reward accordions. (#283)
- Guest checkout shows the cart summary immediately and uses Bootstrap 5 forms. (#280, #284)

### Admin

- Tiptap editor: images are picked from the file manager, have an inline
  width/height/alt panel, and the source view is CodeMirror with HTML
  highlighting. (#277)
- Admins can change their own password. (#283)
- Tax rates have per-language names (#200, via #278).
- "View on site" buttons on the information and manufacturer forms (#209, via #279).
- Stock status, weight class and length class dropdowns on the product form are
  no longer empty when the admin language has no entries (#227, via #277).

### Fixes

- PHP warnings removed in the cart AJAX edit handler and on the search page with
  no query. (#280, #284)

### Docs

- README Docker instructions now match the real installer (`php copona install`)
  and warn against creating `.env` by hand. (#289)

### Upgrading from 0.2.0

1. `git pull` (or download the release), then `composer install`.
2. Run the database migrations: `php vendor/bin/phinx migrate`. This release adds
   one migration, `20260611000001_tax_rate_description`, which creates
   `tax_rate_description` and copies each existing tax rate name into every
   language.
3. Built CSS/JS (`themes/default/assets/css/main.css`,
   `admin/view/javascript/dist/`) is committed, so no npm build is needed unless
   you change the SCSS or editor sources (`npm install && npm run build`).
4. Custom themes that included `bootstrap.min.css` or the old cart templates
   (`cart_info.tpl`, `guest.tpl`) should be compared against the default theme.
5. Existing installs keep a now-unused `shipping_estimator` setting row; it is
   harmless.
