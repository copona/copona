<h3><?php echo $heading_title; ?></h3>
<div class="row gy-4">
  <?php if ($products) { ?>
    <?php foreach ($products as $product) { ?>
      <?php include(__DIR__ . '/_product_card.tpl'); ?>
    <?php } ?>
  <?php } else { ?>
    <div class="col-sm-12"><p><?php echo $text_empty; ?></p></div>
  <?php } ?>
</div>
