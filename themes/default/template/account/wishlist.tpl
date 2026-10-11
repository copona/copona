<?php echo $header; ?>
<?php include(__DIR__ . '/../common/_page_open.tpl'); ?>
      <h1><?php echo $heading_title; ?></h1>
      <?php if ($products) { ?>
        <ul class="cp-rows">
          <?php foreach ($products as $product) { ?>
            <li class="cp-row">
              <a href="<?php echo $product['href']; ?>" class="cp-row__media">
                <?php if ($product['thumb']) { ?>
                  <img loading="lazy" decoding="async" src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" />
                <?php } ?>
              </a>
              <div class="cp-row__body">
                <a href="<?php echo $product['href']; ?>" class="cp-row__name"><?php echo $product['name']; ?></a>
                <div class="cp-row__meta"><?php echo $column_model; ?>: <?php echo $product['model']; ?> · <?php echo $column_stock; ?>: <?php echo $product['stock']; ?></div>
              </div>
              <?php if ($product['price']) { ?>
                <div class="cp-price">
                  <?php if (!$product['special']) { ?>
                    <span class="cp-price__now"><?php echo $product['price']; ?></span>
                  <?php } else { ?>
                    <span class="cp-price__now is-special"><?php echo $product['special']; ?></span>
                    <s class="cp-price__old"><?php echo $product['price']; ?></s>
                  <?php } ?>
                </div>
              <?php } ?>
              <div class="cp-row__actions">
                <button type="button" onclick="cart.add('<?php echo $product['product_id']; ?>');" class="btn btn-primary btn-sm"><i class="fa fa-shopping-cart" aria-hidden="true"></i> <?php echo $button_cart; ?></button>
                <a href="<?php echo $product['remove']; ?>" class="cp-icon-btn" title="<?php echo $button_remove; ?>" aria-label="<?php echo $button_remove; ?>"><i class="fa fa-times"></i></a>
              </div>
            </li>
          <?php } ?>
        </ul>
      <?php } else { ?>
        <p class="cp-empty"><?php echo $text_empty; ?></p>
      <?php } ?>
      <div class="cp-actions">
        <a href="<?php echo $continue; ?>" class="btn btn-outline-secondary"><?php echo $button_continue; ?></a>
      </div>
<?php include(__DIR__ . '/../common/_page_close.tpl'); ?>
<?php echo $footer; ?>
