# Copona Project — Claude Knowledge Base

## Project Overview

Copona is an OpenCart-based PHP 8.3 e-commerce platform. It lives at `/home/arnis/copona/`.

- **Frontend**: `http://localhost:8080`
- **Admin**: `http://localhost:8080/admin` (user: `admin`, pass: `admin123`)
- **DB**: MariaDB 12.3.2, database `copona`, prefix `cp_`

---

## Docker Setup

```yaml
# docker-compose.yml (bind mounts, not named volumes)
web:  copona-web-1  → port 8080  (webdevops/php-apache-dev:8.3, mounts .:/app)
db:   copona-db-1   → MariaDB 12.3.2 (bind mount: ./.mysql:/var/lib/mysql)
```

**DB hostname inside the container is `database` (not `localhost`).**

The `.mysql/` and `vendor/` directories are written by the container user (uid 999, shows as `ollama` on the host). You **cannot** `rm -rf` them as the `arnis` user. Use an Alpine container:

```bash
docker run --rm -v /home/arnis/copona/.mysql:/target alpine sh -c "rm -rf /target/* /target/.*" 2>/dev/null
docker run --rm -v /home/arnis/copona/vendor:/vendor alpine sh -c "rm -rf /vendor/*"
```

---

## Fresh Install Procedure

### Normal first install (fresh clone)

```bash
# 1. Start containers (--build builds the image on first run)
docker compose up -d --build

# 2. Wait for MariaDB (~12s)
sleep 12

# 3. Install Composer deps — run as root (avoids ~/.composer permission issues from host mount)
docker exec -w /app copona-web-1 composer install --no-interaction

# 4. Run the CLI installer — MUST use -u application so log files are owned by
#    uid 1000 (same as PHP-FPM), not root. Root-owned 644 log files can't be
#    written by the web process and will crash the frontend.
docker exec -u application copona-web-1 php /app/copona install --no-interaction
```

The CLI installer reads these env vars (already set in `docker-compose.yml`):
`DB_DRIVER`, `DB_HOSTNAME`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `DB_PORT`, `DB_PREFIX`, `ADMIN_USERNAME`, `ADMIN_PASSWORD`, `ADMIN_EMAIL`

**Why `-u application` only for the install step**: `docker exec` defaults to root. Log files created by root get permissions `644`, which PHP-FPM (running as `application`, uid 1000) cannot write to. The `composer install` step must run as root because the host `~/.composer` cache mount may have files not readable by uid 1000 inside the container.

### Full teardown and reinstall from scratch

```bash
# 1. Stop containers
docker compose down

# 2. Wipe DB data (bind mount, owned by uid 999 — needs Alpine container to delete)
docker run --rm -v /home/arnis/copona/.mysql:/target alpine sh -c "rm -rf /target/* /target/.*" 2>/dev/null

# 3. Wipe vendor (also owned by container user)
docker run --rm -v /home/arnis/copona/vendor:/vendor alpine sh -c "rm -rf /vendor/*"

# 4. Remove generated files
rm -f /home/arnis/copona/.env
rm -f /home/arnis/copona/.htaccess
rm -f /home/arnis/copona/config/dev/database.php

# 5. Rebuild and restart
docker compose up -d --build

# 6. Wait for MariaDB (~12s)
sleep 12 && docker exec copona-db-1 mariadb -u root -proot -e "SELECT 1"

# 7. Install Composer deps and run installer
docker exec -w /app copona-web-1 composer install --no-interaction
docker exec -u application copona-web-1 php /app/copona install --no-interaction
```

---

## Install Internals

**`install/model/install/install.php` and `vendor/copona/core/src/Classes/Install.php`**

The `database()` method loads `migrations/structure.sql` and replaces `oc_` prefix with the configured prefix. It only replaces these four patterns:

```
DROP TABLE IF EXISTS `oc_   →  DROP TABLE IF EXISTS `{prefix}
CREATE TABLE `oc_           →  CREATE TABLE `{prefix}
INSERT INTO `oc_            →  INSERT INTO `{prefix}
ALTER TABLE `oc_            →  ALTER TABLE `{prefix}
```

**Known gap**: `LOCK TABLES \`oc_...\` WRITE;` lines (output by mysqldump) are NOT replaced, causing "Table doesn't exist" errors. **Fix**: strip all `LOCK TABLES` and `UNLOCK TABLES` lines from `structure.sql` before committing — they serve no purpose in the install context.

After `database()`, the installer calls `migration()` which runs Phinx. On a fresh install with the consolidated `structure.sql`, the `cp_migrations` table is pre-populated with all 29 version records so Phinx runs 0 migrations.

