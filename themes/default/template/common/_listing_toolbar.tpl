<div class="cp-toolbar">
  <a href="<?php echo $compare; ?>" id="compare-total" class="cp-toolbar__compare"><?php echo $text_compare; ?></a>
  <div class="cp-toolbar__selects">
    <label><?php echo $text_sort; ?>
      <select id="input-sort" class="form-select form-select-sm" onchange="location = this.value;">
        <?php foreach ($sorts as $s) { ?>
          <option value="<?php echo $s['href']; ?>"<?php if ($s['value'] == $sort . '-' . $order) echo ' selected'; ?>><?php echo $s['text']; ?></option>
        <?php } ?>
      </select>
    </label>
    <label><?php echo $text_limit; ?>
      <select id="input-limit" class="form-select form-select-sm" onchange="location = this.value;">
        <?php foreach ($limits as $l) { ?>
          <option value="<?php echo $l['href']; ?>"<?php if ($l['value'] == $limit) echo ' selected'; ?>><?php echo $l['text']; ?></option>
        <?php } ?>
      </select>
    </label>
  </div>
</div>
