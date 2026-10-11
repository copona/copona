<?php echo $header; ?>
<?php include(__DIR__ . '/../common/_page_open.tpl'); ?>
      <h1><?php echo $heading_title; ?></h1>
      <p class="cp-stat"><?php echo $text_total; ?> <strong><?php echo $total; ?></strong></p>
      <div class="cp-table-wrap">
        <table class="table cp-table">
          <thead>
            <tr>
              <th><?php echo $column_date_added; ?></th>
              <th><?php echo $column_description; ?></th>
              <th class="text-end"><?php echo $column_amount; ?></th>
            </tr>
          </thead>
          <tbody>
            <?php if ($transactions) { ?>
              <?php foreach ($transactions as $transaction) { ?>
                <tr>
                  <td><?php echo $transaction['date_added']; ?></td>
                  <td><?php echo $transaction['description']; ?></td>
                  <td class="text-end cp-table__strong"><?php echo $transaction['amount']; ?></td>
                </tr>
              <?php } ?>
            <?php } else { ?>
              <tr><td class="cp-table__empty" colspan="3"><?php echo $text_empty; ?></td></tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <?php include(__DIR__ . '/../common/_pagination.tpl'); ?>
      <div class="cp-actions">
        <a href="<?php echo $continue; ?>" class="btn btn-outline-secondary"><?php echo $button_continue; ?></a>
      </div>
<?php include(__DIR__ . '/../common/_page_close.tpl'); ?>
<?php echo $footer; ?>
