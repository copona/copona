<?php echo $header; ?>
<?php include(__DIR__ . '/../common/_page_open.tpl'); ?>
      <h1><?php echo $heading_title; ?></h1>
      <?php if ($categories) { ?>
        <nav class="cp-chips cp-chips--index" aria-label="<?php echo strip_tags($text_index); ?>">
          <?php foreach ($categories as $category) { ?>
            <a href="#brand-<?php echo urlencode($category['name']); ?>"><?php echo $category['name']; ?></a>
          <?php } ?>
        </nav>
        <?php foreach ($categories as $category) { ?>
          <section class="cp-brand-group" id="brand-<?php echo urlencode($category['name']); ?>">
            <h2 class="cp-brand-group__letter"><?php echo $category['name']; ?></h2>
            <?php if ($category['manufacturer']) { ?>
              <ul class="cp-brand-group__list">
                <?php foreach ($category['manufacturer'] as $manufacturer) { ?>
                  <li><a href="<?php echo $manufacturer['href']; ?>"><?php echo $manufacturer['name']; ?></a></li>
                <?php } ?>
              </ul>
            <?php } ?>
          </section>
        <?php } ?>
      <?php } else { ?>
        <p class="cp-empty"><?php echo $text_empty; ?></p>
      <?php } ?>
<?php include(__DIR__ . '/../common/_page_close.tpl'); ?>
<?php echo $footer; ?>
