<?php
/* * * Theme specific - override Settings !  * * */
!defined('DIR_SYSTEM') ? die() : false;

// Example:
$template_config_settings = [
    // 'theme_default_image_category_width'       => 80,
    // 'theme_default_image_category_height'      => 80,
    'theme_default_category_show_subcategories_products' => true,
];

if (APPLICATION == 'catalog') {
    $this->document->addStyle('//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700');
    $this->document->addStyle('themes/default/assets/css/stylesheet.css');
    $this->document->addStyle('themes/default/assets/css/additional.css');

    // we are in catalog.

    $this->document->addStyle('themes/default/assets/vendor/bootstrap/css/bootstrap.min.css');
    $this->document->addStyle('themes/default/assets/vendor/font-awesome/css/font-awesome.min.css');
    $this->document->addStyle('themes/default/assets/css/additional.css');
    $this->document->addStyle('themes/default/assets/css/owl.carousel.css');
    $this->document->addStyle('themes/default/assets/css/additional.css');
    $this->document->addScript('themes/default/assets/js/common.js');

}

foreach ($template_config_settings as $key => $val) {
    $this->config->set($key, $val);
}

