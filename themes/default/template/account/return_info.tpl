<?php echo $header; ?>
<?php include(__DIR__ . '/../common/_page_open.tpl'); ?>
      <h1><?php echo $heading_title; ?></h1>
      <div class="cp-summary">
        <?php
        $facts_title = $text_return_detail;
        $facts = [
            [$text_return_id, '#' . $return_id],
            [$text_date_added, $date_added],
            [$text_order_id, '#' . $order_id],
            [$text_date_ordered, $date_ordered],
        ];
        include(__DIR__ . '/../common/_facts.tpl');
        $facts_title = $text_product;
        $facts = [
            [$column_product, $product],
            [$column_model, $model],
            [$column_quantity, $quantity],
        ];
        include(__DIR__ . '/../common/_facts.tpl');
        $facts_title = $text_reason;
        $facts = [
            [$column_reason, $reason],
            [$column_opened, $opened],
            [$column_action, $action],
        ];
        include(__DIR__ . '/../common/_facts.tpl');
        ?>
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
