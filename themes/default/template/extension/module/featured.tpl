<div class="divider"><h3><?php echo $heading_title; ?></h3></div>
<div class="row">
    <?php foreach ($products as $product) { ?>
      <div class="col-lg-3 col-md-3 col-sm-4 product">
        <div class="image">
          <a href="<?= $product['href']; ?>">
            <img src="<?= $product['thumb']; ?>" alt="<?= $product['name']; ?>">
          </a>
        </div>
        <div class="info">
          <div class="name">
            <a href=""><?= $product['name'] ?></a>
          </div>
          <div class="price">
            <span class="price-new"> <?php echo $product['special']; ?></span>
            <span class="price-old"> <?php echo $product['price']; ?></span>
          </div>
          <div class="rating">
              <?php for ($i = 1; $i <= 5; $i++) { ?>
                  <?php if ($product['rating'] < $i) { ?>
                  <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i></span>
                  <?php } else { ?>
                  <span class="fa fa-stack"><i class="fa fa-star active fa-stack-2x"></i></span>
                  <?php } ?>
              <?php } ?>
          </div>
        </div>
        <div class="buttons">
          <button type="button"
                  onclick="cart.add('<?php echo $product['product_id']; ?>', '<?php echo $product['minimum']; ?>');">
            <i class="fa fa-shopping-cart"></i>
          </button>

          <a class="btn btn-primary" href="<?= $product['href']; ?>"><span><i
                  class="fa fa-chevron-right"></i><?= $text_know_more ?></span></a>
        </div>
      </div>
  <?php } ?>
</div>
