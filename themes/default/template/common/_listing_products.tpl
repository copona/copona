<div class="cp-product-grid">
  <?php foreach ($products as $product) { if (empty($product['product_id'])) continue; ?>
    <?php include(__DIR__ . '/_product_card.tpl'); ?>
  <?php } ?>
</div>
<div class="cp-pagination">
  <?php echo $pagination; ?>
  <span class="cp-pagination__results"><?php echo $results; ?></span>
</div>
