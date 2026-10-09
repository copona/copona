# Public demo store

Runs a Copona demo store that anyone can browse and log in to, on a free
Oracle Cloud VM behind a free Cloudflare Tunnel, with the database restored
from a clean snapshot every hour.

## What demo mode does

`DEMO_MODE=true` (set by `docker-compose.yml`, see `config/demo.php`):

- **Admin is read-only.** Every admin POST except the login form is refused,
  and the `modify` permission is denied for everyone, so saves, deletes,
  uploads, extension installs, settings and password changes do nothing.
  Visitors can still open every page and form.
- **The admin login form is pre-filled** with `DEMO_ADMIN_USERNAME` /
  `DEMO_ADMIN_PASSWORD`, and "Forgotten password" is hidden.
- **No e-mail is sent** from the store, admin or catalog.
- **Catalog file uploads are refused.**
- **A banner** on every catalog and admin page says it is a demo that resets
  `DEMO_RESET_INTERVAL`.

Customers can still register, review and place orders; the hourly reset
throws that away. The demo data only enables Cash on Delivery and Free
Checkout, so no real payment can happen.

To change the demo data itself, set `DEMO_MODE=false` in the repo's `.env`,
make the changes in the admin, run `./demo.sh snapshot`, and set it back.

## Setup

### 1. Oracle Cloud VM (free)

1. Sign up at <https://www.oracle.com/cloud/free/> (a card is needed for
   verification). Keep the account on the **Free Tier**; don't upgrade to
   Pay As You Go unless you want to risk charges.
2. Create a compute instance: **Ampere A1** shape (2 OCPU / 12 GB is within
   Always Free), Ubuntu 24.04 image, your SSH key. No ingress rules beyond
   SSH are needed, because the tunnel connects outwards.
3. On the VM:

   ```bash
   sudo apt-get update && sudo apt-get install -y docker.io docker-compose-v2 git
   sudo usermod -aG docker ubuntu && newgrp docker
   git clone https://github.com/copona/copona.git && cd copona/deploy/demo
   ```

Oracle can reclaim Always Free instances that stay almost idle for 7 days;
a public demo with an hourly reset normally stays above that threshold.

### 2. Cloudflare Tunnel (free)

1. Add a domain to Cloudflare (free plan), for example `copona.org`.
2. In Cloudflare Zero Trust, go to **Networks → Tunnels → Create a tunnel**
   (type *cloudflared*), and copy the tunnel token.
3. Add a public hostname to the tunnel, for example `demo.copona.org`, with
   service `http://web:80`.
4. Optional but recommended: a rate-limiting rule on `/admin/` and
   `/index.php?route=account/` under **Security → WAF**.

### 3. Install the store

```bash
cp demo.env.example demo.env
# set MARIADB_ROOT_PASSWORD and DB_PASSWORD (same value), and TUNNEL_TOKEN
./demo.sh install
```

`install` builds the image, starts MariaDB, the store and the tunnel, runs the
installer, turns on HTTPS links, and saves `snapshot.sql` as the clean state.

### 4. Hourly reset

```bash
crontab -e
# add:
0 * * * * /home/ubuntu/copona/deploy/demo/demo.sh reset >> /home/ubuntu/demo-reset.log 2>&1
```

### Updating

```bash
git pull
./demo.sh reset
```

Schema changes need a fresh install: `docker compose down -v`, remove the
repo's `.env`, then `./demo.sh install` again.
