<?php

/**
 * Smoke test for a freshly installed Copona store with the demo data.
 *
 * Usage: php .github/scripts/smoke-test.php http://127.0.0.1:8000
 *
 * Fetches the key storefront and admin pages, fails on non-200 responses or
 * PHP fatal errors in the output, and checks that every JSON-LD block parses.
 */

$base = rtrim($argv[1] ?? 'http://127.0.0.1:8000', '/');

$checks = [
    'Home' => [
        'url'      => '/',
        'contains' => ['"@type":"WebSite"', '"@type":"Organization"'],
    ],
    'Category' => [
        'url'      => '/index.php?route=product/category&path=100',
        'contains' => ['iPhone 16 Pro', '"@type":"BreadcrumbList"'],
    ],
    'Product' => [
        'url'      => '/index.php?route=product/product&product_id=1',
        'contains' => ['iPhone 16 Pro', '"@type":"Product"', '"@type":"Offer"', '"@type":"BreadcrumbList"', 'property="og:type" content="product"'],
    ],
    'Search' => [
        'url'      => '/index.php?route=product/search&search=pro',
        'contains' => ['iPhone 16 Pro'],
    ],
    'Cart' => [
        'url'      => '/index.php?route=checkout/cart',
        'contains' => [],
    ],
    'Admin login' => [
        'url'      => '/admin/',
        'contains' => ['name="username"', 'name="password"'],
    ],
];

$fatal_markers = ['Fatal error', 'Parse error', 'Uncaught ', 'Something went wrong'];

$failures = 0;

foreach ($checks as $name => $check) {
    $context = stream_context_create(['http' => ['ignore_errors' => true, 'timeout' => 30]]);
    $body = @file_get_contents($base . $check['url'], false, $context);
    $status = 0;

    foreach ($http_response_header ?? [] as $header) {
        if (preg_match('#^HTTP/\S+\s+(\d{3})#', $header, $m)) {
            $status = (int)$m[1]; // last one wins when redirects were followed
        }
    }

    $errors = [];

    if ($body === false) {
        $errors[] = 'request failed';
        $body = '';
    } elseif ($status !== 200) {
        $errors[] = "HTTP $status";
    }

    foreach ($fatal_markers as $marker) {
        if (stripos($body, $marker) !== false) {
            $errors[] = "output contains \"$marker\"";
        }
    }

    foreach ($check['contains'] as $needle) {
        if (strpos($body, $needle) === false) {
            $errors[] = "missing $needle";
        }
    }

    preg_match_all('#<script type="application/ld\+json">(.*?)</script>#s', $body, $blocks);
    foreach ($blocks[1] as $json) {
        json_decode($json);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $errors[] = 'invalid JSON-LD: ' . json_last_error_msg();
        }
    }

    if ($errors) {
        $failures++;
        echo "FAIL  $name ({$check['url']}): " . implode('; ', $errors) . "\n";
        echo substr(strip_tags($body), 0, 2000) . "\n\n";
    } else {
        echo "ok    $name ({$check['url']})" . ($blocks[1] ? ', ' . count($blocks[1]) . ' JSON-LD block(s)' : '') . "\n";
    }
}

exit($failures ? 1 : 0);
