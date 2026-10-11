<?php echo $header; ?>
<?php include(__DIR__ . '/../common/_page_open.tpl'); ?>
      <h1><?php echo $heading_title; ?></h1>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="cp-form cp-form--narrow">
        <fieldset class="cp-fieldset">
          <?php $yes_no = ['name' => 'newsletter', 'label' => $entry_newsletter, 'value' => $newsletter]; include(__DIR__ . '/../common/_yes_no.tpl'); ?>
        </fieldset>
        <div class="cp-actions">
          <a href="<?php echo $back; ?>" class="btn btn-outline-secondary"><?php echo $button_back; ?></a>
          <button type="submit" class="btn btn-primary"><?php echo $button_continue; ?></button>
        </div>
      </form>
<?php include(__DIR__ . '/../common/_page_close.tpl'); ?>
<?php echo $footer; ?>
