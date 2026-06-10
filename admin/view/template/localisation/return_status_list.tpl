<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="float-end"><a href="<?php echo $add; ?>" data-bs-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-bs-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger" onclick="confirm('<?php echo $text_confirm; ?>') ? $('#form-return-status').submit() : false;"><i class="fa fa-trash-o"></i></button>
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
        <h3 class="card-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="card-body">
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-return-status">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-start"><?php if ($sort == 'name') { ?>
                        <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                        <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?></td>
                  <td class="text-end"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                  <?php if ($return_statuses) { ?>
                      <?php foreach ($return_statuses as $return_status) { ?>
                        <tr>
                          <td class="text-center"><?php if (in_array($return_status['return_status_id'], $selected)) { ?>
                                <input type="checkbox" name="selected[]" value="<?php echo $return_status['return_status_id']; ?>" checked="checked" />
                            <?php } else { ?>
                                <input type="checkbox" name="selected[]" value="<?php echo $return_status['return_status_id']; ?>" />
                            <?php } ?></td>
                          <td class="text-start"><?php echo $return_status['name']; ?></td>
                          <td class="text-end"><a href="<?php echo $return_status['edit']; ?>" data-bs-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                      <td class="text-center" colspan="3"><?php echo $text_no_results; ?></td>
                    </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-start"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-end"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>