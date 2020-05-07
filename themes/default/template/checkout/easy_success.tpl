<?php echo $header; ?>


<?php


?>

<div class="container">
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li class="breadcrumb-item">
                <a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
            </li>
        <?php } ?>
    </ul>
    <div class="row"><?php echo $column_left; ?>
        <?php if ($column_left && $column_right) { ?>
            <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
            <?php $class = 'col-sm-9'; ?>
        <?php } else { ?>
            <?php $class = 'col-sm-12'; ?>
        <?php } ?>
        <div id="content" class="<?php echo $class; ?> checkout-success">


            <?php if ($after_purchase) { ?>


                <div class="row">
                    <div class="col-sm-9">
                        <div class="row block">

                            <div class="col-sm-12">
                                <h1><?= $heading_title ?></h1>

                                <h2><?php echo $text_order_number ?>: #<?=$order_id?></h2>

                                <ul class="list-unstyled">
                                    <?php foreach ($products as $product) { ?>
                                        <li>

                                            <img src="<?= $product['thumb'] ?>" style="max-width: 70px;">
                                            <?php echo $product['name'] ?> <strong> &times; </strong> <?php echo $product['quantity'] ?>
                                        </li>
                                    <?php } ?>
                                </ul>

                                <?php

                                /* if($payment_instruction) { ?>
                                   <h2>Payment Instruction</h2>
                                   <p><?php echo $payment_instruction; ?>  </p>
                                 <?php } */ ?>
                                <h5><?= $text_thanks ?></h5>
                                <?php /*

					 <p class="idText">
                        <?= $text_order_id ?> #<?= $order_id ?>
                    </p>



					<p>
                        <?= $text_shipping_address ?>:
                      <span><?= $shipping_type_address ?></span>
                      <br>

                        <?= $text_shipping_method ?>:
                      <span><?= $shipping_type_text ?></span>


                    </p>


                    <p>
                        <?= $text_if_question ?>
                      <a href="mailto:<?= $config_azon_store_info_email ?>"><?= $config_azon_store_info_email ?></a>
                        <?= $config_telephone ?>

                    </p>

                    <p> <?php echo $text_order_success_additional_info; ?>
                      <a href="<?= $ordercheck_link ?>"><?= $text_wheres_my_shipment ?></a>
                    </p>



					*/ ?>


                            </div>
                        </div>

                    </div>


                </div>
            <?php } else { ?>
                <div class="row">
                    <div class="col-sm-11">
                        <h1><?= $button_shopping ?></h1>
                    </div>
                </div>
            <?php } ?>


        </div>
        <?php echo $content_bottom; ?>
    </div>
    <?php echo $column_right; ?></div>
</div>


<?php if ($after_purchase || !empty($debug)) { ?>
    <!-- Global site tag (gtag.js) - Google AdWords: 812961930 -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=AW-812961930"></script>

    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());
        gtag('config', 'AW-812961930');

        gtag('event', 'page_view', {
            'send_to': 'AW-812961930',
            'dynx_pagetype': <?=json_encode('conversion'); ?>, // conversion - Thank you page,
            // 'user_id': <?php // json_encode( $remarketing_user_id ); ?>

        });


        dataLayer.push({
            event: 'azon.purchase',
            ecommerce: {
                purchase: {
                    actionField: {
                        'id': '<?=$order_id?>', // Transaction ID. Required.
                        'affiliation': <?=json_encode($store_name, JSON_UNESCAPED_UNICODE) ?>, // Affiliation or store name.
                        'revenue': '<?=$order_total?>', // Grand Total.
                        'transactionCurrency': <?=json_encode($currency_code) ?>,
                        'currency': <?=json_encode($currency_code) ?>,
                        'shipping': '<?=$shipping_total?>', // Shipping.
                        // 'tax': '1.29' // Tax.

                    },
                    products: [ <?php foreach($products as $product) { ?>
                        {
                            'id': '<?=$product['product_id']?>', // Transaction ID. Required.
                            'name': <?=json_encode($product['name'], JSON_UNESCAPED_UNICODE)?>, // Product name. Required.
                            'sku': <?=json_encode($product['model'])?>, // SKU/code.
                            'category': <?=json_encode($product['category'], JSON_UNESCAPED_UNICODE)?>, // Category or variation.
                            'price': '<?=(float)$product['price_numeric']?>', // Unit price.
                            'quantity': '<?=(float)$product['quantity']?>' // Quantity.
                        },
                        <?php } ?>
                    ]
                }
            }
        });


    </script>
