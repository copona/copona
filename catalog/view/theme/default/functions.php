<?php
/* * * Theme specific - override Settings !  * * */
!defined('DIR_SYSTEM') ? die() : false;
// Example:
$rozesbode_settings = array(
    // 'theme_default_image_category_width'       => 80,
    // 'theme_default_image_category_height'      => 80,
);

foreach ($rozesbode_settings as $key => $val) {
    $this->config->set($key, $val);
}


