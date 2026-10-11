<?php echo $header; ?>
<?php include(__DIR__ . '/../common/_page_open.tpl'); ?>
      <article class="cp-article">
        <h1><?php echo $heading_title; ?></h1>
        <?php if ($thumb || $images) { ?>
          <ul class="cp-article__gallery thumbnails">
            <?php if ($thumb) { ?>
              <li><a href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>"><img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" /></a></li>
            <?php } ?>
            <?php foreach ($images as $image) { ?>
              <li><a href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>"><img loading="lazy" src="<?php echo $image['thumb']; ?>" alt="<?php echo $heading_title; ?>" /></a></li>
            <?php } ?>
          </ul>
        <?php } ?>
        <div class="cp-prose"><?php echo $description; ?></div>
      </article>
<?php include(__DIR__ . '/../common/_page_close.tpl'); ?>
<script>
    $(function () {
        if ($.fn.magnificPopup) {
            $('.thumbnails').magnificPopup({type: 'image', delegate: 'a', gallery: {enabled: true}});
        }
    });
</script>
<?php echo $footer; ?>
