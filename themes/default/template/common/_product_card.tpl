<article class="cp-card">
  <a href="<?php echo $product['href']; ?>" class="cp-card__media">
    <img loading="lazy" decoding="async" src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>"/>
    <?php if ($product['special']) { ?><span class="cp-badge">Sale</span><?php } ?>
  </a>
  <div class="cp-card__tools">
    <button type="button" title="<?php echo $button_wishlist; ?>" aria-label="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-heart-o"></i></button>
    <button type="button" title="<?php echo $button_compare; ?>" aria-label="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');"><i class="fa fa-exchange"></i></button>
  </div>
  <div class="cp-card__body">
    <h3 class="cp-card__name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h3>
    <?php if ($product['description']) { ?>
      <p class="cp-card__desc"><?php echo $product['description']; ?></p>
    <?php } ?>
    <?php if ($product['rating']) { ?>
      <div class="cp-rating" aria-label="<?php echo $product['rating']; ?>/5">
        <?php for ($i = 1; $i <= 5; $i++) { ?><i class="fa <?php echo $product['rating'] < $i ? 'fa-star-o' : 'fa-star'; ?>"></i><?php } ?>
      </div>
    <?php } ?>
  </div>
  <div class="cp-card__foot">
    <?php if ($product['price']) { ?>
      <div class="cp-price">
        <?php if (!$product['special']) { ?>
          <span class="cp-price__now"><?php echo $product['price']; ?></span>
        <?php } else { ?>
          <span class="cp-price__now is-special"><?php echo $product['special']; ?></span>
          <s class="cp-price__old"><?php echo $product['price']; ?></s>
        <?php } ?>
        <?php if ($product['tax']) { ?>
          <span class="cp-price__tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
        <?php } ?>
      </div>
    <?php } ?>
    <button type="button" class="cp-card__add" title="<?php echo $button_cart; ?>" aria-label="<?php echo $button_cart; ?>" onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum'] ?? 1; ?>');"><i class="fa fa-plus"></i></button>
  </div>
</article>
