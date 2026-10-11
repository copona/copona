<?php echo $header; ?>
<?php include(__DIR__ . '/../common/_page_open.tpl'); ?>
      <h1><?php echo $heading_title; ?></h1>
      <?php if ($recurrings) { ?>
        <div class="cp-table-wrap">
          <table class="table cp-table">
            <thead>
              <tr>
                <th><?php echo $column_order_recurring_id; ?></th>
                <th><?php echo $column_product; ?></th>
                <th><?php echo $column_status; ?></th>
                <th><?php echo $column_date_added; ?></th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($recurrings as $recurring) { ?>
                <tr>
                  <td class="cp-table__strong">#<?php echo $recurring['order_recurring_id']; ?></td>
                  <td><?php echo $recurring['product']; ?></td>
                  <td><span class="cp-status"><?php echo $recurring['status']; ?></span></td>
                  <td><?php echo $recurring['date_added']; ?></td>
                  <td class="text-end"><a href="<?php echo $recurring['view']; ?>" class="btn btn-sm btn-outline-secondary"><?php echo $button_view; ?></a></td>
                </tr>
              <?php } ?>
            </tbody>
          </table>
        </div>
        <?php include(__DIR__ . '/../common/_pagination.tpl'); ?>
      <?php } else { ?>
        <p class="cp-empty"><?php echo $text_empty; ?></p>
      <?php } ?>
      <div class="cp-actions">
        <a href="<?php echo $continue; ?>" class="btn btn-outline-secondary"><?php echo $button_continue; ?></a>
      </div>
<?php include(__DIR__ . '/../common/_page_close.tpl'); ?>
<?php echo $footer; ?>
