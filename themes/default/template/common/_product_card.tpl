<div class="product-layout <?php echo $product_col_class ?? 'col-lg-3 col-md-3 col-sm-6 col-12'; ?>">
  <div class="product-thumb">
    <div class="image">
      <a href="<?php echo $product['href']; ?>">
        <img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-fluid"/>
      </a>
    </div>
    <div class="caption">
      <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
      <?php if ($product['description']) { ?>
        <p><?php echo $product['description']; ?></p>
      <?php } ?>
      <?php if ($product['rating']) { ?>
        <div class="rating">
          <?php for ($i = 1; $i <= 5; $i++) { ?>
            <?php if ($product['rating'] < $i) { ?>
              <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
            <?php } else { ?>
              <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
            <?php } ?>
          <?php } ?>
        </div>
      <?php } ?>
      <?php if ($product['price']) { ?>
        <p class="price">
          <?php if (!$product['special']) { ?>
            <?php echo $product['price']; ?>
          <?php } else { ?>
            <span class="price-new"><?php echo $product['special']; ?></span>
            <span class="price-old"><?php echo $product['price']; ?></span>
          <?php } ?>
          <?php if ($product['tax']) { ?>
            <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
          <?php } ?>
        </p>
      <?php } ?>
    </div>
    <div class="button-group">
      <button type="button" class="btn btn-primary" onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum'] ?? 1; ?>');">
        <i class="fa fa-shopping-cart"></i>
        <span class="d-none d-lg-inline"><?php echo $button_cart; ?></span>
      </button>
      <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product['product_id']; ?>');">
        <i class="fa fa-heart"></i>
      </button>
      <button type="button" class="btn btn-outline-secondary" data-bs-toggle="tooltip" title="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product['product_id']; ?>');">
        <i class="fa fa-exchange"></i>
      </button>
    </div>
  </div>
</div>
