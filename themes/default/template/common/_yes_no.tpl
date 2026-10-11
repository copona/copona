<?php
// Yes/No radio pair. Expects $yes_no = ['name', 'label', 'value'] plus $text_yes / $text_no.
?>
<div class="form-group cp-field">
  <span class="form-label"><?php echo $yes_no['label']; ?></span>
  <div class="cp-choices cp-choices--inline">
    <label class="form-check">
      <input type="radio" name="<?php echo $yes_no['name']; ?>" value="1" class="form-check-input"<?php echo $yes_no['value'] ? ' checked="checked"' : ''; ?> />
      <span class="form-check-label"><?php echo $text_yes; ?></span>
    </label>
    <label class="form-check">
      <input type="radio" name="<?php echo $yes_no['name']; ?>" value="0" class="form-check-input"<?php echo $yes_no['value'] ? '' : ' checked="checked"'; ?> />
      <span class="form-check-label"><?php echo $text_no; ?></span>
    </label>
  </div>
</div>
