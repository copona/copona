<?php echo $header; ?>
<?php include(__DIR__ . '/../common/_page_open.tpl'); ?>
      <div class="cp-page-intro">
        <h1><?php echo $heading_title; ?></h1>
        <p class="cp-muted"><?php echo $text_description; ?></p>
      </div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="cp-form">
        <fieldset class="cp-fieldset">
          <legend><?php echo $text_order; ?></legend>
          <div class="cp-fields">
            <?php $field = ['name' => 'firstname', 'label' => $entry_firstname, 'value' => $firstname, 'id' => 'input-firstname', 'error' => $error_firstname, 'required' => true, 'autocomplete' => 'given-name']; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $field = ['name' => 'lastname', 'label' => $entry_lastname, 'value' => $lastname, 'id' => 'input-lastname', 'error' => $error_lastname, 'required' => true, 'autocomplete' => 'family-name']; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $field = ['name' => 'email', 'label' => $entry_email, 'value' => $email, 'id' => 'input-email', 'type' => 'email', 'error' => $error_email, 'required' => true, 'autocomplete' => 'email']; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $field = ['name' => 'telephone', 'label' => $entry_telephone, 'value' => $telephone, 'id' => 'input-telephone', 'type' => 'tel', 'error' => $error_telephone, 'required' => true, 'autocomplete' => 'tel']; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $field = ['name' => 'order_id', 'label' => $entry_order_id, 'value' => $order_id, 'id' => 'input-order-id', 'error' => $error_order_id, 'required' => true]; include(__DIR__ . '/../common/_field.tpl'); ?>
            <div class="form-group cp-field">
              <label class="form-label" for="input-date-ordered"><?php echo $entry_date_ordered; ?></label>
              <div class="input-group date">
                <input type="text" name="date_ordered" value="<?php echo $date_ordered; ?>" placeholder="YYYY-MM-DD" data-date-format="YYYY-MM-DD" id="input-date-ordered" class="form-control" />
                <button type="button" class="btn btn-outline-secondary" aria-label="<?php echo $entry_date_ordered; ?>"><i class="fa fa-calendar"></i></button>
              </div>
            </div>
          </div>
        </fieldset>
        <fieldset class="cp-fieldset">
          <legend><?php echo $text_product; ?></legend>
          <div class="cp-fields">
            <?php $field = ['name' => 'product', 'label' => $entry_product, 'value' => $product, 'id' => 'input-product', 'error' => $error_product, 'required' => true]; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $field = ['name' => 'model', 'label' => $entry_model, 'value' => $model, 'id' => 'input-model', 'error' => $error_model, 'required' => true]; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $field = ['name' => 'quantity', 'label' => $entry_quantity, 'value' => $quantity, 'id' => 'input-quantity', 'type' => 'number']; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $yes_no = ['name' => 'opened', 'label' => $entry_opened, 'value' => $opened]; include(__DIR__ . '/../common/_yes_no.tpl'); ?>
            <div class="form-group cp-field cp-field--wide required">
              <span class="form-label"><?php echo $entry_reason; ?></span>
              <div class="cp-choices">
                <?php foreach ($return_reasons as $return_reason) { ?>
                  <label class="form-check">
                    <input type="radio" name="return_reason_id" value="<?php echo $return_reason['return_reason_id']; ?>" class="form-check-input"<?php echo $return_reason['return_reason_id'] == $return_reason_id ? ' checked="checked"' : ''; ?> />
                    <span class="form-check-label"><?php echo $return_reason['name']; ?></span>
                  </label>
                <?php } ?>
              </div>
              <?php if ($error_reason) { ?>
                <div class="text-danger"><?php echo $error_reason; ?></div>
              <?php } ?>
            </div>
            <?php $field = ['name' => 'comment', 'label' => $entry_fault_detail, 'value' => $comment, 'id' => 'input-comment', 'type' => 'textarea', 'wide' => true]; include(__DIR__ . '/../common/_field.tpl'); ?>
          </div>
          <?php echo $captcha; ?>
        </fieldset>
        <div class="cp-actions">
          <a href="<?php echo $back; ?>" class="btn btn-outline-secondary"><?php echo $button_back; ?></a>
          <div class="cp-actions__end">
            <?php if ($text_agree) { ?>
              <label class="form-check cp-agree">
                <input type="checkbox" name="agree" value="1" class="form-check-input"<?php echo $agree ? ' checked="checked"' : ''; ?> />
                <span class="form-check-label"><?php echo $text_agree; ?></span>
              </label>
            <?php } ?>
            <button type="submit" class="btn btn-primary"><?php echo $button_submit; ?></button>
          </div>
        </div>
      </form>
<?php include(__DIR__ . '/../common/_page_close.tpl'); ?>
<?php echo $footer; ?>
