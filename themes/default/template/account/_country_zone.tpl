<?php
// Country + region selects. The zone list is filled by the page's country_id change handler.
?>
<div class="form-group cp-field required">
  <label class="form-label" for="input-country"><?php echo $entry_country; ?></label>
  <div>
    <select name="country_id" id="input-country" class="form-select" autocomplete="country">
      <option value=""><?php echo $text_select; ?></option>
      <?php foreach ($countries as $country) { ?>
        <option value="<?php echo $country['country_id']; ?>"<?php echo $country['country_id'] == $country_id ? ' selected="selected"' : ''; ?>><?php echo $country['name']; ?></option>
      <?php } ?>
    </select>
    <?php if ($error_country) { ?>
      <div class="text-danger"><?php echo $error_country; ?></div>
    <?php } ?>
  </div>
</div>
<div class="form-group cp-field required">
  <label class="form-label" for="input-zone"><?php echo $entry_zone; ?></label>
  <div>
    <select name="zone_id" id="input-zone" class="form-select"></select>
    <?php if ($error_zone) { ?>
      <div class="text-danger"><?php echo $error_zone; ?></div>
    <?php } ?>
  </div>
</div>
