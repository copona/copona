<?php
// Status history timeline (order and return pages). Expects $history_rows.
?>
<?php if ($history_rows) { ?>
  <ol class="cp-timeline">
    <?php foreach ($history_rows as $history) { ?>
      <li class="cp-timeline__item">
        <div class="cp-timeline__head">
          <span class="cp-status"><?php echo $history['status']; ?></span>
          <time class="cp-timeline__date"><?php echo $history['date_added']; ?></time>
        </div>
        <?php if ($history['comment']) { ?>
          <div class="cp-timeline__comment"><?php echo $history['comment']; ?></div>
        <?php } ?>
      </li>
    <?php } ?>
  </ol>
<?php } else { ?>
  <p class="cp-empty"><?php echo $text_no_results; ?></p>
<?php } ?>
