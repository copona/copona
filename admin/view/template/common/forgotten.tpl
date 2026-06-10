<?php echo $header; ?>
<div id="content">
  <div class="container-fluid"><br />
    <br />
    <div class="row">
      <div class="col-sm-offset-4 col-sm-4">
        <div class="card">
          <div class="card-header">
            <h1 class="card-title"><i class="fa fa-repeat"></i> <?php echo $heading_title; ?></h1>
          </div>
          <div class="card-body">
              <?php if ($error_warning) { ?>
                <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
                  <button type="button" class="close" data-bs-dismiss="alert">&times;</button>
                </div>
            <?php } ?>
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
              <div class="form-group">
                <label for="input-email"><?php echo $entry_email; ?></label>
                <div class="input-group"><span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                  <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                </div>
              </div>
              <div class="text-end">
                <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> <?php echo $button_reset; ?></button>
                <a href="<?php echo $cancel; ?>" data-bs-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-secondary"><i class="fa fa-reply"></i></a>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>