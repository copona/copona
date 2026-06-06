<?php echo $header; ?>
<div class="container">
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li class="breadcrumb-item"><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ol></nav>
  <?php if ($success) { ?>
      <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
  <?php } ?>
  <?php if ($error_warning) { ?>
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
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
      <h1><?php echo $heading_title; ?></h1>
      <div class="table-responsive">
        <table class="table table-bordered table-hover">
          <thead>
            <tr>
              <td class="text-start"><?php echo $column_type; ?></td>
              <td class="text-start"><?php echo $column_digits; ?></td>
              <td class="text-end"><?php echo $column_expiry; ?></td>
            </tr>
          </thead>
          <tbody>
              <?php if ($cards) { ?>
                  <?php foreach ($cards as $card) { ?>
                    <tr>
                      <td class="text-start"><?php echo $card['type']; ?></td>
                      <td class="text-start"><?php echo $card['digits']; ?></td>
                      <td class="text-end"><?php echo $card['expiry']; ?></td>
                      <td class="text-end"><a href="<?php echo $delete . $card['card_id']; ?>" class="btn btn-danger"><?php echo $button_delete; ?></a></td>

                    </tr>
                <?php } ?>
            <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="5"><?php echo $text_empty; ?></td>
                </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <div class="row">
        <div class="col-sm-7 text-start"><?php echo $pagination; ?></div>
        <div class="col-sm-5 text-end"><?php echo $results; ?></div>
      </div>
      <div class="buttons clearfix">
        <div class="float-start"><a href="<?php echo $back; ?>" class="btn btn-secondary"><?php echo $button_back; ?></a></div>
        <div class="float-end"><a href="<?php echo $add; ?>" class="btn btn-primary"><?php echo $button_new_card; ?></a></div>
      </div>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
