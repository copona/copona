#!/bin/bash
# Oracle Cloud "Create instance" > Show advanced options > Management >
# "Paste cloud-init script": paste this file. On first boot it installs
# Docker, the demo store (random DB password) and the hourly reset.
# Afterwards add the Cloudflare Tunnel token, see README.md.
# Keep this file plain ASCII: the Oracle console can reject user data with
# characters such as arrows ("Incorrectly formatted request").
set -euxo pipefail
exec > /var/log/copona-demo-setup.log 2>&1

BRANCH=master

apt-get update
apt-get install -y docker.io docker-compose-v2 git openssl
usermod -aG docker ubuntu

git clone --branch "$BRANCH" https://github.com/copona/copona.git /home/ubuntu/copona
cd /home/ubuntu/copona/deploy/demo

pw="$(openssl rand -hex 24)"
sed -e "s/^MARIADB_ROOT_PASSWORD=.*/MARIADB_ROOT_PASSWORD=$pw/" \
    -e "s/^DB_PASSWORD=.*/DB_PASSWORD=$pw/" demo.env.example > demo.env
chmod 600 demo.env

./demo.sh install

chown ubuntu:ubuntu demo.env snapshot.sql

echo '0 * * * * root /home/ubuntu/copona/deploy/demo/demo.sh reset >> /var/log/copona-demo-reset.log 2>&1' > /etc/cron.d/copona-demo
echo "Copona demo setup finished"
