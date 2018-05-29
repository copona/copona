<?php
$custom_fields_data = $this->config->get('theme_default_custom_fields1');
?>

<?php foreach ($content_meta as $meta => $val) {
  if(is_string($val) || is_int($val)){ ?>
<div class="form-group">
  <label class="col-sm-2 control-label" for="input-sku"><span data-toggle="tooltip" title="<?php echo $help_content_meta; ?>"><?php echo $meta; ?></span></label>
  <div class="col-sm-10">
    <input type="text" name="content_meta[<?= $meta ?>]" value="<?php echo $val; ?>" placeholder="<?php echo $meta; ?>" id="input-<?= $meta ?>" class="form-control" />
  </div>
</div>

<?php } else { ?>
    <input type="hidden" name="content_meta[<?= $meta ?>]" value="<?php echo $val; ?>" placeholder="<?php echo $meta; ?>" id="input-<?= $meta ?>" class="form-control" />
  <?php }
}
?>