<?php echo $header; ?>
<?php include(__DIR__ . '/../common/_page_open.tpl'); ?>
      <div class="cp-page-intro">
        <h1><?php echo $text_address_book; ?></h1>
        <a href="<?php echo $add; ?>" class="btn btn-primary"><i class="fa fa-plus" aria-hidden="true"></i> <?php echo $button_new_address; ?></a>
      </div>
      <?php if ($addresses) { ?>
        <div class="cp-cards">
          <?php foreach ($addresses as $result) { ?>
            <div class="cp-panel cp-address">
              <address class="cp-address__body"><?php echo $result['address']; ?></address>
              <div class="cp-address__actions">
                <a href="<?php echo $result['update']; ?>" class="btn btn-sm btn-outline-secondary"><?php echo $button_edit; ?></a>
                <a href="<?php echo $result['delete']; ?>" class="btn btn-sm btn-link cp-link-danger"><?php echo $button_delete; ?></a>
              </div>
            </div>
          <?php } ?>
        </div>
      <?php } else { ?>
        <p class="cp-empty"><?php echo $text_empty; ?></p>
      <?php } ?>
      <div class="cp-actions">
        <a href="<?php echo $back; ?>" class="btn btn-outline-secondary"><?php echo $button_back; ?></a>
      </div>
<?php include(__DIR__ . '/../common/_page_close.tpl'); ?>
<?php echo $footer; ?>
