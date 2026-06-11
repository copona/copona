<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="float-end">
        <button type="submit" form="form-tax-rate" data-bs-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-bs-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-secondary"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
          <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
      <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
          <button type="button" class="close" data-bs-dismiss="alert">&times;</button>
        </div>
    <?php } ?>
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="card-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-tax-rate" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
                <?php foreach ($languages as $language) { ?>
                  <div class="input-group"><span class="input-group-addon lng-image"><img src="<?= HTTP_CATALOG ?>catalog/language/<?php echo $language['directory']; ?>/<?php echo $language['directory']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                    <input type="text" name="tax_rate_description[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($tax_rate_description[$language['language_id']]) ? $tax_rate_description[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" class="form-control" />
                  </div>
                  <?php if (isset($error_name[$language['language_id']])) { ?>
                    <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
                  <?php } ?>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-rate"><?php echo $entry_rate; ?></label>
            <div class="col-sm-10">
              <input type="text" name="rate" value="<?php echo $rate; ?>" placeholder="<?php echo $entry_rate; ?>" id="input-rate" class="form-control" />
              <?php if ($error_rate) { ?>
                  <div class="text-danger"><?php echo $error_rate; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-type"><?php echo $entry_type; ?></label>
            <div class="col-sm-10">
              <select name="type" id="input-type" class="form-control">
                  <?php if ($type == 'P') { ?>
                    <option value="P" selected="selected"><?php echo $text_percent; ?></option>
                <?php } else { ?>
                    <option value="P"><?php echo $text_percent; ?></option>
                <?php } ?>
                <?php if ($type == 'F') { ?>
                    <option value="F" selected="selected"><?php echo $text_amount; ?></option>
                <?php } else { ?>
                    <option value="F"><?php echo $text_amount; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_customer_group; ?></label>
            <div class="col-sm-10">
                <?php foreach ($customer_groups as $customer_group) { ?>
                  <div class="checkbox">
                    <label>
                        <?php if (in_array($customer_group['customer_group_id'], $tax_rate_customer_group)) { ?>
                          <input type="checkbox" name="tax_rate_customer_group[]" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked" />
                          <?php echo $customer_group['name']; ?>
                      <?php } else { ?>
                          <input type="checkbox" name="tax_rate_customer_group[]" value="<?php echo $customer_group['customer_group_id']; ?>" />
                          <?php echo $customer_group['name']; ?>
                      <?php } ?>
                    </label>
                  </div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-geo-zone"><?php echo $entry_geo_zone; ?></label>
            <div class="col-sm-10">
              <select name="geo_zone_id" id="input-geo-zone" class="form-control">
                <?php foreach ($geo_zones as $geo_zone) { ?>
                    <?php if ($geo_zone['geo_zone_id'] == $geo_zone_id) { ?>
                        <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected"><?php echo $geo_zone['name']; ?></option>
                    <?php } else { ?>
                        <option value="<?php echo $geo_zone['geo_zone_id']; ?>"><?php echo $geo_zone['name']; ?></option>
                    <?php } ?>
                <?php } ?>
              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>