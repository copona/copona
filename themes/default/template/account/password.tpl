<?php echo $header; ?>
<?php include(__DIR__ . '/../common/_page_open.tpl'); ?>
      <h1><?php echo $heading_title; ?></h1>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="cp-form cp-form--narrow">
        <fieldset class="cp-fieldset">
          <legend><?php echo $text_password; ?></legend>
          <?php $field = ['name' => 'password', 'label' => $entry_password, 'value' => $password, 'id' => 'input-password', 'type' => 'password', 'error' => $error_password, 'required' => true, 'autocomplete' => 'new-password']; include(__DIR__ . '/../common/_field.tpl'); ?>
          <?php $field = ['name' => 'confirm', 'label' => $entry_confirm, 'value' => $confirm, 'id' => 'input-confirm', 'type' => 'password', 'error' => $error_confirm, 'required' => true, 'autocomplete' => 'new-password']; include(__DIR__ . '/../common/_field.tpl'); ?>
        </fieldset>
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>" id="input-csrf-token" />
        <div class="cp-actions">
          <a href="<?php echo $back; ?>" class="btn btn-outline-secondary"><?php echo $button_back; ?></a>
          <button type="submit" class="btn btn-primary"><?php echo $button_continue; ?></button>
        </div>
      </form>
<?php include(__DIR__ . '/../common/_page_close.tpl'); ?>
<?php echo $footer; ?>
