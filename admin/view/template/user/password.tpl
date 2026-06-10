<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-user" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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

      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-user" class="form-horizontal">



          <div class="form-group required">
            <label class="col-sm-2 control-label" for="old_password"><?php echo $label_old_password; ?></label>
            <div class="col-sm-10">
              <input type="password" name="old_password" value="" placeholder="<?php echo $label_old_password; ?>" id="oldpassword" class="form-control" />
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="newpwd"><?php echo $label_new_password; ?></label>
            <div class="col-sm-10">
              <input type="password" name="new_password" value="" placeholder="<?php echo $label_new_password; ?>" id="newpwd" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label required" for="new_password_confirm"><?php echo $label_new_password_confirm; ?></label>
            <div class="col-sm-10">
              <input type="password" name="new_password_confirm" value="" placeholder="<?php echo $label_new_password_confirm; ?>" id="new_password_confirm" class="form-control" />
            </div>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>