**`app is installed` check**: `Install::checkIfInstalled()` just looks for `.env`. If the site shows "Something went wrong" after the containers restart, the DB was likely wiped but `.env` survived. Fix:
```bash
rm -f /home/arnis/copona/.env /home/arnis/copona/.htaccess /home/arnis/copona/config/dev/database.php
docker exec copona-db-1 mariadb -u root -proot -e "DROP DATABASE IF EXISTS copona; CREATE DATABASE copona CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
docker exec -u application copona-web-1 php /app/copona install --no-interaction
```

---

## Migrations / Schema

- All 29 historical Phinx migrations were consolidated into `migrations/structure.sql` (7349 lines, 140 tables).
- `structure.sql` uses `oc_` prefix throughout; installer replaces on load.
- New schema changes still go in new Phinx migration PHP files in `migrations/`.
- `oc_migrations` table in `structure.sql` is pre-populated → Phinx skips all on fresh install.

---

## Image Handling

Products can have either a **local image** (`cp_product.image`, path relative to `DIR_IMAGE`) or an **external URL** (`cp_product.image_url`). The demo seed uses `image_url` only (placehold.co URLs).

**Helper**: `catalog/model/tool/image.php` → `productImage($localImage, $imageUrl, $width, $height, $method)`
- Tries local image resize first, falls back to `image_url`, falls back to no-image placeholder.
- Never call `resize()`/`propsize()` etc. directly with a remote URL — those methods resolve from `DIR_IMAGE`.

All catalog listing controllers (`category.php`, `special.php`, `search.php`, `manufacturer.php`, `featured.php`, `bestseller.php`) use `productImage()`. The main product page controller (`product/product.php`) has a three-branch if/elseif/else for local / url / neither.

`catalog/model/catalog/product.php` → `getProduct()` (singular) returns `image_url` so featured/bestseller modules can access it.

---

## Admin Order History

`admin/controller/sale/order.php` → `addHistory()` POSTs to add order status history.

**Do not** `require_once(DIR_CATALOG . 'model/checkout/order.php')` + `new ModelCheckoutOrder()` from admin context — the admin Loader only resolves from `admin/model/`, so any `$this->load->model('tool/mail')` inside ModelCheckoutOrder will fatal.

**Correct pattern**: load the admin order model and call its native method:
```php
$this->load->model('sale/order');
$this->model_sale_order->addOrderHistory($order_id, $order_status_id, $comment, $notify, $override);
```

`admin/model/sale/order.php` → `addOrderHistory()` uses direct SQL + `new Mail()` (no model loader needed for mail).

---

## Demo Seed Data

15 modern products seeded (originally via migration, now baked into `structure.sql`):
iPhone 16 Pro, Galaxy S25 Ultra, Pixel 9 Pro, MacBook Pro 14" M4, Dell XPS 15, iPad Pro 13", Galaxy Tab S10+, AirPods Pro 2, Sony WH-1000XM6, Apple Watch Series 10, Galaxy Watch 7, PS5 Slim, Xbox Series X, Logitech MX Master 3S, HomePod mini.

Images use: `https://placehold.co/800x600/1d1d1f/f5f5f7?text=Product+Name`

Categories: Smartphones (100), Laptops & Computers (101), Windows Laptops (102), MacBooks (103), Tablets (104), Audio & Headphones (105), Wearables (106), Gaming (107), Consoles (108), Gaming Accessories (109), Smart Home (110)

---

## Admin Bar (Catalog → Admin Edit Link)

When an admin is logged in and browses the catalog, a fixed floating bar appears at the bottom of **every catalog page** with context-sensitive edit links.

**How it works:**

Admin and catalog share the same PHP session namespace. Both use the `default` cookie as the session key (set by `system/library/session.php` via `$session->start()` in `system/framework.php`). This means:

- `$this->session->data['user_id']` is set after admin login — readable from any catalog controller
- `$this->session->data['token']` holds the admin CSRF token — also readable

**Files involved:**

- `catalog/controller/common/header.php` — checks `user_id`/`token`; sets `$data['admin_bar']` (bool) and `$data['admin_bar_links']` (array of `{text, href}`)
- `themes/default/template/common/header.tpl` — renders bar when `$admin_bar` is true
- `themes/simplica/template/common/header.tpl` — same

**Context-sensitive links (route-based):**

- `product/product` + `product_id` → `catalog/product/edit&product_id={id}`
- `product/category` + `path` → `catalog/category/edit&category_id={last segment of path}`
- `information/information` + `information_id` → `catalog/information/edit&information_id={id}`
- All other pages: bar shows with only the gear "Admin" link to `/admin/`

**To add more edit links:** add an `elseif` branch in `catalog/controller/common/header.php` pushing to `$data['admin_bar_links']`.

**Security:** No additional signing needed. `user_id` is only set after a real server-side admin login validated against the DB. An attacker cannot inject a fake `user_id` into the session without server access (no client-forgeable path).

---

## OpenCart/Copona Architecture Notes

