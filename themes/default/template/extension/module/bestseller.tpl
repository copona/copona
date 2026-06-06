<h3><?php echo $heading_title; ?></h3>
<div class="row">
  <?php foreach ($products as $product) { ?>
    <?php include(__DIR__ . '/../../common/_product_card.tpl'); ?>
  <?php } ?>
</div>
