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

## OpenCart/Copona Architecture Notes

- **Admin Loader** resolves models only from `admin/model/` — cannot load catalog models.
- **Catalog Loader** resolves from `catalog/model/`.
- Config is in `.env` (DB creds) + `config/{env}/database.php` (generated by installer). No traditional `config.php`.
- `phinx.php` at project root configures Phinx; reads `.env` for DB connection.
- Prefix substitution in install only handles 4 DDL patterns — any other `oc_` references in SQL must be handled separately or stripped.

---

## Git Workflow

- Always branch → PR → merge to master (never direct push to master).
- Don't commit after each change; batch at end of session.
- Remote: `git@github.com:copona/copona.git`
