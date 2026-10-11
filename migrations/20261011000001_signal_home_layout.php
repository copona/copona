<?php

use Phinx\Migration\AbstractMigration;

/**
 * Home page for the "Signal" storefront theme.
 *
 * The seeded "Home Page" featured module still pointed at the old OpenCart
 * demo products (ids 30-43), which no longer exist, so the home page showed
 * an empty "Featured" heading. Point it at the current demo products and show
 * eight of them.
 *
 * The featured and carousel modules move below the slideshow and the new
 * category tiles (content_bottom), so the home page reads hero, categories,
 * products.
 */
class SignalHomeLayout extends AbstractMigration
{
    private const DEMO_PRODUCTS = ['1', '3', '4', '8', '6', '12', '10', '14'];

    public function up()
    {
        $prefix = DB_PREFIX;

        $featured = $this->fetchRow("SELECT `setting` FROM `{$prefix}module` WHERE `module_id` = 28 AND `code` = 'featured'");

        if ($featured) {
            $setting = json_decode($featured['setting'], true) ?: [];
            $ids     = implode(',', array_map('intval', $setting['product'] ?? []));
            $found   = $ids ? $this->fetchRow("SELECT COUNT(*) AS `total` FROM `{$prefix}product` WHERE `product_id` IN ({$ids})") : ['total' => 0];

            // Only replace a product list that points at nothing, never a store's own choice.
            if (!(int)$found['total']) {
                $setting['product'] = self::DEMO_PRODUCTS;
                $setting['limit']   = '8';
                $setting['width']   = '400';
                $setting['height']  = '300';

                $this->execute(sprintf(
                    "UPDATE `{$prefix}module` SET `setting` = %s WHERE `module_id` = 28",
                    $this->getAdapter()->getConnection()->quote(json_encode($setting))
                ));
            }
        }

        $this->execute("UPDATE `{$prefix}layout_module` SET `position` = 'content_bottom', `sort_order` = 1 WHERE `layout_id` = 1 AND `code` = 'featured.28' AND `position` = 'content_top'");
        $this->execute("UPDATE `{$prefix}layout_module` SET `position` = 'content_bottom', `sort_order` = 2 WHERE `layout_id` = 1 AND `code` = 'carousel.29' AND `position` = 'content_top'");
    }

    public function down()
    {
        $prefix = DB_PREFIX;

        $this->execute("UPDATE `{$prefix}layout_module` SET `position` = 'content_top', `sort_order` = 2 WHERE `layout_id` = 1 AND `code` = 'featured.28'");
        $this->execute("UPDATE `{$prefix}layout_module` SET `position` = 'content_top', `sort_order` = 3 WHERE `layout_id` = 1 AND `code` = 'carousel.29'");
    }
}
