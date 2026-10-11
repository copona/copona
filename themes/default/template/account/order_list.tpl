<?php echo $header; ?>
<?php include(__DIR__ . '/../common/_page_open.tpl'); ?>
      <h1><?php echo $heading_title; ?></h1>
      <?php if ($orders) { ?>
        <div class="cp-table-wrap">
          <table class="table cp-table">
            <thead>
              <tr>
                <th><?php echo $column_order_id; ?></th>
                <th><?php echo $column_date_added; ?></th>
                <th><?php echo $column_customer; ?></th>
                <th class="text-end"><?php echo $column_product; ?></th>
                <th><?php echo $column_status; ?></th>
                <th class="text-end"><?php echo $column_total; ?></th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($orders as $order) { ?>
                <tr>
                  <td class="cp-table__strong">#<?php echo $order['order_id']; ?></td>
                  <td><?php echo $order['date_added']; ?></td>
                  <td><?php echo $order['name']; ?></td>
                  <td class="text-end"><?php echo $order['products']; ?></td>
                  <td><span class="cp-status"><?php echo $order['status']; ?></span></td>
                  <td class="text-end cp-table__strong"><?php echo $order['total']; ?></td>
                  <td class="text-end"><a href="<?php echo $order['view']; ?>" class="btn btn-sm btn-outline-secondary"><?php echo $button_view; ?></a></td>
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
