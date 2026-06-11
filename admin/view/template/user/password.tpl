<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="float-end">
        <button type="submit" form="form-password" data-bs-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-bs-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-secondary"><i class="fa fa-reply"></i></a>
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
        <button type="button" class="close" data-bs-dismiss="alert">&times;</button>
      </div>
    <?php } ?>
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="fa fa-key"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="card-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-password" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="old_password"><?php echo $entry_old_password; ?></label>
            <div class="col-sm-10">
              <input type="password" name="old_password" value="" placeholder="<?php echo $entry_old_password; ?>" id="old_password" class="form-control" autocomplete="current-password" />
              <?php if ($error_old_password) { ?>
                <div class="text-danger"><?php echo $error_old_password; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="new_password"><?php echo $entry_new_password; ?></label>
            <div class="col-sm-10">
              <input type="password" name="new_password" value="" placeholder="<?php echo $entry_new_password; ?>" id="new_password" class="form-control" autocomplete="new-password" />
              <?php if ($error_new_password) { ?>
                <div class="text-danger"><?php echo $error_new_password; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="new_password_confirm"><?php echo $entry_confirm; ?></label>
            <div class="col-sm-10">
              <input type="password" name="new_password_confirm" value="" placeholder="<?php echo $entry_confirm; ?>" id="new_password_confirm" class="form-control" autocomplete="new-password" />
              <?php if ($error_confirm) { ?>
                <div class="text-danger"><?php echo $error_confirm; ?></div>
              <?php } ?>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>
