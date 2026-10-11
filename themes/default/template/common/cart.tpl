<button type="button" data-bs-toggle="dropdown" aria-expanded="false" data-loading-text="<?php echo $text_loading; ?>"
        class="cp-cart-btn"><span id="cart-total"><i class="fa fa-shopping-cart"></i> <?php echo $text_items; ?></span></button>
<ul class="dropdown-menu dropdown-menu-end cp-minicart">
  <?php if ($products || $vouchers) { ?>
    <li class="cp-minicart__items">
      <?php foreach ($products as $product) { ?>
        <div class="cp-minicart__item">
          <?php if ($product['thumb']) { ?>
            <a href="<?php echo $product['href']; ?>" class="cp-minicart__thumb"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>"></a>
          <?php } ?>
          <div class="cp-minicart__info">
            <a href="<?php echo $product['href']; ?>" class="cp-minicart__name"><?php echo $product['name']; ?></a>
            <span class="cp-minicart__meta">× <?php echo $product['quantity']; ?></span>
            <?php foreach ($product['option'] as $option) { ?>
              <span class="cp-minicart__meta"><?php echo $option['name']; ?>: <?php echo $option['value']; ?></span>
            <?php } ?>
            <?php if ($product['recurring']) { ?>
              <span class="cp-minicart__meta"><?php echo $product['recurring']; ?></span>
            <?php } ?>
          </div>
          <div class="cp-minicart__price">
            <strong><?php echo $product['price_enduser_total_formatted']; ?></strong>
            <button type="button" onclick="cart.remove('<?php echo $product['cart_id']; ?>');" title="<?php echo $button_remove; ?>" aria-label="<?php echo $button_remove; ?>"><i class="fa fa-times"></i></button>
          </div>
        </div>
      <?php } ?>
      <?php foreach ($vouchers as $voucher) { ?>
        <div class="cp-minicart__item">
          <div class="cp-minicart__info"><?php echo $voucher['description']; ?></div>
          <div class="cp-minicart__price">
            <strong><?php echo $voucher['amount']; ?></strong>
            <button type="button" onclick="voucher.remove('<?php echo $voucher['key']; ?>');" title="<?php echo $button_remove; ?>" aria-label="<?php echo $button_remove; ?>"><i class="fa fa-times"></i></button>
          </div>
        </div>
      <?php } ?>
    </li>
    <li class="cp-minicart__totals">
      <?php foreach ($totals as $total) { ?>
        <div class="cp-minicart__total<?php echo ($total['code'] === 'total') ? ' is-grand' : ''; ?>">
          <span><?php echo $total['title']; ?></span>
          <span><?php echo $total['text']; ?></span>
        </div>
      <?php } ?>
      <div class="cp-minicart__actions">
        <a href="<?php echo $cart; ?>" class="btn btn-outline-dark"><?php echo $text_cart; ?></a>
        <a href="<?php echo $checkout; ?>" class="btn btn-primary"><?php echo $text_checkout; ?></a>
      </div>
    </li>
  <?php } else { ?>
    <li class="cp-minicart__empty"><?php echo $text_empty; ?></li>
  <?php } ?>
</ul>
