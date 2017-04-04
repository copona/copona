<?php
/*
 * This file will be included at catalog/controller/startup.php as first priority
 * Right before inclusion of Theme specific functions.php
 *
 */
/* * * Theme specific - override Settings !  * * */
!defined('DIR_SYSTEM') ? die() : false;
// Example:
$template_config_settings = array(
    'theme_default_product_category_list_resize' => 'resize',
    // 'theme_default_image_category_width'       => 80,
    // 'theme_default_image_category_height'      => 80,
);

foreach ($template_config_settings as $key => $val) {
    $this->config->set($key, $val);
}