- **Admin Loader** resolves models only from `admin/model/` — cannot load catalog models.
- **Catalog Loader** resolves from `catalog/model/`.
- Config is in `.env` (DB creds) + `config/{env}/database.php` (generated by installer). No traditional `config.php`.
- `phinx.php` at project root configures Phinx; reads `.env` for DB connection.
- Prefix substitution in install only handles 4 DDL patterns — any other `oc_` references in SQL must be handled separately or stripped.
- **Session architecture**: Both admin and catalog use the same PHP `$_SESSION` via the `default` cookie. Admin sets `session->data['user_id']` + `['token']`; catalog sets `session->data['customer_id']`. No conflict — different keys. From catalog context you can always read admin session state via `$this->session->data['user_id']`.

---

## Git Workflow

- Always branch → PR → merge to master (never direct push to master).
- Don't commit after each change; batch at end of session.
- Remote: `git@github.com:copona/copona.git`

---

## Local Dev on a Custom Port (multiple stacks on one host)

If other Docker projects already occupy 8080/3306, don't edit the tracked
`docker-compose.yml` — add a `docker-compose.override.yml` (gitignored) next
to it:

```yaml
services:
  db:
    ports:
      - "3316:3306"
  web:
    ports:
      - "8091:80"
```

Docker Compose merges override files automatically; no `-f` flag needed.
Then follow the normal fresh-install procedure — it works unchanged since
only the *host* port mapping changes, not the container-internal ports.

---

## Automated Testing — There Is None

Neither `copona/copona` nor `copona/core` has a test suite: no PHPUnit/Pest
config, no `tests/` directory, no CI workflows (`.github/workflows` doesn't
exist in either repo). The only checks are static analysis via Composer
scripts in `copona/copona`'s `composer.json`:

```bash
composer analyse   # phpstan
composer cs-check   # php-cs-fixer --dry-run
composer cs-fix      # php-cs-fixer fix
```

`phpstan.neon` / the baseline currently has pre-existing unrelated findings
(`pr` function not found in a few catalog controllers, a `DB_PREFIX`
constant baseline count mismatch) — these are static-analysis artifacts of
globals/constants defined at runtime bootstrap, not real bugs, and predate
any dependency work. Don't treat them as regressions from unrelated changes.

For actual verification, there's no substitute for booting the stack and
smoke-testing: frontend home, a category listing (confirms DB reads +
`productImage()`), a product detail page (SEO routing), and an admin
login → dashboard round trip (confirms session/auth + DB writes).

---

## laravel/framework Is Not a Direct Dependency — It's Transitive via copona/core

`copona/copona`'s `composer.json` does **not** require `laravel/framework`
directly. It requires `copona/core` via a VCS repository:

```json
"repositories": {
  "copona-core": { "type": "vcs", "url": "git@github.com:copona/core.git" }
},
"require": { "copona/core": "^0.3.0" }
```

`copona/core` is where `laravel/framework` actually lives (used for the
`Illuminate\Database` Capsule/Eloquent adapter — see
`src/Database/Adapters/Eloquent.php` and `src/Database/OrmModel.php`, the
*only* two files in that package touching the `Illuminate` namespace).

**Why Dependabot can't auto-fix Laravel advisories in this repo**: the
version constraint Dependabot would need to edit lives in a different
repo's `composer.json`. GitHub's dependency graph still flags the
vulnerable version here (because `composer.lock` records it), but there's
no manifest in *this* repo for a bot to patch. The fix always has to be a
manual PR against `copona/core`, followed by a version bump here.

**Laravel version history in copona/core** (`composer.json` → `require` →
`laravel/framework`): 5.6 → 5.8 → 6.20 → 9.0 → 10.48 (CVE-2025-27515) →
12.0 (2026-07, Laravel 10 hit EOL for security support Feb 2025 with
10.50.2 already the newest 10.x patch — no further 10.x fix existed, so
the only real remediation was a major-version jump).

**When bumping laravel/framework in copona/core again**, watch for:
- `phpfastcache/phpfastcache` — 8.x pins `psr/simple-cache ~1.0`, which
  conflicts with `robmorgan/phinx`'s `cakephp/datasource` (`^2.0||^3.0`).
  Needs `phpfastcache` `^9.0`+ alongside any Laravel version requiring
  newer `symfony/console`.
- `symfony/finder`, `symfony/console`, `symfony/css-selector` are also
  required directly by `copona/core` (not just pulled in via Laravel) —
  their constraint has to be widened to match whatever `symfony/console`
  version the new Laravel release requires.
- Prefer pinning `copona/copona`'s `composer.json` to a tagged `copona/core`
  release (e.g. `^0.3.0`) rather than `dev-<branch>` — dev-branch refs can
  be deleted or rewritten after merge, and there's no reason to stay on one
  once the branch's PR has landed and been tagged.
