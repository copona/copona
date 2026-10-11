<?php echo $header; ?>
<?php include(__DIR__ . '/../common/_page_open.tpl'); ?>
      <h1><?php echo $heading_title; ?></h1>
      <div class="cp-summary">
        <?php
        $facts_title = $text_recurring_detail;
        $facts = [
            [$text_order_recurring_id, '#' . $order_recurring_id],
            [$text_date_added, $date_added],
            [$text_status, $status],
            [$text_payment_method, $payment_method],
        ];
        include(__DIR__ . '/../common/_facts.tpl');
        $facts = [
            [$text_order_id, '<a href="' . $order . '">#' . $order_id . '</a>'],
            [$text_product, '<a href="' . $product . '">' . $product_name . '</a>'],
            [$text_quantity, $product_quantity],
            [$text_description, $recurring_description],
            [$text_reference, $reference],
        ];
        include(__DIR__ . '/../common/_facts.tpl');
        ?>
      </div>
      <h2 class="cp-section__title"><?php echo $text_transaction; ?></h2>
      <div class="cp-table-wrap">
        <table class="table cp-table">
          <thead>
            <tr>
              <th><?php echo $column_date_added; ?></th>
              <th><?php echo $column_type; ?></th>
              <th class="text-end"><?php echo $column_amount; ?></th>
            </tr>
          </thead>
          <tbody>
            <?php if ($transactions) { ?>
              <?php foreach ($transactions as $transaction) { ?>
                <tr>
                  <td><?php echo $transaction['date_added']; ?></td>
                  <td><?php echo $transaction['type']; ?></td>
                  <td class="text-end cp-table__strong"><?php echo $transaction['amount']; ?></td>
                </tr>
              <?php } ?>
            <?php } else { ?>
              <tr><td colspan="3" class="cp-table__empty"><?php echo $text_no_results; ?></td></tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
      <?php echo $recurring; ?>
<?php include(__DIR__ . '/../common/_page_close.tpl'); ?>
<?php echo $footer; ?>
