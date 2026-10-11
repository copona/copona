<?php
// A labelled form input. Expects $field = [
//   'name', 'label', 'value', 'id',
//   'type' => 'text', 'error' => '', 'required' => false, 'wide' => false, 'autocomplete' => '', 'help' => '',
// ]
$field += ['type' => 'text', 'error' => '', 'required' => false, 'wide' => false, 'autocomplete' => '', 'value' => '', 'help' => ''];
?>
<div class="form-group cp-field<?php echo $field['required'] ? ' required' : ''; ?><?php echo $field['wide'] ? ' cp-field--wide' : ''; ?>">
  <label class="form-label" for="<?php echo $field['id']; ?>"><?php echo $field['label']; ?></label>
  <div>
    <?php if ($field['type'] == 'textarea') { ?>
      <textarea name="<?php echo $field['name']; ?>" rows="8" id="<?php echo $field['id']; ?>" class="form-control"><?php echo $field['value']; ?></textarea>
    <?php } else { ?>
      <input type="<?php echo $field['type']; ?>" name="<?php echo $field['name']; ?>" value="<?php echo $field['value']; ?>" id="<?php echo $field['id']; ?>" class="form-control"<?php echo $field['autocomplete'] ? ' autocomplete="' . $field['autocomplete'] . '"' : ''; ?> />
    <?php } ?>
    <?php if ($field['help']) { ?>
      <div class="form-text"><?php echo $field['help']; ?></div>
    <?php } ?>
    <?php if ($field['error']) { ?>
      <div class="text-danger"><?php echo $field['error']; ?></div>
    <?php } ?>
  </div>
</div>
