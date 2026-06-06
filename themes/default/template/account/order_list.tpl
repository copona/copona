<?php echo $header; ?>
<div class="container">
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li class="breadcrumb-item"><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ol></nav>
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
      <?php if ($orders) { ?>
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td class="text-end"><?php echo $column_order_id; ?></td>
                  <td class="text-start"><?php echo $column_customer; ?></td>
                  <td class="text-end"><?php echo $column_product; ?></td>
                  <td class="text-start"><?php echo $column_status; ?></td>
                  <td class="text-end"><?php echo $column_total; ?></td>
                  <td class="text-start"><?php echo $column_date_added; ?></td>
                  <td></td>
                </tr>
              </thead>
              <tbody>
                  <?php foreach ($orders as $order) { ?>
                    <tr>
                      <td class="text-end">#<?php echo $order['order_id']; ?></td>
                      <td class="text-start"><?php echo $order['name']; ?></td>
                      <td class="text-end"><?php echo $order['products']; ?></td>
                      <td class="text-start"><?php echo $order['status']; ?></td>
                      <td class="text-end"><?php echo $order['total']; ?></td>
                      <td class="text-start"><?php echo $order['date_added']; ?></td>
                      <td class="text-end"><a href="<?php echo $order['view']; ?>" data-bs-toggle="tooltip" title="<?php echo $button_view; ?>" class="btn btn-info"><i class="fa fa-eye"></i></a></td>
                    </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
          <div class="row">
            <div class="col-sm-7 text-start"><?php echo $pagination; ?></div>
            <div class="col-sm-5 text-end"><?php echo $results; ?></div>
          </div>
      <?php } else { ?>
          <p><?php echo $text_empty; ?></p>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
