<?php echo $header; ?>
<?php include(__DIR__ . '/../common/_page_open.tpl'); ?>
      <h1><?php echo $heading_title; ?></h1>
      <div class="cp-summary">
        <?php
        $facts_title = $text_order_detail;
        $facts = [
            [$text_order_id, '#' . $order_id],
            [$text_invoice_no, $invoice_no],
            [$text_date_added, $date_added],
            [$text_payment_method, $payment_method],
            [$text_shipping_method, $shipping_method],
        ];
        include(__DIR__ . '/../common/_facts.tpl');
        ?>
        <section class="cp-panel cp-info">
          <h2 class="cp-info__title"><?php echo $text_payment_address; ?></h2>
          <address><?php echo $payment_address; ?></address>
        </section>
        <?php if ($shipping_address) { ?>
          <section class="cp-panel cp-info">
            <h2 class="cp-info__title"><?php echo $text_shipping_address; ?></h2>
            <address><?php echo $shipping_address; ?></address>
          </section>
        <?php } ?>
      </div>
      <div class="cp-table-wrap">
        <table class="table cp-table">
          <thead>
            <tr>
              <th><?php echo $column_name; ?></th>
              <th><?php echo $column_model; ?></th>
              <th class="text-end"><?php echo $column_quantity; ?></th>
              <th class="text-end"><?php echo $column_price; ?></th>
              <th class="text-end"><?php echo $column_total; ?></th>
              <?php if ($products) { ?>
                <th></th>
              <?php } ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($products as $product) { ?>
              <tr>
                <td>
                  <span class="cp-table__strong"><?php echo $product['name']; ?></span>
                  <?php foreach ($product['option'] as $option) { ?>
                    <div class="cp-table__note"><?php echo $option['name']; ?>: <?php echo $option['value']; ?></div>
                  <?php } ?>
                </td>
                <td><?php echo $product['model']; ?></td>
                <td class="text-end"><?php echo $product['quantity']; ?></td>
                <td class="text-end"><?php echo $product['price']; ?></td>
                <td class="text-end cp-table__strong"><?php echo $product['total']; ?></td>
                <td class="text-end text-nowrap">
                  <?php if ($product['reorder']) { ?>
                    <a href="<?php echo $product['reorder']; ?>" class="cp-icon-btn" title="<?php echo $button_reorder; ?>" aria-label="<?php echo $button_reorder; ?>"><i class="fa fa-shopping-cart"></i></a>
                  <?php } ?>
                  <a href="<?php echo $product['return']; ?>" class="cp-icon-btn" title="<?php echo $button_return; ?>" aria-label="<?php echo $button_return; ?>"><i class="fa fa-reply"></i></a>
                </td>
              </tr>
            <?php } ?>
            <?php foreach ($vouchers as $voucher) { ?>
              <tr>
                <td class="cp-table__strong"><?php echo $voucher['description']; ?></td>
                <td></td>
                <td class="text-end">1</td>
                <td class="text-end"><?php echo $voucher['amount']; ?></td>
                <td class="text-end cp-table__strong"><?php echo $voucher['amount']; ?></td>
                <?php if ($products) { ?>
                  <td></td>
                <?php } ?>
              </tr>
            <?php } ?>
          </tbody>
          <tfoot>
            <?php foreach ($totals as $total) { ?>
              <tr class="cp-table__total">
                <td colspan="4" class="text-end"><?php echo $total['title']; ?></td>
                <td class="text-end"><?php echo $total['text']; ?></td>
                <?php if ($products) { ?>
                  <td></td>
                <?php } ?>
              </tr>
            <?php } ?>
          </tfoot>
        </table>
      </div>
      <?php if ($comment) { ?>
        <section class="cp-panel cp-note">
          <h2 class="cp-info__title"><?php echo $text_comment; ?></h2>
          <p><?php echo $comment; ?></p>
        </section>
      <?php } ?>
      <h2 class="cp-section__title"><?php echo $text_history; ?></h2>
      <?php $history_rows = $histories; include(__DIR__ . '/_history.tpl'); ?>
      <div class="cp-actions">
        <a href="<?php echo $continue; ?>" class="btn btn-outline-secondary"><?php echo $button_continue; ?></a>
      </div>
<?php include(__DIR__ . '/../common/_page_close.tpl'); ?>
<?php echo $footer; ?>
