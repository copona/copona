<div class="tile">
  <div class="tile-heading"><?php echo $heading_title; ?> <span class="float-end">
          <?php if ($percentage > 0) { ?>
          <i class="fa fa-caret-up"></i>
      <?php } elseif ($percentage < 0) { ?>
          <i class="fa fa-caret-down"></i>
      <?php } ?>
      <?php echo $percentage; ?>%</span></div>
  <div class="tile-body"><i class="fa fa-shopping-cart"></i>
    <h2 class="float-end"><?php echo $total; ?></h2>
  </div>
  <div class="tile-footer"><a href="<?php echo $order; ?>"><?php echo $text_view; ?></a></div>
</div>
