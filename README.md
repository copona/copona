# Copona

[![CI](https://github.com/copona/copona/actions/workflows/ci.yml/badge.svg)](https://github.com/copona/copona/actions/workflows/ci.yml)
![PHP 8.3+](https://img.shields.io/badge/PHP-8.3%2B-777bb4)
[![License: GPL v3](https://img.shields.io/badge/License-GPLv3-blue.svg)](LICENSE)

**A modern, open-source PHP e-commerce platform.** Copona started as a fork of
[OpenCart](https://www.opencart.com) and has been rebuilt on a current stack:
if you like OpenCart's simplicity but not its age, this is for you.

<p align="center">
  <a href="https://demo.copona.org"><strong>▶ Try the live demo</strong></a> ·
  <a href="https://demo.copona.org/admin/">Admin demo</a> (login pre-filled) ·
  <a href="#try-it-locally-in-2-minutes">Run it locally</a>
</p>

![Copona storefront with the Signal theme](docs/screenshots/home.png)

## Highlights

- **A storefront that looks current out of the box.** The default "Signal" theme
  is an original design (Bricolage Grotesque + Figtree, one accent colour) built
  on Bootstrap 5 with a Vite/SCSS pipeline, and it is fully responsive.
- **SEO and speed without plugins.** It ships schema.org JSON-LD (Product, Offer,
  rating, breadcrumbs, site search), Open Graph tags, canonical URLs and a
  sitemap. Thumbnails are WebP, images below the fold load lazily, and every
  asset URL carries a version stamp so deploys never leave stale CSS behind.
- **PHP 8.3 and a modern core.** Laravel 12's Eloquent/Query Builder sits next to
  the familiar OpenCart-style MVC, schema changes are Phinx migrations, and
  `php copona install` sets up a store from the command line.
- **Built for real use.** You get multi-store, multi-currency, guest checkout,
  coupons/vouchers/rewards, reviews, wishlist and compare, plus a storefront
  admin bar with one-click "edit this product/category/page" links.
- **A safe public demo mode.** `DEMO_MODE=true` makes the admin read-only and
  disables e-mail, which is how demo.copona.org runs.

## Why Copona

| | Copona | OpenCart 3 | WooCommerce |
|---|---|---|---|
| PHP | 8.3+ | 7.x–8.x | 7.4+ (via WordPress) |
| Front-end | Bootstrap 5, Vite + SCSS build | Bootstrap 3 | theme-dependent |
| Admin editor | Tiptap + CodeMirror source view | Summernote | Gutenberg |
| Database layer | Laravel 12 Eloquent/Query Builder alongside the classic models | custom | WordPress `$wpdb` |
| Migrations | Phinx | none (manual SQL) | plugin-specific |
| SEO out of the box | JSON-LD (Product, Offer, Rating, Breadcrumbs, WebSite), Open Graph, canonical URLs, sitemap | basic meta | plugin needed |
| Images | auto WebP thumbnails, lazy loading | JPEG/PNG only | plugin needed |
| Install | `php copona install` or Docker | web wizard | WordPress + plugin |
| Needs WordPress | no | no | yes |

## Screenshots

| Category | Product |
|---|---|
| ![Category listing](docs/screenshots/category.png) | ![Product page](docs/screenshots/product.png) |

| Mobile: home | Mobile: product | Admin: products |
|---|---|---|
| <img src="docs/screenshots/home-mobile.png" alt="Home page on mobile" width="260"> | <img src="docs/screenshots/product-mobile.png" alt="Product page on mobile" width="260"> | ![Admin product list](docs/screenshots/admin-products.png) |

<sub>Captured from [demo.copona.org](https://demo.copona.org); the
[Screenshots workflow](.github/workflows/screenshots.yml) regenerates them from
the demo data.</sub>

## Try it locally in 2 minutes

You need Docker. This starts a store with 15 demo products (phones, laptops,
audio, gaming…) in 11 categories:

```bash
git clone https://github.com/copona/copona.git && cd copona
docker compose up -d --build
sleep 15   # let MariaDB start
docker exec -w /app copona-web-1 composer install --no-interaction
docker exec -u application copona-web-1 php /app/copona install --no-interaction
```

- Storefront: http://localhost:8080
- Admin: http://localhost:8080/admin (user `admin`, password `admin123`)

## Roadmap

Next up are the Signal design for checkout, sidebar modules and affiliate pages,
then Stripe and PayPal Checkout v2, a cookie-consent banner and more bundled
languages. Ideas and votes are welcome in
[issues](https://github.com/copona/copona/issues).

## Contributing

Copona is under active development. Please try it and post issues, bugs or
**feature requests** at https://github.com/copona/copona/issues; pull requests
are welcome too. CI checks every PR: it lints all PHP, installs a fresh store and
smoke-tests the storefront and admin. If Copona is useful to you, a ⭐ on GitHub
helps other people find it.

## Requirements
* MySQL >= 5.6
* PHP >= 8.3
* Composer [https://getcomposer.org/](https://getcomposer.org/)

## Get started
`composer create-project copona/copona`

`cd copona && php copona install`

## Installation
* Getting project files
    * With Git (recommended)
        * Install Git [guide](http://rogerdudler.github.io/git-guide)
        * Install Composer [guide](https://getcomposer.org/doc/01-basic-usage.md#installing-dependencies)
        * Navigate to your webroot, for example:
            * `cd /var/www/public_html`
        * `git clone https://github.com/Copona/copona.git .`
        * `git config user.name "Your Name"`
        * `git config user.email youremail@yourdomain.org`
        * `git config core.fileMode false`
        * `composer install`
        * Open the installer at `http://domain/install`
    * Using manual download:
        * [Click here to download the master branch](https://github.com/copona/copona/archive/master.zip)
* Prepared environment
    * With Docker
        * Install [Docker](https://docs.docker.com/engine/installation/) and [Docker Compose](https://docs.docker.com/compose/install/)
        * Do **not** create `.env` by hand — the installer generates it, and its own "is this installed?" check just looks for `.env`, so a pre-existing one makes it skip setup and leave the database empty.
        * Execute `docker compose up -d --build`
        * Wait for MariaDB to finish starting (a few seconds), then run:
            * `docker exec -w /app <web-container> composer install --no-interaction`
            * `docker exec -u application <web-container> php /app/copona install --no-interaction`
        * The install command reads its DB/admin settings from the environment variables already set in `docker-compose.yml` (`DB_DRIVER`, `DB_HOSTNAME`, `DB_DATABASE`, `ADMIN_USERNAME`, etc.) and creates `.env`, the database schema, and runs migrations for you.
    * Manual install
        * Install a web server: Apache, IIS, etc.
        * Install PHP and MySQL
        * Install Composer [https://getcomposer.org/](https://getcomposer.org/)
        * From the command prompt, execute:
            * `composer install`
            * `php copona install` (interactive) — asks for DB/admin details, creates `.env`, and runs migrations
* Navigate to your web address: `http://domain-OR-IPaddress/` or `http://domain-OR-IPaddress/subfolder-where-you-cloned`
* If all requirements have been met, fill in the form and enjoy!

## Update
* If you installed Copona with Git (recommended), go to the folder where Copona is installed:
  * If you have not edited any files locally:
    * `git pull`
  * If you have edited files locally — you are a developer, you will know what to do!
  * Check the site; if there are problems, post them online, or you can always revert to the previous version.
* Run Composer install:
  * `composer install`
* Run database migration:
  * `php vendor/bin/phinx migrate` (https://github.com/copona/copona/wiki/Migration-Phinx)


## TODO

* Put into migrations:

```sql
ALTER TABLE `cp_url_alias`
ADD INDEX `query_language_id` (`query`, `language_id`);
```
