<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="float-end"><a onclick="confirm('<?php echo $text_confirm; ?>') ? location.href = '<?php echo $reset; ?>' : false;" data-bs-toggle="tooltip" title="<?php echo $button_reset; ?>" class="btn btn-danger"><i class="fa fa-refresh"></i></a></div>
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
        <h3 class="card-title"><i class="fa fa-bar-chart"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
              <tr>
                <td class="text-start"><?php echo $column_name; ?></td>
                <td class="text-start"><?php echo $column_model; ?></td>
                <td class="text-end"><?php echo $column_viewed; ?></td>
                <td class="text-end"><?php echo $column_percent; ?></td>
              </tr>
            </thead>
            <tbody>
                <?php if ($products) { ?>
                    <?php foreach ($products as $product) { ?>
                      <tr>
                        <td class="text-start"><?php echo $product['name']; ?></td>
                        <td class="text-start"><?php echo $product['model']; ?></td>
                        <td class="text-end"><?php echo $product['viewed']; ?></td>
                        <td class="text-end"><?php echo $product['percent']; ?></td>
                      </tr>
                  <?php } ?>
              <?php } else { ?>
                  <tr>
                    <td class="text-center" colspan="4"><?php echo $text_no_results; ?></td>
                  </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <div class="row">
          <div class="col-sm-6 text-start"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-end"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>