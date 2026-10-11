<?php echo $header; ?>
<?php include(__DIR__ . '/_page_open.tpl'); ?>
      <div class="cp-message">
        <h1><?php echo $heading_title; ?></h1>
        <div class="cp-prose"><?php echo $text_message; ?></div>
        <?php if (!empty($continue)) { ?>
          <div class="cp-actions">
            <a href="<?php echo $continue; ?>" class="btn btn-primary"><?php echo $button_continue; ?></a>
          </div>
        <?php } ?>
      </div>
<?php include(__DIR__ . '/_page_close.tpl'); ?>
<?php echo $footer; ?>
