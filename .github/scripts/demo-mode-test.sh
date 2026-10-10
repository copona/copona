#!/usr/bin/env bash
# Checks DEMO_MODE against a running store: the banner shows, the admin login
# is pre-filled, and admin writes (form and AJAX) are refused.
#
#   demo-mode-test.sh http://127.0.0.1:8000
set -euo pipefail

base="${1%/}"
jar="$(mktemp)"
trap 'rm -f "$jar"' EXIT
fail() { echo "FAIL: $*" >&2; exit 1; }
# Fetch a page into a variable first: piping curl into `grep -q` under
# pipefail fails whenever grep exits before curl has written everything.
page() { curl -fsS -b "$jar" "$1"; }

home="$(page "$base/")"
grep -q 'copona-demo-bar' <<<"$home" || fail 'catalog demo banner missing'
echo 'ok   catalog banner'

login_page="$(curl -fsS -c "$jar" "$base/admin/index.php?route=common/login")"
grep -q "value=\"$DEMO_ADMIN_USERNAME\"" <<<"$login_page" || fail 'admin login form not pre-filled'
echo 'ok   login form pre-filled'

location="$(curl -sS -b "$jar" -c "$jar" -o /dev/null -w '%{redirect_url}' \
    --data-urlencode "username=$DEMO_ADMIN_USERNAME" --data-urlencode "password=$DEMO_ADMIN_PASSWORD" \
    "$base/admin/index.php?route=common/login")"
token="$(sed -n 's/.*token=\([A-Za-z0-9]*\).*/\1/p' <<<"$location")"
[ -n "$token" ] || fail "admin login did not redirect with a token (got '$location')"
echo 'ok   admin login'

dashboard="$(page "$base/admin/index.php?route=common/dashboard&token=$token")"
grep -q 'copona-demo-bar' <<<"$dashboard" || fail 'admin demo banner missing'
echo 'ok   admin banner'

before="$(mysql -h "$DB_HOSTNAME" -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" -N -e "SELECT name FROM ${DB_PREFIX}product_description WHERE product_id = (SELECT MIN(product_id) FROM ${DB_PREFIX}product) LIMIT 1")"
product_id="$(mysql -h "$DB_HOSTNAME" -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" -N -e "SELECT MIN(product_id) FROM ${DB_PREFIX}product")"

status="$(curl -sS -b "$jar" -o /dev/null -w '%{http_code}' \
    -H "Referer: $base/admin/index.php?route=catalog/product&token=$token" \
    --data 'selected[]='"$product_id" \
    "$base/admin/index.php?route=catalog/product/delete&token=$token")"
[ "$status" = 302 ] || fail "form POST was not redirected (HTTP $status)"

ajax="$(curl -sS -b "$jar" -H 'X-Requested-With: XMLHttpRequest' --data 'order_status_id=1' \
    "$base/admin/index.php?route=sale/order/addHistory&token=$token&order_id=1")"
grep -q 'disabled in the demo' <<<"$ajax" || fail "AJAX POST not refused: $ajax"

after="$(mysql -h "$DB_HOSTNAME" -u"$DB_USERNAME" -p"$DB_PASSWORD" "$DB_DATABASE" -N -e "SELECT name FROM ${DB_PREFIX}product_description WHERE product_id = $product_id LIMIT 1")"
[ -n "$after" ] && [ "$after" = "$before" ] || fail "product $product_id changed or was deleted"
echo 'ok   admin writes refused'

products="$(page "$base/admin/index.php?route=catalog/product&token=$token")"
grep -q 'disabled in the demo' <<<"$products" || fail 'refusal message not shown after redirect'
grep -q 'coponaDemoPopup();' <<<"$products" || fail 'refusal popup not opened after redirect'
echo 'ok   refusal message shown'
