<?php echo $header; ?>
<?php include(__DIR__ . '/../common/_page_open.tpl'); ?>
      <h1><?php echo $heading_title; ?></h1>
      <?php if ($returns) { ?>
        <div class="cp-table-wrap">
          <table class="table cp-table">
            <thead>
              <tr>
                <th><?php echo $column_return_id; ?></th>
                <th><?php echo $column_status; ?></th>
                <th><?php echo $column_date_added; ?></th>
                <th><?php echo $column_order_id; ?></th>
                <th><?php echo $column_customer; ?></th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($returns as $return) { ?>
                <tr>
                  <td class="cp-table__strong">#<?php echo $return['return_id']; ?></td>
                  <td><span class="cp-status"><?php echo $return['status']; ?></span></td>
                  <td><?php echo $return['date_added']; ?></td>
                  <td>#<?php echo $return['order_id']; ?></td>
                  <td><?php echo $return['name']; ?></td>
                  <td class="text-end"><a href="<?php echo $return['href']; ?>" class="btn btn-sm btn-outline-secondary"><?php echo $button_view; ?></a></td>
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
