<div class="row gx-4 gx-lg-5">

  <!-- Product table -->
  <div class="col-md-8">
    <div class="table-responsive">
      <table class="cart-table">
        <thead>
          <tr>
            <th class="cart-td-img"></th>
            <th><?php echo $column_name; ?></th>
            <th><?php echo $column_quantity; ?></th>
            <th class="text-end"><?php echo $column_price; ?></th>
            <th class="text-end"><?php echo $column_total; ?></th>
            <th></th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($products as $product) { ?>
            <tr>
              <td class="cart-td-img">
                <?php if ($product['thumb']) { ?>
                  <a href="<?php echo $product['href']; ?>">
                    <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>">
                  </a>
                <?php } ?>
              </td>

              <td>
                <a href="<?php echo $product['href']; ?>" class="cart-product-name"><?php echo $product['name']; ?></a>
                <div class="cart-product-meta"><?php echo $column_model; ?>: <?php echo $product['model']; ?></div>
                <?php if (!$product['stock']) { ?><span class="text-danger small">***</span><?php } ?>
                <?php foreach ($product['option'] as $option) { ?>
                  <div class="cart-product-option">- <?php echo $option['name']; ?>: <?php echo $option['value']; ?></div>
                <?php } ?>
                <?php if ($product['reward']) { ?>
                  <div class="cart-product-meta"><?php echo $product['reward']; ?></div>
                <?php } ?>
                <?php if ($product['recurring']) { ?>
                  <div class="mt-1"><span class="badge bg-info"><?php echo $text_recurring_item; ?></span> <small><?php echo $product['recurring']; ?></small></div>
                <?php } ?>
              </td>

              <td>
                <div class="cart-qty-pill">
                  <button type="button" onclick="var i=this.nextElementSibling;i.value=Math.max(1,parseInt(i.value||1)-1);cart.update(<?php echo $product['cart_id']; ?>,i.value)">&#8722;</button>
                  <input type="text" name="quantity[<?php echo $product['cart_id']; ?>]" value="<?php echo $product['quantity']; ?>" class="checkout-quantity" onblur="cart.update(<?php echo $product['cart_id']; ?>,this.value)">
                  <button type="button" onclick="var i=this.previousElementSibling;i.value=parseInt(i.value||1)+1;cart.update(<?php echo $product['cart_id']; ?>,i.value)">&#43;</button>
                </div>
              </td>

              <td class="td-unit-price text-end"><?php echo $product['price']; ?></td>
              <td class="td-line-total text-end"><?php echo $product['total']; ?></td>

              <td>
                <button type="button" class="cart-btn-remove" onclick="cart.remove('<?php echo $product['cart_id']; ?>')" title="<?php echo $button_remove; ?>">
                  <i class="fa fa-times"></i>
                </button>
              </td>
            </tr>
          <?php } ?>

          <?php foreach ($vouchers as $voucher) { ?>
            <tr>
              <td class="cart-td-img"></td>
              <td><?php echo $voucher['description']; ?></td>
              <td>
                <div class="cart-qty-pill">
                  <button type="button" disabled>&#8722;</button>
                  <input type="text" value="1" disabled style="width:38px;">
                  <button type="button" disabled>&#43;</button>
                </div>
              </td>
              <td class="td-unit-price text-end"><?php echo $voucher['amount']; ?></td>
              <td class="td-line-total text-end"><?php echo $voucher['amount']; ?></td>
              <td>
                <button type="button" class="cart-btn-remove" onclick="voucher.remove('<?php echo $voucher['key']; ?>')" title="<?php echo $button_remove; ?>">
                  <i class="fa fa-times"></i>
                </button>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div><!-- /col-md-8 -->

  <!-- Order summary sidebar -->
  <div class="col-md-4 cart-info-sidebar">
    <div class="cart-summary">
      <div class="cart-summary-heading"><?php echo $text_next ?? 'Order Summary'; ?></div>

      <?php foreach ($totals as $total) { ?>
        <div class="cart-summary-line <?php echo ($total['code'] === 'total') ? 'cart-summary-total' : ''; ?>">
          <span><?php echo $total['title']; ?></span>
          <span><?php echo $total['text']; ?></span>
        </div>
      <?php } ?>

      <?php if ($modules) { ?>
        <div class="mt-3">
          <div class="accordion" id="accordion">
            <?php foreach ($modules as $module) { echo $module; } ?>
          </div>
        </div>
      <?php } ?>

      <div class="cart-info-actions">
        <a href="<?php echo $checkout; ?>" class="cart-btn-checkout">
          <?php echo $button_checkout; ?> &rarr;
        </a>
      </div>
    </div>
  </div><!-- /col-md-4 -->

</div>
