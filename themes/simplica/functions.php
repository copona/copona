<?php
/* * * Theme specific - override Settings !  * * */
!defined('DIR_SYSTEM') ? die() : false;

// Example:
$template_config_settings = array(
    // 'theme_default_image_category_width'       => 80,
    // 'theme_default_image_category_height'      => 80,
);

if (APPLICATION == 'catalog') {
    // we are in catalog.
    $this->document->addStyle('themes/' . $this->config->get('theme_name') . '/assets/vendor/bootstrap/css/bootstrap.min.css');
    $this->document->addStyle('themes/' . $this->config->get('theme_name') . '/assets/vendor/font-awesome/css/font-awesome.min.css');
    $this->document->addStyle('//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700');
    $this->document->addStyle('themes/' . $this->config->get('theme_name') . '/assets/css/stylesheet.css');
    $this->document->addStyle('themes/default/assets/css/additional.css');
    $this->document->addStyle('themes/' . $this->config->get('theme_name') . '/assets/css/additional.css');
    $this->document->addStyle('themes/' . $this->config->get('theme_name') . '/assets/css/owl.carousel.css');
    $this->document->addStyle('themes/' . $this->config->get('theme_name') . '/assets/css/additional.css');
    $this->document->addScript('themes/' . $this->config->get('theme_name') . '/assets/js/common.js');

}

foreach ($template_config_settings as $key => $val) {
    $this->config->set($key, $val);
}

