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
    $this->document->addStyleVersioned('themes/' . $this->config->get('theme_name') . '/assets/vendor/bootstrap/css/bootstrap.min.css');
    $this->document->addStyleVersioned('themes/' . $this->config->get('theme_name') . '/assets/vendor/font-awesome/css/font-awesome.min.css');
    $this->document->addStyleVersioned('//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700');
    $this->document->addStyleVersioned('themes/' . $this->config->get('theme_name') . '/assets/css/stylesheet.css');
    $this->document->addStyleVersioned('themes/' . $this->config->get('theme_name') . '/assets/css/additional.css');
    $this->document->addStyleVersioned('themes/' . $this->config->get('theme_name') . '/assets/css/owl.carousel.css');
    $this->document->addStyleVersioned('themes/' . $this->config->get('theme_name') . '/assets/css/additional.css');
    $this->document->addScriptVersioned('themes/' . $this->config->get('theme_name') . '/assets/vendor/jquery/jquery-2.1.1.min.js');
    $this->document->addScriptVersioned('themes/' . $this->config->get('theme_name') . '/assets/vendor/bootstrap/js/bootstrap.min.js');
    $this->document->addScriptVersioned('themes/' . $this->config->get('theme_name') . '/assets/js/common.js');
}

foreach ($template_config_settings as $key => $val) {
    $this->config->set($key, $val);
}

