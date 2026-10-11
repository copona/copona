<?php
// One customer custom field (register, edit, address form).
// Expects $custom_field, $cf_prefix (input name prefix, e.g. "custom_field[account]")
// and $cf_values (submitted/saved values keyed by custom_field_id).
$cf_id = $custom_field['custom_field_id'];
$cf_name = $cf_prefix . '[' . $cf_id . ']';
$cf_value = $cf_values[$cf_id] ?? null;
$cf_input_id = 'input-custom-field' . $cf_id;
$cf_label_for = in_array($custom_field['type'], ['radio', 'checkbox', 'file']) ? '' : ' for="' . $cf_input_id . '"';
$cf_pickers = [
    'date'     => ['date', 'YYYY-MM-DD'],
    'time'     => ['time', 'HH:mm'],
    'datetime' => ['datetime', 'YYYY-MM-DD HH:mm'],
];
?>
<div id="custom-field<?php echo $cf_id; ?>" class="form-group cp-field custom-field<?php echo !empty($custom_field['required']) ? ' required' : ''; ?><?php echo $custom_field['type'] == 'textarea' ? ' cp-field--wide' : ''; ?>" data-sort="<?php echo $custom_field['sort_order']; ?>">
  <label class="form-label"<?php echo $cf_label_for; ?>><?php echo $custom_field['name']; ?></label>
  <div>
    <?php if ($custom_field['type'] == 'select') { ?>
      <select name="<?php echo $cf_name; ?>" id="<?php echo $cf_input_id; ?>" class="form-select">
        <option value=""><?php echo $text_select; ?></option>
        <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
          <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>"<?php echo $cf_value == $custom_field_value['custom_field_value_id'] ? ' selected="selected"' : ''; ?>><?php echo $custom_field_value['name']; ?></option>
        <?php } ?>
      </select>
    <?php } elseif ($custom_field['type'] == 'radio' || $custom_field['type'] == 'checkbox') { ?>
      <?php $cf_multi = $custom_field['type'] == 'checkbox'; ?>
      <div class="cp-choices">
        <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
          <?php $cf_checked = $cf_multi ? in_array($custom_field_value['custom_field_value_id'], (array)$cf_value) : $cf_value == $custom_field_value['custom_field_value_id']; ?>
          <label class="form-check">
            <input type="<?php echo $custom_field['type']; ?>" name="<?php echo $cf_name . ($cf_multi ? '[]' : ''); ?>" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" class="form-check-input"<?php echo $cf_checked ? ' checked="checked"' : ''; ?> />
            <span class="form-check-label"><?php echo $custom_field_value['name']; ?></span>
          </label>
        <?php } ?>
      </div>
    <?php } elseif ($custom_field['type'] == 'textarea') { ?>
      <textarea name="<?php echo $cf_name; ?>" rows="5" placeholder="<?php echo $custom_field['name']; ?>" id="<?php echo $cf_input_id; ?>" class="form-control"><?php echo $cf_value ?? $custom_field['value']; ?></textarea>
    <?php } elseif ($custom_field['type'] == 'file') { ?>
      <button type="button" id="button-custom-field<?php echo $cf_id; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-outline-secondary"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
      <input type="hidden" name="<?php echo $cf_name; ?>" value="<?php echo $cf_value ?? ''; ?>" />
    <?php } elseif (isset($cf_pickers[$custom_field['type']])) { ?>
      <div class="input-group <?php echo $cf_pickers[$custom_field['type']][0]; ?>">
        <input type="text" name="<?php echo $cf_name; ?>" value="<?php echo $cf_value ?? $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="<?php echo $cf_pickers[$custom_field['type']][1]; ?>" id="<?php echo $cf_input_id; ?>" class="form-control" />
        <button type="button" class="btn btn-outline-secondary" aria-label="<?php echo $custom_field['name']; ?>"><i class="fa fa-calendar"></i></button>
      </div>
    <?php } else { ?>
      <input type="text" name="<?php echo $cf_name; ?>" value="<?php echo $cf_value ?? $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" id="<?php echo $cf_input_id; ?>" class="form-control" />
    <?php } ?>
    <?php if (isset($error_custom_field[$cf_id])) { ?>
      <div class="text-danger"><?php echo $error_custom_field[$cf_id]; ?></div>
    <?php } ?>
  </div>
</div>
