<?php echo $header; ?>
<?php include(__DIR__ . '/../common/_page_open.tpl'); ?>
      <div class="cp-page-intro">
        <h1><?php echo $heading_title; ?></h1>
        <p class="cp-muted"><?php echo $text_account_already; ?></p>
      </div>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="cp-form">
        <fieldset id="account" class="cp-fieldset">
          <legend><?php echo $text_your_details; ?></legend>
          <div class="cp-fields">
            <div class="form-group cp-field cp-field--wide required"<?php echo count($customer_groups) > 1 ? '' : ' style="display: none;"'; ?>>
              <span class="form-label"><?php echo $entry_customer_group; ?></span>
              <div class="cp-choices cp-choices--inline">
                <?php foreach ($customer_groups as $customer_group) { ?>
                  <label class="form-check">
                    <input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" class="form-check-input"<?php echo $customer_group['customer_group_id'] == $customer_group_id ? ' checked="checked"' : ''; ?> />
                    <span class="form-check-label"><?php echo $customer_group['name']; ?></span>
                  </label>
                <?php } ?>
              </div>
            </div>
            <?php $field = ['name' => 'firstname', 'label' => $entry_firstname, 'value' => $firstname, 'id' => 'input-firstname', 'error' => $error_firstname, 'required' => true, 'autocomplete' => 'given-name']; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $field = ['name' => 'lastname', 'label' => $entry_lastname, 'value' => $lastname, 'id' => 'input-lastname', 'error' => $error_lastname, 'required' => true, 'autocomplete' => 'family-name']; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $field = ['name' => 'email', 'label' => $entry_email, 'value' => $email, 'id' => 'input-email', 'type' => 'email', 'error' => $error_email, 'required' => true, 'autocomplete' => 'email']; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $field = ['name' => 'telephone', 'label' => $entry_telephone, 'value' => $telephone, 'id' => 'input-telephone', 'type' => 'tel', 'autocomplete' => 'tel']; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $cf_prefix = 'custom_field[account]'; $cf_values = $register_custom_field; ?>
            <?php foreach ($custom_fields as $custom_field) { if ($custom_field['location'] == 'account') include(__DIR__ . '/_custom_field.tpl'); } ?>
          </div>
        </fieldset>
        <fieldset id="address" class="cp-fieldset">
          <legend><?php echo $text_your_address; ?></legend>
          <div class="cp-fields">
            <?php $field = ['name' => 'address_1', 'label' => $entry_address_1, 'value' => $address_1, 'id' => 'input-address-1', 'error' => $error_address_1, 'required' => true, 'wide' => true, 'autocomplete' => 'address-line1']; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $field = ['name' => 'city', 'label' => $entry_city, 'value' => $city, 'id' => 'input-city', 'error' => $error_city, 'required' => true, 'autocomplete' => 'address-level2']; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $field = ['name' => 'postcode', 'label' => $entry_postcode, 'value' => $postcode, 'id' => 'input-postcode', 'error' => $error_postcode, 'required' => true, 'autocomplete' => 'postal-code']; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php include(__DIR__ . '/_country_zone.tpl'); ?>
            <?php $field = ['name' => 'company', 'label' => $entry_company, 'value' => $company, 'id' => 'input-company', 'autocomplete' => 'organization']; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $cf_prefix = 'custom_field[address]'; ?>
            <?php foreach ($custom_fields as $custom_field) { if ($custom_field['location'] == 'address') include(__DIR__ . '/_custom_field.tpl'); } ?>
          </div>
        </fieldset>
        <fieldset class="cp-fieldset">
          <legend><?php echo $text_your_password; ?></legend>
          <div class="cp-fields">
            <?php $field = ['name' => 'password', 'label' => $entry_password, 'value' => $password, 'id' => 'input-password', 'type' => 'password', 'error' => $error_password, 'required' => true, 'autocomplete' => 'new-password']; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $field = ['name' => 'confirm', 'label' => $entry_confirm, 'value' => $confirm, 'id' => 'input-confirm', 'type' => 'password', 'error' => $error_confirm, 'required' => true, 'autocomplete' => 'new-password']; include(__DIR__ . '/../common/_field.tpl'); ?>
          </div>
        </fieldset>
        <fieldset class="cp-fieldset">
          <legend><?php echo $text_newsletter; ?></legend>
          <?php $yes_no = ['name' => 'newsletter', 'label' => $entry_newsletter, 'value' => $newsletter]; include(__DIR__ . '/../common/_yes_no.tpl'); ?>
        </fieldset>
        <?php echo $captcha; ?>
        <div class="cp-actions">
          <?php if ($text_agree) { ?>
            <label class="form-check cp-agree">
              <input type="checkbox" name="agree" value="1" class="form-check-input"<?php echo $agree ? ' checked="checked"' : ''; ?> />
              <span class="form-check-label"><?php echo $text_agree; ?></span>
            </label>
          <?php } ?>
          <button type="submit" class="btn btn-primary"><?php echo $button_continue; ?></button>
        </div>
      </form>
