<section class="cp-section">
  <h2 class="cp-section__title"><?php echo $heading_title; ?></h2>
  <?php if ($products) { ?>
    <div class="cp-product-grid">
      <?php foreach ($products as $product) { ?>
        <?php include(__DIR__ . '/_product_card.tpl'); ?>
      <?php } ?>
    </div>
  <?php } else { ?>
    <p><?php echo $text_empty; ?></p>
  <?php } ?>
</section>
