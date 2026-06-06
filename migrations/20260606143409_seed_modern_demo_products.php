<?php

use Phinx\Migration\AbstractMigration;

class SeedModernDemoProducts extends AbstractMigration
{
    public function up()
    {
        $p = defined('DB_PREFIX') ? DB_PREFIX : 'cp_';

        $this->execute("SET FOREIGN_KEY_CHECKS=0");

        // ── CLEAN OLD PRODUCTS ──────────────────────────────────────────────
        foreach ([
            'product', 'product_description', 'product_to_category', 'product_to_store',
            'product_image', 'product_option', 'product_option_value', 'product_attribute',
            'product_discount', 'product_special', 'product_reward', 'product_related',
            'product_filter', 'product_to_download', 'product_to_layout',
        ] as $t) {
            $this->execute("DELETE FROM `{$p}{$t}`");
        }
        $this->execute("ALTER TABLE `{$p}product` AUTO_INCREMENT = 1");

        // ── CLEAN OLD CATEGORIES ────────────────────────────────────────────
        foreach ([
            'category', 'category_description', 'category_path',
            'category_to_store', 'category_to_layout', 'category_filter',
        ] as $t) {
            $this->execute("DELETE FROM `{$p}{$t}`");
        }
        $this->execute("ALTER TABLE `{$p}category` AUTO_INCREMENT = 1");

        // ── CLEAN OLD URL ALIASES FOR PRODUCTS/CATEGORIES ──────────────────
        $this->execute("DELETE FROM `{$p}url_alias` WHERE `query` LIKE 'product_id=%' OR `query` LIKE 'category_id=%'");

        // ── CLEAN OLD MANUFACTURERS & ADD FRESH ────────────────────────────
        foreach (['manufacturer', 'manufacturer_to_store'] as $t) {
            if ($this->hasTable(ltrim($t, $p))) {
                $this->execute("DELETE FROM `{$p}{$t}`");
            }
        }
        $this->execute("DELETE FROM `{$p}manufacturer`");
        $this->execute("ALTER TABLE `{$p}manufacturer` AUTO_INCREMENT = 1");

        $manufacturers = [
            1 => ['Apple',     1],
            2 => ['Samsung',   2],
            3 => ['Google',    3],
            4 => ['Dell',      4],
            5 => ['Sony',      5],
            6 => ['Microsoft', 6],
            7 => ['Logitech',  7],
        ];
        foreach ($manufacturers as $id => [$name, $sort]) {
            $this->execute("INSERT INTO `{$p}manufacturer` (manufacturer_id, name, sort_order, date_modified) VALUES ({$id}, '{$name}', {$sort}, NOW())");
            $this->execute("INSERT INTO `{$p}manufacturer_to_store` (manufacturer_id, store_id) VALUES ({$id}, 0)");
        }

        // ── CLEAN & REBUILD ATTRIBUTE GROUPS/ATTRIBUTES ────────────────────
        foreach (['attribute', 'attribute_description', 'attribute_group', 'attribute_group_description'] as $t) {
            $this->execute("DELETE FROM `{$p}{$t}`");
        }

        $attrGroups = [
            1 => 'Display',
            2 => 'Performance',
            3 => 'Camera',
            4 => 'Battery & Connectivity',
        ];
        foreach ($attrGroups as $id => $name) {
            $this->execute("INSERT INTO `{$p}attribute_group` (attribute_group_id, sort_order) VALUES ({$id}, {$id})");
            $this->execute("INSERT INTO `{$p}attribute_group_description` (attribute_group_id, language_id, name) VALUES ({$id}, 1, '{$name}')");
        }

        $attrs = [
            // id, group, name
            [1,  1, 'Screen Size'],
            [2,  1, 'Resolution'],
            [3,  1, 'Display Type'],
            [4,  2, 'Processor'],
            [5,  2, 'RAM'],
            [6,  2, 'Storage Options'],
            [7,  3, 'Rear Camera'],
            [8,  3, 'Front Camera'],
            [9,  4, 'Battery'],
            [10, 4, 'Operating System'],
            [11, 4, 'Weight'],
            [12, 4, 'Connectivity'],
        ];
        foreach ($attrs as [$id, $group, $name]) {
            $this->execute("INSERT INTO `{$p}attribute` (attribute_id, attribute_group_id, sort_order) VALUES ({$id}, {$group}, {$id})");
            $this->execute("INSERT INTO `{$p}attribute_description` (attribute_id, language_id, name) VALUES ({$id}, 1, '{$name}')");
        }

        // ── CLEAN & REBUILD OPTION VALUES ──────────────────────────────────
        // Keep existing option types; only replace predefined values
        $this->execute("DELETE FROM `{$p}option_value`");
        $this->execute("DELETE FROM `{$p}option_value_description`");

        // option_id=1 (Radio), option_id=5 (Select)
        $optionValues = [
            // id, option_id, name, sort
            // --- Storage sizes (Select=5) ---
            [10, 5, '128 GB',  1],
            [11, 5, '256 GB',  2],
            [12, 5, '512 GB',  3],
            [13, 5, '1 TB',    4],
            // --- MacBook configs (Select=5) ---
            [20, 5, 'M4 Pro 12-core / 24 GB / 512 GB SSD',  1],
            [21, 5, 'M4 Pro 14-core / 24 GB / 1 TB SSD',    2],
            [22, 5, 'M4 Max 16-core / 48 GB / 1 TB SSD',    3],
            // --- Dell XPS configs (Select=5) ---
            [25, 5, 'Core Ultra 7 / 16 GB / 512 GB SSD',    1],
            [26, 5, 'Core Ultra 9 / 32 GB / 1 TB SSD',      2],
            // --- iPad connectivity (Select=5) ---
            [30, 5, 'Wi-Fi',                 1],
            [31, 5, 'Wi-Fi + Cellular',      2],
            // --- PS5 editions (Select=5) ---
            [35, 5, 'Disc Edition',    1],
            [36, 5, 'Digital Edition', 2],
            // --- Colors (Radio=1) ---
            [50, 1, 'Black',         1],
            [51, 1, 'Silver',        2],
            [52, 1, 'Gold',          3],
            [53, 1, 'Blue',          4],
            [54, 1, 'White',         5],
            [55, 1, 'Graphite',      6],
            [56, 1, 'Platinum Silver', 7],
            // --- Watch sizes (Radio=1) ---
            [60, 1, '42mm', 1],
            [61, 1, '46mm', 2],
        ];
        foreach ($optionValues as [$id, $optId, $name, $sort]) {
            $nameSafe = $this->getAdapter()->getConnection()->quote($name);
            $this->execute("INSERT INTO `{$p}option_value` (option_value_id, option_id, image, sort_order) VALUES ({$id}, {$optId}, '', {$sort})");
            $this->execute("INSERT INTO `{$p}option_value_description` (option_value_id, language_id, option_id, name) VALUES ({$id}, 1, {$optId}, {$nameSafe})");
        }

        // ── CATEGORIES ─────────────────────────────────────────────────────
        // [id, parent, name, description, top, sort, seo]
        $categories = [
            [100,   0, 'Smartphones',
             'Discover the latest smartphones from Apple, Samsung, Google and more.',
             1, 1, 'smartphones'],
            [101,   0, 'Laptops &amp; Computers',
             'From ultrabooks to powerhouse workstations — find the perfect laptop.',
             1, 2, 'laptops-computers'],
            [102, 101, 'Windows Laptops',
             'High-performance Windows laptops for every need and budget.',
             0, 1, 'windows-laptops'],
            [103, 101, 'MacBooks',
             'Apple MacBook lineup — lightweight, powerful, and beautifully designed.',
             0, 2, 'macbooks'],
            [104,   0, 'Tablets',
             'Versatile tablets for work, creativity, and entertainment.',
             1, 3, 'tablets'],
            [105,   0, 'Audio &amp; Headphones',
             'Immersive sound experiences — from noise-cancelling headphones to earbuds.',
             1, 4, 'audio-headphones'],
            [106,   0, 'Wearables',
             'Smart watches and fitness trackers to keep you connected and healthy.',
             1, 5, 'wearables'],
            [107,   0, 'Gaming',
             'Consoles, accessories and everything you need for your gaming setup.',
             1, 6, 'gaming'],
            [108, 107, 'Consoles',
             'PlayStation, Xbox and more — the latest gaming consoles.',
             0, 1, 'gaming-consoles'],
            [109, 107, 'Gaming Accessories',
             'Mice, keyboards, headsets and more gaming peripherals.',
             0, 2, 'gaming-accessories'],
            [110,   0, 'Smart Home',
             'Smart speakers, displays and home automation devices.',
             1, 7, 'smart-home'],
        ];

        foreach ($categories as [$id, $parent, $name, $desc, $top, $sort, $seo]) {
            $nameSafe = $this->getAdapter()->getConnection()->quote($name);
            $descSafe = $this->getAdapter()->getConnection()->quote($desc);
            $this->execute("INSERT INTO `{$p}category` (category_id, parent_id, top, `column`, sort_order, status, external_link, date_added, date_modified)
                VALUES ({$id}, {$parent}, {$top}, 1, {$sort}, 1, '', NOW(), NOW())");
            $this->execute("INSERT INTO `{$p}category_description` (category_id, language_id, name, description, meta_title, meta_description, meta_keyword, language_status)
                VALUES ({$id}, 1, {$nameSafe}, {$descSafe}, {$nameSafe}, {$descSafe}, {$nameSafe}, 1)");
            $this->execute("INSERT INTO `{$p}category_to_store` (category_id, store_id) VALUES ({$id}, 0)");
            $this->execute("INSERT INTO `{$p}url_alias` (query, keyword, language_id) VALUES ('category_id={$id}', '{$seo}', 1)");
        }

        // Category paths (for breadcrumbs)
        $paths = [
            [100, [[100,0]]],
            [101, [[101,0]]],
            [102, [[101,0],[102,1]]],
            [103, [[101,0],[103,1]]],
            [104, [[104,0]]],
            [105, [[105,0]]],
            [106, [[106,0]]],
            [107, [[107,0]]],
            [108, [[107,0],[108,1]]],
            [109, [[107,0],[109,1]]],
            [110, [[110,0]]],
        ];
        foreach ($paths as [$catId, $entries]) {
            foreach ($entries as [$pathId, $level]) {
                $this->execute("INSERT INTO `{$p}category_path` (category_id, path_id, level) VALUES ({$catId}, {$pathId}, {$level})");
            }
        }

        // ── PRODUCTS ────────────────────────────────────────────────────────
        // Image URLs — placehold.co dark-tech style; replace with real product photos.
        // Each product: [id, model, name, subtitle, price, manufacturer_id, category_id, sort, image_url, description, tag, weight]
        $products = [
            [1,  'APPLE-IP16PRO',    'iPhone 16 Pro',
             'A18 Pro · 48 MP · 6.3"',
             999.00, 1, 100, 1,
             'https://placehold.co/800x600/1d1d1f/f5f5f7?text=iPhone+16+Pro',
             'The most powerful iPhone ever. Featuring the A18 Pro chip with a 6-core GPU, the all-new 48 MP Fusion camera, and a stunning 6.3-inch Super Retina XDR display with ProMotion. Built from aerospace-grade titanium.',
             'iphone,apple,smartphone,5g,pro,titanium'],
            [2,  'SAM-GS25U',        'Samsung Galaxy S25 Ultra',
             'Snapdragon 8 Elite · 200 MP · 6.9"',
             1299.00, 2, 100, 2,
             'https://placehold.co/800x600/1a1a2e/e2e8f0?text=Galaxy+S25+Ultra',
             'The ultimate Android flagship. The Galaxy S25 Ultra is powered by Snapdragon 8 Elite, featuring a 200 MP pro-grade camera, an integrated S Pen, and a massive 6.9-inch Dynamic AMOLED 2X display at 120 Hz.',
             'samsung,galaxy,smartphone,android,s-pen,ultra'],
            [3,  'GOOG-P9PRO',       'Google Pixel 9 Pro',
             'Tensor G4 · 50 MP · 6.3"',
             999.00, 3, 100, 3,
             'https://placehold.co/800x600/202124/e8eaed?text=Pixel+9+Pro',
             'Google\'s most advanced camera phone. Powered by the Tensor G4 chip with on-device AI, the Pixel 9 Pro delivers stunning photography with a 50 MP main camera, 48 MP ultrawide, and 48 MP telephoto with 5× optical zoom.',
             'google,pixel,android,ai,camera,smartphone'],
            [4,  'APPLE-MBP14M4',    'MacBook Pro 14" M4 Pro',
             'M4 Pro · 24 GB · Liquid Retina XDR',
             1999.00, 1, 103, 1,
             'https://placehold.co/800x600/1d1d1f/f5f5f7?text=MacBook+Pro+14',
             'Supercharged by M4 Pro, the MacBook Pro 14-inch delivers exceptional performance with up to 24 CPU cores and up to 32 GPU cores. The stunning Liquid Retina XDR display with ProMotion technology reaches 120 Hz for buttery-smooth scrolling.',
             'apple,macbook,pro,m4,laptop,macos'],
            [5,  'DELL-XPS15-9530',  'Dell XPS 15 (2025)',
             'Core Ultra 9 · 32 GB · OLED 3.5K',
             1699.00, 4, 102, 1,
             'https://placehold.co/800x600/0f172a/e2e8f0?text=Dell+XPS+15',
             'The pinnacle of Windows laptop design. The Dell XPS 15 features a gorgeous 3.5K OLED touchscreen, Intel Core Ultra 9 processor, NVIDIA GeForce RTX 4070 graphics, and an incredibly thin CNC-machined aluminum chassis.',
             'dell,xps,windows,laptop,oled,rtx'],
            [6,  'APPLE-IPADPRO13',  'iPad Pro 13" M4',
             'M4 · Ultra Retina XDR · 13"',
             1299.00, 1, 104, 1,
             'https://placehold.co/800x600/1d1d1f/f5f5f7?text=iPad+Pro+13',
             'The thinnest Apple product ever. iPad Pro 13" is powered by the M4 chip and features the most advanced display — Ultra Retina XDR with nano-texture glass option, ProMotion, and extreme brightness for true-to-life color accuracy.',
             'apple,ipad,pro,tablet,m4,creative'],
            [7,  'SAM-TABS10P',      'Samsung Galaxy Tab S10+',
             'Snapdragon 8 Gen 3 · 12 GB · 12.4"',
             799.00, 2, 104, 2,
             'https://placehold.co/800x600/1a1a2e/e2e8f0?text=Galaxy+Tab+S10%2B',
             'The premium Android tablet experience. Galaxy Tab S10+ boasts a 12.4-inch Dynamic AMOLED 2X display, Snapdragon 8 Gen 3, 12 GB RAM, and seamless integration with the S Pen. Perfect for productivity and creativity.',
             'samsung,galaxy,tab,android,tablet,s-pen'],
            [8,  'APPLE-APP2',       'AirPods Pro (2nd Gen)',
             'H2 chip · Active Noise Cancellation · USB-C',
             249.00, 1, 105, 1,
             'https://placehold.co/800x600/f5f5f7/1d1d1f?text=AirPods+Pro+2',
             'The world\'s most popular earbuds, reinvented. AirPods Pro feature the Apple H2 chip for up to 2× more noise cancellation, Adaptive Audio that blends Active Noise Cancellation and Transparency mode in real time, and a USB-C MagSafe case.',
             'apple,airpods,earbuds,noise-cancelling,wireless,audio'],
            [9,  'SONY-WH1000XM6',  'Sony WH-1000XM6',
             'Industry-leading ANC · 30h battery · Hi-Res Audio',
             349.00, 5, 105, 2,
             'https://placehold.co/800x600/0d0d0d/e0e0e0?text=Sony+WH-1000XM6',
             'Sony\'s flagship over-ear headphones set a new standard for noise cancellation. The WH-1000XM6 features an all-new acoustic structure, Auto NC Optimizer, Multipoint Bluetooth, and up to 30 hours of battery life with ANC enabled.',
             'sony,headphones,noise-cancelling,wireless,hifi,bluetooth'],
            [10, 'APPLE-WS10',       'Apple Watch Series 10',
             'Largest display ever · Sleep Apnea detection',
             399.00, 1, 106, 1,
             'https://placehold.co/800x600/1d1d1f/f5f5f7?text=Apple+Watch+S10',
             'The thinnest Apple Watch ever. Series 10 features the largest and brightest display yet, advanced health sensors including Sleep Apnea detection, water resistance to 50 metres, and the powerful S10 chip with watchOS 11.',
             'apple,watch,smartwatch,health,fitness,wearable'],
            [11, 'SAM-GW7',          'Samsung Galaxy Watch 7',
             'Advanced health · BioActive sensor · Wear OS',
             299.00, 2, 106, 2,
             'https://placehold.co/800x600/1a1a2e/e2e8f0?text=Galaxy+Watch+7',
             'Your ultimate health and wellness companion. Galaxy Watch 7 features the most powerful BioActive sensor for comprehensive body composition analysis, advanced sleep coaching, race course support, and seamless Galaxy AI integration.',
             'samsung,galaxy,watch,smartwatch,health,android,wearable'],
            [12, 'SONY-PS5SLIM',     'PlayStation 5 Slim',
             'PS5 · 4K · Up to 120fps',
             449.00, 5, 108, 1,
             'https://placehold.co/800x600/003791/ffffff?text=PlayStation+5+Slim',
             'The PS5 Slim is 30% smaller than the original PlayStation 5, featuring a detachable disc drive, 1 TB SSD, and the full power of the PS5 platform — 4K gaming at up to 120 fps, DualSense haptic feedback, and ray tracing.',
             'sony,playstation,ps5,console,gaming,4k'],
            [13, 'MS-XBXSX',         'Xbox Series X',
             '12 TFLOPS · 4K 120fps · Quick Resume',
             499.00, 6, 108, 2,
             'https://placehold.co/800x600/107C10/ffffff?text=Xbox+Series+X',
             'The most powerful Xbox ever. Xbox Series X delivers 12 teraflops of raw GPU performance, 4K gaming at up to 120 fps, 1 TB Custom NVMe SSD, and Quick Resume — jump back into multiple games exactly where you left off.',
             'microsoft,xbox,series-x,console,gaming,4k'],
            [14, 'LOGI-MXM3S',       'Logitech MX Master 3S',
             '8K DPI · MagSpeed scroll · Multi-device',
             99.00, 7, 109, 1,
             'https://placehold.co/800x600/0f172a/e2e8f0?text=MX+Master+3S',
             'The ultimate mouse for creators and power users. MX Master 3S features an ultra-fast MagSpeed electromagnetic scroll wheel, whisper-quiet clicks, 8000 DPI precision on any surface including glass, and connects to up to 3 devices via Bolt USB or Bluetooth.',
             'logitech,mouse,mx-master,productivity,wireless,ergonomic'],
            [15, 'APPLE-HPM',        'Apple HomePod mini',
             '360° audio · Smart home hub · Thread',
             99.00, 1, 110, 1,
             'https://placehold.co/800x600/f5f5f7/1d1d1f?text=HomePod+mini',
             'Surprisingly powerful sound in a compact design. HomePod mini delivers full 360° audio with computational audio, acts as a smart home hub for HomeKit and Matter accessories, supports Thread networking, and integrates seamlessly with all your Apple devices.',
             'apple,homepod,smart-speaker,smart-home,homekit,siri'],
        ];

        foreach ($products as [$id, $model, $name, $subtitle, $price, $mfr, $cat, $sort, $imgUrl, $desc, $tags]) {
            $nameSafe    = $this->getAdapter()->getConnection()->quote($name);
            $subtSafe    = $this->getAdapter()->getConnection()->quote($subtitle);
            $descSafe    = $this->getAdapter()->getConnection()->quote($desc);
            $tagsSafe    = $this->getAdapter()->getConnection()->quote($tags);
            $imgSafe     = $this->getAdapter()->getConnection()->quote($imgUrl);
            $modelSafe   = $this->getAdapter()->getConnection()->quote($model);
            $seoKeyword  = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $name));
            $metaDesc    = $this->getAdapter()->getConnection()->quote(substr(strip_tags($desc), 0, 160));

            $this->execute("INSERT INTO `{$p}product`
                (product_id, model, sku, upc, ean, jan, isbn, mpn, location, quantity,
                 stock_status_id, image, image_url, manufacturer_id, shipping, price,
                 points, tax_class_id, date_available, weight, weight_class_id,
                 length, width, height, length_class_id, subtract, minimum, sort_order,
                 status, viewed, date_added, date_modified, youtube, youtube_code, price_inshop)
            VALUES
                ({$id}, {$modelSafe}, {$modelSafe}, '', '', '', '', '', '', 100,
                 7, '', {$imgSafe}, {$mfr}, 1, {$price},
                 0, 9, CURDATE(), 0.20, 1,
                 0, 0, 0, 1, 1, 1, {$sort},
                 1, 0, NOW(), NOW(), '', '', {$price})");

            $this->execute("INSERT INTO `{$p}product_description`
                (product_id, language_id, name, description, admin_description, tag,
                 meta_title, meta_description, meta_keyword, subtitle, language_status, external_link)
            VALUES
                ({$id}, 1, {$nameSafe}, {$descSafe}, '', {$tagsSafe},
                 {$nameSafe}, {$metaDesc}, {$tagsSafe}, {$subtSafe}, 1, '')");

            $this->execute("INSERT INTO `{$p}product_to_category` (product_id, category_id) VALUES ({$id}, {$cat})");
            $this->execute("INSERT INTO `{$p}product_to_store` (product_id, store_id) VALUES ({$id}, 0)");
            $this->execute("INSERT INTO `{$p}url_alias` (query, keyword, language_id) VALUES ('product_id={$id}', '{$seoKeyword}', 1)");
        }

        // ── PRODUCT ATTRIBUTES ──────────────────────────────────────────────
        // [product_id, attribute_id, text]
        $productAttrs = [
            // iPhone 16 Pro
            [1, 1,  '6.3 inches'],
            [1, 2,  '2622 × 1206 pixels, 460 ppi'],
            [1, 3,  'Super Retina XDR, ProMotion, 120 Hz'],
            [1, 4,  'Apple A18 Pro (3 nm)'],
            [1, 5,  '8 GB'],
            [1, 6,  '256 GB / 512 GB / 1 TB'],
            [1, 7,  '48 MP main + 48 MP ultrawide + 12 MP 5× telephoto'],
            [1, 8,  '12 MP TrueDepth'],
            [1, 9,  '3582 mAh, USB-C fast charge, MagSafe'],
            [1, 10, 'iOS 18'],
            [1, 11, '227 g'],
            [1, 12, '5G, Wi-Fi 7, Bluetooth 5.3, NFC, UWB'],
            // Samsung Galaxy S25 Ultra
            [2, 1,  '6.9 inches'],
            [2, 2,  '3088 × 1440 pixels, 501 ppi'],
            [2, 3,  'Dynamic AMOLED 2X, 120 Hz, 2600 nits'],
            [2, 4,  'Snapdragon 8 Elite (3 nm)'],
            [2, 5,  '12 GB'],
            [2, 6,  '256 GB / 512 GB / 1 TB'],
            [2, 7,  '200 MP main + 50 MP ultrawide + 10 MP 3× + 50 MP 5× telephoto'],
            [2, 8,  '12 MP'],
            [2, 9,  '5000 mAh, 45W fast charge'],
            [2, 10, 'Android 15 / One UI 7'],
            [2, 11, '218 g'],
            [2, 12, '5G, Wi-Fi 7, Bluetooth 5.4, NFC, UWB'],
            // Google Pixel 9 Pro
            [3, 1,  '6.3 inches'],
            [3, 2,  '2856 × 1280 pixels, 495 ppi'],
            [3, 3,  'LTPO OLED, 1-120 Hz, 3000 nits'],
            [3, 4,  'Google Tensor G4'],
            [3, 5,  '16 GB'],
            [3, 6,  '128 GB / 256 GB / 512 GB'],
            [3, 7,  '50 MP main + 48 MP ultrawide + 48 MP 5× telephoto'],
            [3, 8,  '10.5 MP'],
            [3, 9,  '4700 mAh, 27W fast charge'],
            [3, 10, 'Android 15'],
            [3, 11, '199 g'],
            [3, 12, '5G, Wi-Fi 7, Bluetooth 5.3, NFC, UWB'],
            // MacBook Pro 14
            [4, 1,  '14.2 inches'],
            [4, 2,  '3024 × 1964 pixels, 254 ppi'],
            [4, 3,  'Liquid Retina XDR, ProMotion, 120 Hz, 1600 nits'],
            [4, 4,  'Apple M4 Pro (up to 14-core CPU, 20-core GPU)'],
            [4, 5,  '24 GB or 48 GB unified memory'],
            [4, 6,  '512 GB, 1 TB, or 2 TB SSD'],
            [4, 9,  '72.4 Wh, up to 22 hours'],
            [4, 10, 'macOS Sequoia'],
            [4, 11, '1.62 kg'],
            [4, 12, 'Wi-Fi 6E, Bluetooth 5.3, Thunderbolt 4, HDMI, SD card'],
            // Dell XPS 15
            [5, 1,  '15.6 inches'],
            [5, 2,  '3456 × 2160 OLED, 265 ppi'],
            [5, 3,  'OLED InfinityEdge touch, 120 Hz'],
            [5, 4,  'Intel Core Ultra 9 185H'],
            [5, 5,  '16 GB / 32 GB LPDDR5x'],
            [5, 6,  '512 GB / 1 TB / 2 TB NVMe SSD'],
            [5, 7,  'NVIDIA GeForce RTX 4070'],
            [5, 9,  '86 Wh, up to 13 hours'],
            [5, 10, 'Windows 11 Pro'],
            [5, 11, '1.86 kg'],
            [5, 12, 'Wi-Fi 6E, Bluetooth 5.3, Thunderbolt 4, USB-A'],
            // iPad Pro 13
            [6, 1,  '13 inches'],
            [6, 2,  '2752 × 2064 pixels, 264 ppi'],
            [6, 3,  'Ultra Retina XDR OLED, ProMotion 120 Hz'],
            [6, 4,  'Apple M4 (10-core CPU, 10-core GPU)'],
            [6, 5,  '8 GB / 16 GB unified memory'],
            [6, 6,  '256 GB to 2 TB'],
            [6, 9,  '38.99 Wh, up to 10 hours'],
            [6, 10, 'iPadOS 18'],
            [6, 11, '582 g (Wi-Fi)'],
            [6, 12, 'Wi-Fi 6E, Bluetooth 5.3, USB4, Apple Pencil Pro'],
            // Samsung Galaxy Tab S10+
            [7, 1,  '12.4 inches'],
            [7, 2,  '2800 × 1752 pixels, 266 ppi'],
            [7, 3,  'Dynamic AMOLED 2X, 120 Hz, 930 nits'],
            [7, 4,  'Snapdragon 8 Gen 3'],
            [7, 5,  '12 GB'],
            [7, 6,  '256 GB / 512 GB'],
            [7, 9,  '10090 mAh, 45W fast charge'],
            [7, 10, 'Android 14 / One UI 6.1'],
            [7, 11, '571 g'],
            [7, 12, 'Wi-Fi 7, Bluetooth 5.3, USB-C 3.2, S Pen included'],
            // AirPods Pro 2
            [8, 4,  'Apple H2'],
            [8, 9,  '6h ANC / 30h with case, USB-C'],
            [8, 10, 'iOS / iPadOS / macOS / watchOS'],
            [8, 11, '5.3 g per earbud'],
            [8, 12, 'Bluetooth 5.3, USB-C, MagSafe, IP54'],
            // Sony WH-1000XM6
            [9, 4,  'Sony V2 chip + QN2 HD processor'],
            [9, 9,  '30 hours (ANC on), USB-C 3-min quick charge'],
            [9, 11, '254 g'],
            [9, 12, 'Bluetooth 5.3, Multipoint, LDAC, USB-C, 3.5mm'],
            // Apple Watch Series 10
            [10, 1,  '42mm or 46mm OLED'],
            [10, 4,  'Apple S10'],
            [10, 9,  '18 hours, 80% charge in 30 min'],
            [10, 10, 'watchOS 11'],
            [10, 11, '36.4 g (42mm aluminum)'],
            [10, 12, 'Wi-Fi 6, Bluetooth 5.3, NFC, WR50, GPS'],
            // Samsung Galaxy Watch 7
            [11, 1,  '40mm or 44mm AMOLED'],
            [11, 4,  'Exynos W1000'],
            [11, 9,  '40 hours (40mm)'],
            [11, 10, 'Wear OS 5 / One UI Watch 6'],
            [11, 11, '28.8 g (40mm)'],
            [11, 12, 'Wi-Fi, Bluetooth 5.3, NFC, GPS, BioActive sensor'],
            // PlayStation 5 Slim
            [12, 4,  'AMD Zen 2 + RDNA 2, 10.28 TFLOPS'],
            [12, 5,  '16 GB GDDR6'],
            [12, 6,  '1 TB NVMe SSD (expandable)'],
            [12, 9,  'N/A'],
            [12, 10, 'PlayStation OS'],
            [12, 11, '3.2 kg'],
            [12, 12, 'HDMI 2.1, USB-A, USB-C, Wi-Fi 6, Bluetooth 5.1'],
            // Xbox Series X
            [13, 4,  'AMD Zen 2 + RDNA 2, 12 TFLOPS'],
            [13, 5,  '16 GB GDDR6'],
            [13, 6,  '1 TB NVMe SSD (expandable)'],
            [13, 10, 'Xbox OS'],
            [13, 11, '4.45 kg'],
            [13, 12, 'HDMI 2.1, USB-A, USB-C, Wi-Fi 6E, Bluetooth 5.0'],
            // Logitech MX Master 3S
            [14, 4,  '32-bit ARM'],
            [14, 9,  '70 days on full charge, USB-C'],
            [14, 11, '141 g'],
            [14, 12, 'Bluetooth 5, Logi Bolt USB receiver'],
            // Apple HomePod mini
            [15, 4,  'Apple S5'],
            [15, 9,  'N/A (plug-in)'],
            [15, 10, 'audioOS / HomePod Software'],
            [15, 11, '345 g'],
            [15, 12, 'Wi-Fi 6, Bluetooth 5.0, Thread, Ultra Wideband'],
        ];

        foreach ($productAttrs as [$pid, $aid, $text]) {
            $textSafe = $this->getAdapter()->getConnection()->quote($text);
            $this->execute("INSERT IGNORE INTO `{$p}product_attribute` (product_id, attribute_id, language_id, text)
                VALUES ({$pid}, {$aid}, 1, {$textSafe})");
        }

        // ── PRODUCT OPTIONS ─────────────────────────────────────────────────
        // product_option_id is auto-increment; we use a counter for reference.
        // Format: [product_id, option_id, required, label, [[option_value_id, price, price_prefix, qty], ...]]
        $productOptions = [
            // iPhone 16 Pro — Storage + Color
            [1, 5, 1, '', [
                [11, 0.00,   '+', 50],  // 256 GB base
                [12, 100.00, '+', 30],  // 512 GB +$100
                [13, 200.00, '+', 20],  // 1 TB   +$200
            ]],
            [1, 1, 1, '', [
                [50, 0.00, '+', 99],  // Black
                [51, 0.00, '+', 99],  // Silver
                [52, 0.00, '+', 99],  // Gold
                [54, 0.00, '+', 99],  // White
            ]],
            // Samsung Galaxy S25 Ultra — Storage + Color
            [2, 5, 1, '', [
                [11, 0.00,   '+', 40],
                [12, 120.00, '+', 25],
                [13, 240.00, '+', 15],
            ]],
            [2, 1, 1, '', [
                [50, 0.00, '+', 99],
                [51, 0.00, '+', 99],
                [53, 0.00, '+', 99],
                [55, 0.00, '+', 99],
            ]],
            // Google Pixel 9 Pro — Storage + Color
            [3, 5, 1, '', [
                [10, 0.00,   '+', 40],  // 128 GB
                [11, 100.00, '+', 30],  // 256 GB
                [12, 200.00, '+', 20],  // 512 GB
            ]],
            [3, 1, 1, '', [
                [50, 0.00, '+', 99],
                [51, 0.00, '+', 99],
                [53, 0.00, '+', 99],
                [54, 0.00, '+', 99],
            ]],
            // MacBook Pro 14 — Configuration
            [4, 5, 1, '', [
                [20, 0.00,    '+', 20],  // M4 Pro 12c/24GB/512GB
                [21, 200.00,  '+', 15],  // M4 Pro 14c/24GB/1TB
                [22, 600.00,  '+', 10],  // M4 Max 16c/48GB/1TB
            ]],
            [4, 1, 1, '', [
                [54, 0.00, '+', 30],  // White (Silver)
                [55, 0.00, '+', 30],  // Graphite / Space Black
            ]],
            // Dell XPS 15 — Configuration + Color
            [5, 5, 1, '', [
                [25, 0.00,   '+', 15],
                [26, 300.00, '+', 10],
            ]],
            [5, 1, 1, '', [
                [50, 0.00, '+', 20],  // Black
                [51, 0.00, '+', 20],  // Silver
            ]],
            // iPad Pro 13 — Storage + Connectivity
            [6, 5, 1, '', [
                [11, 0.00,   '+', 20],  // 256 GB
                [12, 200.00, '+', 15],  // 512 GB
                [13, 400.00, '+', 10],  // 1 TB
            ]],
            [6, 5, 1, '', [
                [30, 0.00,   '+', 25],  // Wi-Fi
                [31, 200.00, '+', 15],  // Wi-Fi + Cellular
            ]],
            // Samsung Galaxy Tab S10+ — Storage + Color
            [7, 5, 1, '', [
                [11, 0.00,   '+', 20],
                [12, 100.00, '+', 10],
            ]],
            [7, 1, 1, '', [
                [50, 0.00, '+', 20],
                [54, 0.00, '+', 20],
            ]],
            // Sony WH-1000XM6 — Color
            [9, 1, 1, '', [
                [50, 0.00, '+', 30],  // Black
                [56, 0.00, '+', 20],  // Platinum Silver
            ]],
            // Apple Watch Series 10 — Size + Color
            [10, 1, 1, '', [
                [60, 0.00,  '+', 25],  // 42mm
                [61, 30.00, '+', 20],  // 46mm +$30
            ]],
            [10, 1, 1, '', [
                [50, 0.00, '+', 30],  // Black
                [51, 0.00, '+', 30],  // Silver
                [52, 0.00, '+', 20],  // Gold
                [54, 0.00, '+', 20],  // White
            ]],
            // Samsung Galaxy Watch 7 — Size + Color
            [11, 1, 1, '', [
                [60, 0.00,  '+', 20],
                [61, 30.00, '+', 15],
            ]],
            [11, 1, 1, '', [
                [50, 0.00, '+', 20],
                [51, 0.00, '+', 20],
                [53, 0.00, '+', 15],
            ]],
            // PlayStation 5 Slim — Edition
            [12, 5, 1, '', [
                [35, 0.00,   '+', 20],  // Disc Edition
                [36, -50.00, '+', 15],  // Digital Edition -$50
            ]],
            // Logitech MX Master 3S — Color
            [14, 1, 1, '', [
                [50, 0.00, '+', 30],  // Black
                [55, 0.00, '+', 25],  // Graphite
                [54, 0.00, '+', 20],  // Pale Grey (White)
            ]],
            // Apple HomePod mini — Color
            [15, 1, 1, '', [
                [54, 0.00, '+', 20],  // White
                [50, 0.00, '+', 20],  // Midnight (Black)
                [52, 0.00, '+', 15],  // Yellow (Gold)
            ]],
        ];

        foreach ($productOptions as [$pid, $optId, $required, $label, $values]) {
            $labelSafe = $this->getAdapter()->getConnection()->quote($label);
            $this->execute("INSERT INTO `{$p}product_option`
                (product_id, option_id, value, required)
                VALUES ({$pid}, {$optId}, '', {$required})");

            $poId = $this->getAdapter()->getConnection()->lastInsertId();

            foreach ($values as [$ovId, $price, $prefix, $qty]) {
                $this->execute("INSERT INTO `{$p}product_option_value`
                    (product_option_id, product_id, option_id, option_value_id,
                     quantity, subtract, price, price_prefix, points, points_prefix, weight, weight_prefix)
                    VALUES ({$poId}, {$pid}, {$optId}, {$ovId},
                            {$qty}, 1, {$price}, '{$prefix}', 0, '+', 0.00, '+')");
            }
        }

        $this->execute("SET FOREIGN_KEY_CHECKS=1");
    }

    public function down()
    {
        $p = defined('DB_PREFIX') ? DB_PREFIX : 'cp_';

        $this->execute("SET FOREIGN_KEY_CHECKS=0");

        foreach ([
            'product', 'product_description', 'product_to_category', 'product_to_store',
            'product_image', 'product_option', 'product_option_value', 'product_attribute',
            'product_discount', 'product_special', 'product_reward', 'product_related',
            'product_filter', 'product_to_download', 'product_to_layout',
        ] as $t) {
            $this->execute("DELETE FROM `{$p}{$t}`");
        }

        foreach ([
            'category', 'category_description', 'category_path',
            'category_to_store', 'category_to_layout',
        ] as $t) {
            $this->execute("DELETE FROM `{$p}{$t}`");
        }

        $this->execute("DELETE FROM `{$p}url_alias` WHERE query LIKE 'product_id=%' OR query LIKE 'category_id=%'");
        $this->execute("DELETE FROM `{$p}manufacturer`");

        $this->execute("SET FOREIGN_KEY_CHECKS=1");
    }
}
