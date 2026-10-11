<?php echo $header; ?>
<?php include(__DIR__ . '/../common/_page_open.tpl'); ?>
      <h1><?php echo $heading_title; ?></h1>
      <div class="cp-auth">
        <section class="cp-panel cp-auth__panel">
          <h2><?php echo $text_returning_customer; ?></h2>
          <p class="cp-muted"><?php echo $text_i_am_returning_customer; ?></p>
          <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="cp-form cp-form--stack">
            <?php $field = ['name' => 'email', 'label' => $entry_email, 'value' => $email, 'id' => 'input-email', 'type' => 'email', 'autocomplete' => 'email']; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $field = ['name' => 'password', 'label' => $entry_password, 'value' => $password, 'id' => 'input-password', 'type' => 'password', 'autocomplete' => 'current-password']; include(__DIR__ . '/../common/_field.tpl'); ?>
            <div class="cp-auth__row">
              <a href="<?php echo $forgotten; ?>" class="cp-link-muted"><?php echo $text_forgotten; ?></a>
              <button type="submit" class="btn btn-primary"><?php echo $button_login; ?></button>
            </div>
            <?php if ($redirect) { ?>
              <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
            <?php } ?>
          </form>
        </section>
        <section class="cp-panel cp-auth__panel cp-auth__panel--alt">
          <h2><?php echo $text_new_customer; ?></h2>
          <p><strong><?php echo $text_register; ?></strong></p>
          <p class="cp-muted"><?php echo $text_register_account; ?></p>
          <a href="<?php echo $register; ?>" class="btn btn-outline-secondary"><?php echo $button_continue; ?></a>
        </section>
      </div>
<?php include(__DIR__ . '/../common/_page_close.tpl'); ?>
<?php echo $footer; ?>
