<?php
/* * * Theme specific - override Settings !  * * */
!defined('DIR_SYSTEM') ? die() : false;
// Example:
$template_config_settings = array(
    // 'theme_default_image_category_width'       => 80,
    // 'theme_default_image_category_height'      => 80,
);


if(!defined('HTTP_CATALOG')) {
    // we are in catalog.
//
    $this->document->addStyleVersioned('catalog/view/javascript/bootstrap/css/bootstrap.min.css');
    $this->document->addStyleVersioned('catalog/view/javascript/font-awesome/css/font-awesome.min.css');
    $this->document->addStyleVersioned('//fonts.googleapis.com/css?family=Open+Sans:400,400i,300,700');
    $this->document->addStyleVersioned('catalog/view/theme/default/stylesheet/additional.css');
    $this->document->addStyleVersioned('catalog/view/theme/' . $this->config->get('theme_name') . '/stylesheet/stylesheet.css');
    $this->document->addStyleVersioned('catalog/view/theme/' . $this->config->get('theme_name') . '/stylesheet/owl.carousel.css');
    $this->document->addStyleVersioned('catalog/view/theme/' . $this->config->get('theme_name') . '/stylesheet/additional.css');

    $this->document->addScriptVersioned('catalog/view/javascript/jquery/jquery-2.1.1.min.js');
    $this->document->addScriptVersioned('catalog/view/javascript/bootstrap/js/bootstrap.min.js');
    $this->document->addScriptVersioned('catalog/view/javascript/common.js');
//prd();
}





foreach ($template_config_settings as $key => $val) {
    $this->config->set($key, $val);
}


