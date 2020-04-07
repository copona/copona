<?php
if($products) {
?>
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
  <div class="table-responsive">
    <table class="table table-bordered table-sm">
      <thead>
      <tr>
        <td class="text-center"><?php echo $column_image; ?></td>
        <td class="text-left"><?php echo $column_name; ?></td>
        <td class="text-left d-none"><?php echo $column_model; ?></td>
        <td class="text-left"><?php echo $column_quantity; ?></td>
        <td class="text-right"><?php echo $column_price; ?></td>
        <td class="text-right"><?php echo $column_total; ?></td>
      </tr>
      </thead>
      <tbody>
      <?php foreach ($products as $product) { ?>
        <tr>
          <td class="text-center"><?php if ($product['thumb']) { ?>
              <a href="<?php echo $product['href']; ?>">
                <img src="<?php echo $product['thumb']; ?>"
                     alt="<?php echo $product['name']; ?>"
                     title="<?php echo $product['name']; ?>"
                     class="img-thumbnail"
                     style="width: 95px; height:95px;"
                />
              </a>
              <?php } ?></td>
          <td class="text-left align-middle"><a
              href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
              <?php if (!$product['stock']) { ?>
                 <h6 style="color: red;">*** <?php echo __c('text_warning_not_enough_in_stock') ?> </h6>
              <?php } ?>
              <?php if ($product['option']) { ?>
                  <?php foreach ($product['option'] as $option) { ?>
                  <br/>
                  <small><?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                  <?php } ?>
              <?php } ?>
              <?php if ($product['attribute_groups']) { ?>
                  <?php foreach ($product['attribute_groups'] as $attribute) { ?>
                      <?php foreach ($attribute['attribute'] as $item) { ?>
                    <br><small><?php echo $item['name']; ?>: <?php echo $item['text']; ?></small>
                      <?php } ?>
                  <?php } ?>
              <?php } ?>
              <?php if ($product['reward']) { ?>
                <br/>
                <small><?php echo $product['reward']; ?></small>
              <?php } ?>
              <?php if ($product['recurring']) { ?>
                <br/>
                <span class="label label-info"><?php echo $text_recurring_item; ?></span>
                <small><?php echo $product['recurring']; ?></small>
              <?php } ?></td>
          <td class="text-left d-none align-middle"><?php echo $product['model']; ?></td>
          <td class="text-left align-middle" style="min-width: 124px;">
            <div class="input-group btn-block" style="max-width: 200px;">

              <span class="input-group-btn" style="white-space: nowrap;">

                      <div class="form-quantity form-quantity-product">
                      <div class="box-input-qty">

                      <input type="text" name="quantity[<?php echo $product['cart_id']; ?>]"
                             id = "cart_id_<?php echo $product['cart_id']; ?>"

                             value="<?php echo $product['quantity']; ?>" size="1" class="form-control"/>

                      <div class="btn-plus"><input type="button" data-for="cart_id_<?php echo $product['cart_id']; ?>" value="+" class="qty plus"/></div>
                      <div class="btn-minus"><input type="button" data-for="cart_id_<?php echo $product['cart_id']; ?>" value="-" class="qty minus"/></div>
                      </div>

                          <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>"/>
                      </div>



                    <button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>"
                            data-cart-id="<?php echo $product['cart_id']; ?>"

                            class="btn btn-danger btn-remove ">
                      <i class="fa fa-times-circle"></i>
                    </button>

                    </span></div>
          </td>
          <td class="text-right align-middle"><?php echo $product['price_enduser_formatted']; ?></td>
          <td class="text-right align-middle"><?php echo $product['price_enduser_total_formatted']; ?></td>
        </tr>
      <?php } ?>
      <?php foreach ($vouchers as $voucher) { ?>
        <tr>
          <td></td>
          <td class="text-left"><?php echo $voucher['description']; ?></td>
          <td class="text-left"></td>
          <td class="text-left">
            <div class="input-group btn-block" style="max-width: 200px;">
              <input type="text" name="" value="1" size="1" disabled="disabled" class="form-control"/>
              <span class="input-group-btn">
                    <button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>"
                            class="btn btn-danger" onclick="voucher.remove('<?php echo $voucher['key']; ?>');"><i
                        class="fa fa-times-circle"></i>
                    </button>
                    </span></div>
          </td>
          <td class="text-right"><?php echo $voucher['amount']; ?></td>
          <td class="text-right"><?php echo $voucher['amount']; ?></td>
        </tr>
      <?php } ?>


      <?php

      if(!empty( $shipping )) {
        ?>
        <tr>
          <td class="text-center">-</td>
          <td class="text-left align-middle"><?php echo $text_shipping; ?>: <?php echo $shipping['title']; ?></td>
          <td class="text-left d-none align-middle"> - </td>
          <td class="text-left align-middle">
            -
          </td>
          <td class="text-right align-middle">-</td>
          <td class="text-right align-middle"><?php echo $shipping['price_enduser_formatted']; ?></td>
        </tr>
      <?php } ?>



      <?php foreach ($totals as $total) { ?>
        <?php if($total['code'] == 'shipping'){ continue; } // doc: 'shipping' attēlojas kā atsevišķa produkta pozīcija ar PVN :) tāpēc šeit skipojam. ?>
        <tr>
          <td class="text-right border-left-0 border-bottom-0 border-top-0 d-none d-sm-table-cell " colspan="2">&nbsp;
          </td>
          <td class="text-right" colspan="2"><strong><?php echo $total['title']; ?>:</strong></td>
          <td class="text-right"><?php echo $total['text']; ?></td>
        </tr>
      <?php } ?>


      </tbody>
    </table>
  </div>
</form>
    <?php } else { ?>

  <h2><?php echo $text_empty; ?></h2>

<?php } ?>


<?php debug_template(__FILE__); ?>