<section class="cp-section">
  <h2 class="cp-section__title"><?php echo $heading_title; ?></h2>
  <div class="cp-product-grid">
    <?php foreach ($products as $product) { ?>
      <?php include(__DIR__ . '/../../common/_product_card.tpl'); ?>
    <?php } ?>
  </div>
</section>
