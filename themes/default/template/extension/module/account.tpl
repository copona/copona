<div class="list-group">
  <?php foreach ($links as $link) {
      if ($link['status']){ ?>
    <a href="<?php echo $link['href']; ?>" class="list-group-item"><?php echo $link['name']; ?></a>
  <?php }
   } ?>
</div>