<?php echo $header; ?>
<?php include(__DIR__ . '/../common/_page_open.tpl'); ?>
      <div class="cp-page-intro">
        <h1><?php echo $heading_title; ?></h1>
        <p class="cp-muted"><?php echo $text_description; ?></p>
      </div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="cp-form">
        <fieldset class="cp-fieldset">
          <div class="cp-fields">
            <?php $field = ['name' => 'to_name', 'label' => $entry_to_name, 'value' => $to_name, 'id' => 'input-to-name', 'error' => $error_to_name, 'required' => true]; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $field = ['name' => 'to_email', 'label' => $entry_to_email, 'value' => $to_email, 'id' => 'input-to-email', 'type' => 'email', 'error' => $error_to_email, 'required' => true]; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $field = ['name' => 'from_name', 'label' => $entry_from_name, 'value' => $from_name, 'id' => 'input-from-name', 'error' => $error_from_name, 'required' => true, 'autocomplete' => 'name']; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $field = ['name' => 'from_email', 'label' => $entry_from_email, 'value' => $from_email, 'id' => 'input-from-email', 'type' => 'email', 'error' => $error_from_email, 'required' => true, 'autocomplete' => 'email']; include(__DIR__ . '/../common/_field.tpl'); ?>
            <div class="form-group cp-field cp-field--wide required">
              <span class="form-label"><?php echo $entry_theme; ?></span>
              <div class="cp-choices cp-choices--inline">
                <?php foreach ($voucher_themes as $voucher_theme) { ?>
                  <label class="form-check">
                    <input type="radio" name="voucher_theme_id" value="<?php echo $voucher_theme['voucher_theme_id']; ?>" class="form-check-input"<?php echo $voucher_theme['voucher_theme_id'] == $voucher_theme_id ? ' checked="checked"' : ''; ?> />
                    <span class="form-check-label"><?php echo $voucher_theme['name']; ?></span>
                  </label>
                <?php } ?>
              </div>
              <?php if ($error_theme) { ?>
                <div class="text-danger"><?php echo $error_theme; ?></div>
              <?php } ?>
            </div>
            <?php $field = ['name' => 'message', 'label' => $entry_message, 'value' => $message, 'id' => 'input-message', 'type' => 'textarea', 'wide' => true, 'help' => $help_message]; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $field = ['name' => 'amount', 'label' => $entry_amount, 'value' => $amount, 'id' => 'input-amount', 'error' => $error_amount, 'help' => $help_amount]; include(__DIR__ . '/../common/_field.tpl'); ?>
          </div>
        </fieldset>
        <div class="cp-actions">
          <label class="form-check cp-agree">
            <input type="checkbox" name="agree" value="1" class="form-check-input"<?php echo $agree ? ' checked="checked"' : ''; ?> />
            <span class="form-check-label"><?php echo $text_agree; ?></span>
          </label>
          <button type="submit" class="btn btn-primary"><?php echo $button_continue; ?></button>
        </div>
      </form>
<?php include(__DIR__ . '/../common/_page_close.tpl'); ?>
<?php echo $footer; ?>
