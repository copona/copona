<?php echo $header; ?>
<?php include(__DIR__ . '/../common/_page_open.tpl'); ?>
      <h1><?php echo $heading_title; ?></h1>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="cp-form">
        <fieldset class="cp-fieldset">
          <legend><?php echo $text_your_details; ?></legend>
          <div class="cp-fields">
            <?php $field = ['name' => 'firstname', 'label' => $entry_firstname, 'value' => $firstname, 'id' => 'input-firstname', 'error' => $error_firstname, 'required' => true, 'autocomplete' => 'given-name']; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $field = ['name' => 'lastname', 'label' => $entry_lastname, 'value' => $lastname, 'id' => 'input-lastname', 'error' => $error_lastname, 'required' => true, 'autocomplete' => 'family-name']; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $field = ['name' => 'email', 'label' => $entry_email, 'value' => $email, 'id' => 'input-email', 'type' => 'email', 'error' => $error_email, 'required' => true, 'autocomplete' => 'email']; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $field = ['name' => 'telephone', 'label' => $entry_telephone, 'value' => $telephone, 'id' => 'input-telephone', 'type' => 'tel', 'error' => $error_telephone ?? '', 'autocomplete' => 'tel']; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $cf_prefix = 'custom_field'; $cf_values = $account_custom_field; ?>
            <?php foreach ($custom_fields as $custom_field) { if ($custom_field['location'] == 'account') include(__DIR__ . '/_custom_field.tpl'); } ?>
          </div>
        </fieldset>
        <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>" id="input-csrf-token" />
        <div class="cp-actions">
          <a href="<?php echo $back; ?>" class="btn btn-outline-secondary"><?php echo $button_back; ?></a>
          <button type="submit" class="btn btn-primary"><?php echo $button_continue; ?></button>
        </div>
      </form>
<?php include(__DIR__ . '/../common/_page_close.tpl'); ?>
<script>
// Sort the custom fields
    $('.form-group[data-sort]').detach().each(function () {
        if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('.form-group').length) {
            $('.form-group').eq($(this).attr('data-sort')).before(this);
        }

        if ($(this).attr('data-sort') > $('.form-group').length) {
            $('.form-group:last').after(this);
        }

        if ($(this).attr('data-sort') == $('.form-group').length) {
            $('.form-group:last').after(this);
        }

        if ($(this).attr('data-sort') < -$('.form-group').length) {
            $('.form-group:first').before(this);
        }
    });
</script>
<?php include(__DIR__ . '/_custom_field_upload.tpl'); ?>
<?php echo $footer; ?>
