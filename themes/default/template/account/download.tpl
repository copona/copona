<?php echo $header; ?>
<?php include(__DIR__ . '/../common/_page_open.tpl'); ?>
      <h1><?php echo $heading_title; ?></h1>
      <?php if ($downloads) { ?>
        <div class="cp-table-wrap">
          <table class="table cp-table">
            <thead>
              <tr>
                <th class="text-end"><?php echo $column_order_id; ?></th>
                <th><?php echo $column_name; ?></th>
                <th><?php echo $column_size; ?></th>
                <th><?php echo $column_date_added; ?></th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($downloads as $download) { ?>
                <tr>
                  <td class="text-end">#<?php echo $download['order_id']; ?></td>
                  <td class="cp-table__strong"><?php echo $download['name']; ?></td>
                  <td><?php echo $download['size']; ?></td>
                  <td><?php echo $download['date_added']; ?></td>
                  <td class="text-end"><a href="<?php echo $download['href']; ?>" class="btn btn-sm btn-outline-secondary"><i class="fa fa-download" aria-hidden="true"></i> <?php echo $button_download; ?></a></td>
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