<?php include(__DIR__ . '/../common/_page_close.tpl'); ?>
<script>
// Sort the custom fields
    $('#account .form-group[data-sort]').detach().each(function () {
        if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#account .form-group').length) {
            $('#account .form-group').eq($(this).attr('data-sort')).before(this);
        }

        if ($(this).attr('data-sort') > $('#account .form-group').length) {
            $('#account .form-group:last').after(this);
        }

        if ($(this).attr('data-sort') == $('#account .form-group').length) {
            $('#account .form-group:last').after(this);
        }

        if ($(this).attr('data-sort') < -$('#account .form-group').length) {
            $('#account .form-group:first').before(this);
        }
    });

    $('#address .form-group[data-sort]').detach().each(function () {
        if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('#address .form-group').length) {
            $('#address .form-group').eq($(this).attr('data-sort')).before(this);
        }

        if ($(this).attr('data-sort') > $('#address .form-group').length) {
            $('#address .form-group:last').after(this);
        }

        if ($(this).attr('data-sort') == $('#address .form-group').length) {
            $('#address .form-group:last').after(this);
        }

        if ($(this).attr('data-sort') < -$('#address .form-group').length) {
            $('#address .form-group:first').before(this);
        }
    });

    $('input[name=\'customer_group_id\']').on('change', function () {
        $.ajax({
            url: 'index.php?route=account/register/customfield&customer_group_id=' + this.value,
            dataType: 'json',
            success: function (json) {
                $('.custom-field').hide();
                $('.custom-field').removeClass('required');

                for (i = 0; i < json.length; i++) {
                    custom_field = json[i];

                    $('#custom-field' + custom_field['custom_field_id']).show();

                    if (custom_field['required']) {
                        $('#custom-field' + custom_field['custom_field_id']).addClass('required');
                    }
                }


            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('input[name=\'customer_group_id\']:checked').trigger('change');
</script>
<?php include(__DIR__ . '/_custom_field_upload.tpl'); ?>
<script>
    $('select[name=\'country_id\']').on('change', function () {
        $.ajax({
            url: 'index.php?route=account/account/country&country_id=' + this.value,
            dataType: 'json',
            beforeSend: function () {
                $('select[name=\'country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
            },
            complete: function () {
                $('.fa-spin').remove();
            },
            success: function (json) {
                if (json['postcode_required'] == '1') {
                    $('input[name=\'postcode\']').parent().parent().addClass('required');
                } else {
                    $('input[name=\'postcode\']').parent().parent().removeClass('required');
                }

                html = '<option value=""><?php echo $text_select; ?></option>';

                if (json['zone'] && json['zone'] != '') {
                    for (i = 0; i < json['zone'].length; i++) {
                        html += '<option value="' + json['zone'][i]['zone_id'] + '"';

                        if (json['zone'][i]['zone_id'] == '<?php echo $zone_id; ?>') {
                            html += ' selected="selected"';
                        }

                        html += '>' + json['zone'][i]['name'] + '</option>';
                    }
                } else {
                    html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
                }

                $('select[name=\'zone_id\']').html(html);
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    $('select[name=\'country_id\']').trigger('change');
</script>
<?php echo $footer; ?>
