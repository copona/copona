<?php if (!empty($pagination) || !empty($results)) { ?>
  <div class="cp-pagination">
    <?php echo $pagination ?? ''; ?>
    <span class="cp-pagination__results"><?php echo $results ?? ''; ?></span>
  </div>
<?php } ?>