<?php } ?>
<?php echo $footer; ?>
<?php // echo $content_top; ?>
<?php // echo $heading_title; ?>
<?php // echo $text_message; ?>
<?php /* Truck <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 width="612px" height="612px" viewBox="0 0 612 612" style="enable-background:new 0 0 612 612;" xml:space="preserve">
<g>
	<path d="M21.474,377.522V117.138c0-14.469,11.729-26.199,26.199-26.199h260.25c14.469,0,26.198,11.73,26.198,26.199v260.385
		c0,4.823-3.909,8.733-8.733,8.733H30.207C25.383,386.256,21.474,382.346,21.474,377.522z M231.634,466.724
		c0,30.01-24.329,54.338-54.338,54.338c-30.009,0-54.338-24.328-54.338-54.338c0-30.011,24.329-54.338,54.338-54.338
		C207.305,412.386,231.634,436.713,231.634,466.724z M204.464,466.724c0-15.005-12.164-27.169-27.169-27.169
		s-27.17,12.164-27.17,27.169s12.165,27.17,27.17,27.17S204.464,481.729,204.464,466.724z M130.495,412.385H8.733
		c-4.823,0-8.733,3.91-8.733,8.733v26.495c0,4.823,3.91,8.733,8.733,8.733h97.598C108.879,438.862,117.704,423.418,130.495,412.385z
		 M515.938,466.724c0,30.01-24.329,54.338-54.338,54.338c-30.01,0-54.338-24.328-54.338-54.338
		c0-30.011,24.328-54.338,54.338-54.338C491.609,412.385,515.938,436.713,515.938,466.724z M488.77,466.724
		c0-15.005-12.165-27.169-27.17-27.169c-15.006,0-27.169,12.164-27.169,27.169s12.164,27.17,27.169,27.17
		S488.77,481.729,488.77,466.724z M612,421.118v26.495c0,4.823-3.91,8.733-8.733,8.733h-70.704
		c-5.057-34.683-34.906-61.427-70.961-61.427c-36.062,0-65.912,26.745-70.969,61.427H248.261
		c-2.549-17.483-11.373-32.928-24.164-43.961h134.994V162.594c0-9.646,7.82-17.466,17.466-17.466h82.445
		c23.214,0,44.911,11.531,57.9,30.77l53.15,78.721c7.796,11.547,11.962,25.161,11.962,39.094v118.672h21.253
		C608.09,412.385,612,416.295,612,421.118z M523.408,256.635l-42.501-60.393c-1.636-2.324-4.3-3.707-7.142-3.707H407.47
		c-4.822,0-8.733,3.91-8.733,8.733v60.393c0,4.824,3.91,8.733,8.733,8.733h108.798C523.342,270.394,527.48,262.421,523.408,256.635z
		"/>
</svg>
*/ ?>
<?php /* Green tick <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 426.667 426.667" style="enable-background:new 0 0 426.667 426.667;" xml:space="preserve">
<path style="fill:#6AC259;" d="M213.333,0C95.518,0,0,95.514,0,213.333s95.518,213.333,213.333,213.333
	c117.828,0,213.333-95.514,213.333-213.333S331.157,0,213.333,0z M174.199,322.918l-93.935-93.931l31.309-31.309l62.626,62.622
	l140.894-140.898l31.309,31.309L174.199,322.918z"/>
</svg>
*/ ?>
<?php /* Location marker <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
	 viewBox="0 0 52 52" style="enable-background:new 0 0 52 52;" xml:space="preserve">
<path style="fill:#1081E0;" d="M38.853,5.324L38.853,5.324c-7.098-7.098-18.607-7.098-25.706,0h0
	C6.751,11.72,6.031,23.763,11.459,31L26,52l14.541-21C45.969,23.763,45.249,11.72,38.853,5.324z M26.177,24c-3.314,0-6-2.686-6-6
	s2.686-6,6-6s6,2.686,6,6S29.491,24,26.177,24z"/>

</svg>
*/ ?>
