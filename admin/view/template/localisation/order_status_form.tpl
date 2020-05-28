<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-order-status" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-order-status" class="form-horizontal">
          <ul class="nav nav-tabs" id="language">
              <?php foreach ($languages as $language) { ?>
                <li>
                  <a href="#language<?php echo $language['language_id']; ?>" data-toggle="tab">
                    <img src="<?= HTTP_CATALOG ?>catalog/language/<?php echo $language['directory']; ?>/<?php echo $language['directory']; ?>.png"
                         title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?>
                  </a>
                </li>
            <?php } ?>
          </ul>
          <div class="tab-content">
              <?php foreach ($languages as $language) { ?>
                <div class="tab-pane" id="language<?php echo $language['language_id']; ?>">
                  <div class="form-group required">
                    <label class="col-sm-2 control-label"><?php echo $entry_name; ?></label>
                    <div class="col-sm-10">
                      <div class="input-group"><span class="input-group-addon lng-image"><img src="<?= HTTP_CATALOG ?>catalog/language/<?php echo $language['directory']; ?>/<?php echo $language['directory']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                        <input type="text" name="order_status[<?php echo $language['language_id']; ?>][name]" value="<?php echo isset($order_status[$language['language_id']]) ? $order_status[$language['language_id']]['name'] : ''; ?>" placeholder="<?php echo $entry_name; ?>" class="form-control" />
                      </div>
                      <?php if (isset($error_name[$language['language_id']])) { ?>
                          <div class="text-danger"><?php echo $error_name[$language['language_id']]; ?></div>
                      <?php } ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label class="col-sm-2 control-label"><?php echo $entry_description; ?></label>
                    <div class="col-sm-10">
                      <div class="input-group"><span class="input-group-addon lng-image"><img src="<?= HTTP_CATALOG ?>catalog/language/<?php echo $language['directory']; ?>/<?php echo $language['directory']; ?>.png" title="<?php echo $language['name']; ?>" /></span>
                        <textarea name="order_status[<?php echo $language['language_id']; ?>][description]" placeholder="<?php echo $entry_description; ?>" id="input-description<?php echo $language['language_id']; ?>" class="ck-basic form-control "><?php echo isset($order_status[$language['language_id']]) ? $order_status[$language['language_id']]['description'] : ''; ?></textarea>
                      </div>
                    </div>
                  </div>
                </div>
            <?php } ?>
            <div class="form-group">
              <label class="col-sm-2 control-label"><?php echo $entry_send_invoice; ?></label>
              <div class="col-sm-10">
                <label class="radio-inline">
                    <?php if ($send_invoice) { ?>
                      <input type="radio" name="send_invoice" value="1" checked="checked" />
                      <?php echo $text_yes; ?>
                  <?php } else { ?>
                      <input type="radio" name="send_invoice" value="1" />
                      <?php echo $text_yes; ?>
                  <?php } ?>
                </label>
                <label class="radio-inline">
                  <?php if (!$send_invoice) { ?>
                      <input type="radio" name="send_invoice" value="0" checked="checked" />
                      <?php echo $text_no; ?>
                  <?php } else { ?>
                      <input type="radio" name="send_invoice" value="0" />
                      <?php echo $text_no; ?>
                  <?php } ?>
                </label>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>