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
    'theme_default_product_category_list_resize'  => 'resize',
    'theme_default_product_info_thumb_resize'     => 'resize',
    'theme_default_product_info_image_mid_resize' => 'resize',
    // 'theme_default_image_category_width'       => 80,
    // 'theme_default_image_category_height'      => 80,
);

foreach ($template_config_settings as $key => $val) {
    $this->config->set($key, $val);
}




/* Introducing HOOKS */
/* * ********** Example for product/index/after ************* */

/*
 * To set hook.
 * string = hook name
 * string = callback functions
 */

$this->hook->setHook('product/index/after', 'remove_image');

/*
 * callback functions
 * reference = to array
 *
 */

function remove_image(&$data, &$registry) {
    $db = $registry->get('db');
    $registry->get('load')->model('catalog/product');
    // Real modifications.
    //
    // $product = $registry->get('model_catalog_product')->getProduct(51);
    // prd($product);
    // prd($db->query('select * from oc_product limit 10'));
    /*
      $data['image_mid'] = '';
      $data['popup'] = '';
      $data['thumb'] = '';
     */
}
