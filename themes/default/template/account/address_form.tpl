<?php echo $header; ?>
<?php include(__DIR__ . '/../common/_page_open.tpl'); ?>
      <h1><?php echo $text_edit_address; ?></h1>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="cp-form">
        <fieldset class="cp-fieldset">
          <div class="cp-fields">
            <?php $field = ['name' => 'firstname', 'label' => $entry_firstname, 'value' => $firstname, 'id' => 'input-firstname', 'error' => $error_firstname, 'required' => true, 'autocomplete' => 'given-name']; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $field = ['name' => 'lastname', 'label' => $entry_lastname, 'value' => $lastname, 'id' => 'input-lastname', 'error' => $error_lastname, 'required' => true, 'autocomplete' => 'family-name']; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $field = ['name' => 'company', 'label' => $entry_company, 'value' => $company, 'id' => 'input-company', 'autocomplete' => 'organization']; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $field = ['name' => 'address_1', 'label' => $entry_address_1, 'value' => $address_1, 'id' => 'input-address-1', 'error' => $error_address_1, 'required' => true, 'autocomplete' => 'address-line1']; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $field = ['name' => 'address_2', 'label' => $entry_address_2, 'value' => $address_2, 'id' => 'input-address-2', 'autocomplete' => 'address-line2']; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $field = ['name' => 'city', 'label' => $entry_city, 'value' => $city, 'id' => 'input-city', 'error' => $error_city, 'required' => true, 'autocomplete' => 'address-level2']; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php $field = ['name' => 'postcode', 'label' => $entry_postcode, 'value' => $postcode, 'id' => 'input-postcode', 'error' => $error_postcode, 'required' => true, 'autocomplete' => 'postal-code']; include(__DIR__ . '/../common/_field.tpl'); ?>
            <?php include(__DIR__ . '/_country_zone.tpl'); ?>
            <?php $cf_prefix = 'custom_field'; $cf_values = $address_custom_field; ?>
            <?php foreach ($custom_fields as $custom_field) { if ($custom_field['location'] == 'address') include(__DIR__ . '/_custom_field.tpl'); } ?>
            <?php $yes_no = ['name' => 'default', 'label' => $entry_default, 'value' => $default]; include(__DIR__ . '/../common/_yes_no.tpl'); ?>
          </div>
        </fieldset>
        <div class="cp-actions">
          <a href="<?php echo $back; ?>" class="btn btn-outline-secondary"><?php echo $button_back; ?></a>
          <button type="submit" class="btn btn-primary"><?php echo $button_continue; ?></button>
        </div>
      </form>
<?php include(__DIR__ . '/../common/_page_close.tpl'); ?>
<script>
// Sort the custom fields
    $('.form-group[data-sort]').detach().each(function () {
        if ($(this).attr('data-sort') >= 0 && $(this).attr('data-sort') <= $('.form-group').length - 2) {
            $('.form-group').eq(parseInt($(this).attr('data-sort')) + 2).before(this);
        }

        if ($(this).attr('data-sort') > $('.form-group').length - 2) {
            $('.form-group:last').after(this);
        }

        if ($(this).attr('data-sort') == $('.form-group').length - 2) {
            $('.form-group:last').after(this);
        }

        if ($(this).attr('data-sort') < -$('.form-group').length - 2) {
            $('.form-group:first').before(this);
        }
    });
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
