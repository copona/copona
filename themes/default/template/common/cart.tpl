<button type="button" data-bs-toggle="dropdown" data-loading-text="<?php echo $text_loading; ?>"
        class="btn btn-inverse dropdown-toggle"><i class="fa fa-shopping-cart"></i> <span
    id="cart-total"><?php echo $text_items; ?></span></button>

<ul class="dropdown-menu dropdown-menu-end shadow" style="min-width:300px;max-width:340px">
  <?php if ($products || $vouchers) { ?>
    <li>
      <div class="px-2 pt-2" style="max-height:380px;overflow-y:auto">
        <?php foreach ($products as $product) { ?>
          <div class="d-flex gap-2 align-items-start py-2 border-bottom">
            <?php if ($product['thumb']) { ?>
              <a href="<?php echo $product['href']; ?>" class="flex-shrink-0">
                <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>"
                     style="width:44px;height:44px;object-fit:contain;border-radius:6px;background:#f9fafb;border:1px solid #f3f4f6">
              </a>
            <?php } ?>
            <div class="flex-grow-1" style="min-width:0;font-size:.82rem">
              <a href="<?php echo $product['href']; ?>" class="fw-semibold text-dark text-decoration-none d-block text-truncate"><?php echo $product['name']; ?></a>
              <span class="text-muted">× <?php echo $product['quantity']; ?></span>
              <?php foreach ($product['option'] as $option) { ?>
                <div class="text-muted" style="font-size:.75rem">— <?php echo $option['name']; ?>: <?php echo $option['value']; ?></div>
              <?php } ?>
              <?php if ($product['recurring']) { ?>
                <div class="text-muted" style="font-size:.75rem">— <?php echo $product['recurring']; ?></div>
              <?php } ?>
            </div>
            <div class="flex-shrink-0 text-end" style="font-size:.82rem">
              <div class="fw-bold"><?php echo $product['price_enduser_total_formatted']; ?></div>
              <button type="button" onclick="cart.remove('<?php echo $product['cart_id']; ?>');"
                      title="<?php echo $button_remove; ?>" class="btn btn-link btn-sm text-danger p-0 mt-1" style="line-height:1">
                <i class="fa fa-times"></i>
              </button>
            </div>
          </div>
        <?php } ?>
        <?php foreach ($vouchers as $voucher) { ?>
          <div class="d-flex justify-content-between align-items-center py-2 border-bottom gap-2" style="font-size:.82rem">
            <span><?php echo $voucher['description']; ?></span>
            <div class="d-flex align-items-center gap-2">
              <span class="fw-bold"><?php echo $voucher['amount']; ?></span>
              <button type="button" onclick="voucher.remove('<?php echo $voucher['key']; ?>');"
                      class="btn btn-link btn-sm text-danger p-0"><i class="fa fa-times"></i></button>
            </div>
          </div>
        <?php } ?>
      </div>
    </li>
    <li>
      <div class="px-3 py-2">
        <?php foreach ($totals as $total) { ?>
          <div class="d-flex justify-content-between<?php echo ($total['code'] === 'total') ? ' fw-bold border-top pt-1 mt-1' : ''; ?>" style="font-size:.82rem">
            <span><?php echo $total['title']; ?></span>
            <span><?php echo $total['text']; ?></span>
          </div>
        <?php } ?>
        <div class="d-flex gap-2 mt-2">
          <a href="<?php echo $cart; ?>" class="btn btn-outline-secondary btn-sm flex-fill text-center">
            <i class="fa fa-shopping-cart me-1"></i><?php echo $text_cart; ?>
          </a>
          <a href="<?php echo $checkout; ?>" class="btn btn-primary btn-sm flex-fill text-center">
            <i class="fa fa-share me-1"></i><?php echo $text_checkout; ?>
          </a>
        </div>
      </div>
    </li>
  <?php } else { ?>
    <li class="px-3 py-3 text-center text-muted small"><?php echo $text_empty; ?></li>
  <?php } ?>
</ul>
