<?php
$custom_fields_data = $this->config->get('theme_default_custom_fields1');
?>
<div class="col-md-12">
  <div class="form-group">
    <label class="col-sm-2 control-label" for="input-subtract">Data:</label>
    <div class="col-sm-10">
        <?= print_r(!$custom_fields_data ? $custom_fields_data : '') ?>
    </div>
  </div>
</div>