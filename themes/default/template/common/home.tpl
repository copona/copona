<?php echo $header; ?>
<div class="container cp-page cp-home">
  <div class="cp-layout<?php echo $column_left ? ' has-left' : ''; ?><?php echo $column_right ? ' has-right' : ''; ?>">
    <?php echo $column_left; ?>
    <div id="content">
      <?php echo $content_top; ?>
      <?php if ($categories) { ?>
        <section class="cp-section">
          <h2 class="cp-section__title"><?php echo $text_categories; ?></h2>
          <div class="cp-tiles">
            <?php foreach ($categories as $category) { ?>
              <a href="<?php echo $category['href']; ?>" class="cp-tile">
                <span class="cp-tile__name"><?php echo $category['name']; ?></span>
                <span class="cp-tile__count"><?php echo sprintf($text_category_total, $category['total']); ?></span>
              </a>
            <?php } ?>
          </div>
        </section>
      <?php } ?>
      <?php echo $content_bottom; ?>
    </div>
    <?php echo $column_right; ?>
  </div>
</div>
<?php echo $footer; ?>
