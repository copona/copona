<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="float-end">
        <button type="submit" form="form-backup" data-bs-toggle="tooltip" title="<?php echo $button_export; ?>" class="btn btn-secondary"><i class="fa fa-download"></i></button>
        <button type="submit" form="form-restore" data-bs-toggle="tooltip" title="<?php echo $button_import; ?>" class="btn btn-secondary"><i class="fa fa-upload"></i></button>
      </div>
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
    <?php if ($success) { ?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
          <button type="button" form="form-backup" class="close" data-bs-dismiss="alert">&times;</button>
        </div>
    <?php } ?>
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="fa fa-exchange"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="card-body">
        <form action="<?php echo $restore; ?>" method="post" enctype="multipart/form-data" id="form-restore" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-import"><?php echo $entry_import; ?></label>
            <div class="col-sm-10">
              <input type="file" name="import" id="input-import" />
            </div>
          </div>
        </form>
        <form action="<?php echo $backup; ?>" method="post" enctype="multipart/form-data" id="form-backup" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_export; ?></label>
            <div class="col-sm-10">
              <div class="well well-sm" style="height: 150px; overflow: auto;">
                  <?php foreach ($tables as $table) { ?>
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" name="backup[]" value="<?php echo $table; ?>" checked="checked" />
                        <?php echo $table; ?></label>
                    </div>
                <?php } ?>
              </div>
              <a onclick="$(this).parent().find(':checkbox').prop('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').prop('checked', false);"><?php echo $text_unselect_all; ?></a></div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
