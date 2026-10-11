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
              <th class="text-end"><?php echo $column_points; ?></th>
            </tr>
          </thead>
          <tbody>
            <?php if ($rewards) { ?>
              <?php foreach ($rewards as $reward) { ?>
                <tr>
                  <td><?php echo $reward['date_added']; ?></td>
                  <td><?php if ($reward['order_id']) { ?><a href="<?php echo $reward['href']; ?>"><?php echo $reward['description']; ?></a><?php } else { ?><?php echo $reward['description']; ?><?php } ?></td>
                  <td class="text-end cp-table__strong"><?php echo $reward['points']; ?></td>
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
