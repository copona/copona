<?php echo $header; ?>
<div class="container">
  <ul class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <?php if ($attention) { ?>
      <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $attention; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
  <?php } ?>
  <?php if ($success) { ?>
      <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
  <?php } ?>
  <?php if ($error_warning) { ?>
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
        <button type="button" class="close" data-dismiss="alert">&times;</button>
      </div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
      <?php if ($column_left && $column_right) { ?>
          <?php $class = 'col-sm-6'; ?>
      <?php } elseif ($column_left || $column_right) { ?>
          <?php $class = 'col-sm-9'; ?>
      <?php } else { ?>
          <?php $class = 'col-sm-12'; ?>
      <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1><?php echo $heading_title; ?>
          <?php if ($weight) { ?>
            &nbsp;(<?php echo $weight; ?>)
        <?php } ?>
      </h1>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <div id="mycart"></div>
      </form>
      <?php if ($modules) { ?>
          <h2><?php echo $text_next; ?></h2>
          <p><?php echo $text_next_choice; ?></p>
          <div class="panel-group" id="accordion">
              <?php foreach ($modules as $module) { ?>
                  <?php echo $module; ?>
              <?php } ?>
          </div>
      <?php } ?>
      <br />
      <div class="row">
        <div class="col-sm-4 col-sm-offset-8">
          <table class="table table-bordered">
              <?php foreach ($totals as $total) { ?>
                <tr>
                  <td class="text-right"><strong><?php echo $total['title']; ?>:</strong></td>
                  <td class="text-right"><?php echo $total['text']; ?></td>
                </tr>
            <?php } ?>
          </table>
        </div>
      </div>
      <div class="buttons clearfix">
        <div class="pull-left"><a href="<?php echo $continue; ?>" class="btn btn-default"><?php echo $button_shopping; ?></a></div>
        <div class="pull-right"><a href="<?php echo $checkout; ?>" class="btn btn-primary"><?php echo $button_checkout; ?></a></div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script>
    cart.get('#mycart');
</script>
<?php echo $footer; ?>
