<?php echo $header; ?>
<div class="container">
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <li class="breadcrumb-item"><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ol></nav>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
      <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
      <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
      <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h1 class="mb-3"><?php echo $heading_title; ?></h1>

      <div class="card mb-4">
        <div class="card-body">
          <p class="text-muted small mb-3"><?php echo $entry_search; ?></p>
          <div class="row g-2 mb-2">
            <div class="col-sm-5">
              <input type="text" name="search" value="<?php echo $search; ?>" placeholder="<?php echo $text_keyword; ?>" id="input-search" class="form-control form-control-sm" />
            </div>
            <div class="col-sm-4">
              <select name="category_id" class="form-select form-select-sm">
                <option value="0"><?php echo $text_category; ?></option>
                <?php foreach ($categories as $category_1) { ?>
                  <option value="<?php echo $category_1['category_id']; ?>"<?php if ($category_1['category_id'] == $category_id) echo ' selected'; ?>><?php echo $category_1['name']; ?></option>
                  <?php foreach ($category_1['children'] as $category_2) { ?>
                    <option value="<?php echo $category_2['category_id']; ?>"<?php if ($category_2['category_id'] == $category_id) echo ' selected'; ?>>&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_2['name']; ?></option>
                    <?php foreach ($category_2['children'] as $category_3) { ?>
                      <option value="<?php echo $category_3['category_id']; ?>"<?php if ($category_3['category_id'] == $category_id) echo ' selected'; ?>>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $category_3['name']; ?></option>
                    <?php } ?>
                  <?php } ?>
                <?php } ?>
              </select>
            </div>
            <div class="col-sm-3 d-flex align-items-center">
              <div class="form-check mb-0">
                <input class="form-check-input" type="checkbox" name="sub_category" value="1" id="sub-category"<?php if ($sub_category) echo ' checked'; ?> />
                <label class="form-check-label small" for="sub-category"><?php echo $text_sub_category; ?></label>
              </div>
            </div>
          </div>
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="description" value="1" id="description"<?php if ($description) echo ' checked'; ?> />
            <label class="form-check-label small" for="description"><?php echo $entry_description; ?></label>
          </div>
          <button type="button" id="button-search" class="btn btn-primary btn-sm px-4"><?php echo $button_search; ?></button>
        </div>
      </div>

      <?php if ($products) { ?>
        <h5 class="mb-3"><?php echo $text_search; ?></h5>
        <div class="row g-2 mb-3">
          <div class="col-md-2 col-sm-6 d-none d-sm-block">
            <div class="btn-group btn-group-sm">
              <button type="button" id="list-view" class="btn btn-secondary" data-bs-toggle="tooltip" title="<?php echo $button_list; ?>"><i class="fa fa-th-list"></i></button>
              <button type="button" id="grid-view" class="btn btn-secondary" data-bs-toggle="tooltip" title="<?php echo $button_grid; ?>"><i class="fa fa-th"></i></button>
            </div>
          </div>
          <div class="col-md-3 col-sm-6">
            <a href="<?php echo $compare; ?>" id="compare-total" class="btn btn-link btn-sm"><?php echo $text_compare; ?></a>
          </div>
          <div class="col-md-4 col-6">
            <div class="input-group input-group-sm">
              <label class="input-group-text" for="input-sort"><?php echo $text_sort; ?></label>
              <select id="input-sort" class="form-select form-select-sm" onchange="location = this.value;">
                <?php foreach ($sorts as $sorts) { ?>
                  <option value="<?php echo $sorts['href']; ?>"<?php if ($sorts['value'] == $sort . '-' . $order) echo ' selected'; ?>><?php echo $sorts['text']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
          <div class="col-md-3 col-6">
            <div class="input-group input-group-sm">
              <label class="input-group-text" for="input-limit"><?php echo $text_limit; ?></label>
              <select id="input-limit" class="form-select form-select-sm" onchange="location = this.value;">
                <?php foreach ($limits as $limits) { ?>
                  <option value="<?php echo $limits['href']; ?>"<?php if ($limits['value'] == $limit) echo ' selected'; ?>><?php echo $limits['text']; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </div>
        <div class="row gy-4">
          <?php foreach ($products as $product) { if ($product['product_id'] != '') { ?>
            <?php include(__DIR__ . '/../common/_product_list_card.tpl'); ?>
          <?php }} ?>
        </div>
        <div class="row mt-3">
          <div class="col-sm-7 text-start"><?php echo $pagination; ?></div>
          <div class="col-sm-5 text-end"><?php echo $results; ?></div>
        </div>
      <?php } else { ?>
        <div class="text-center py-5 text-muted">
          <i class="fa fa-search fa-2x mb-3 d-block" style="opacity:.25"></i>
          <p class="mb-0"><?php echo $text_empty; ?></p>
        </div>
      <?php } ?>

      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script>
$('#button-search').on('click', function () {
  var url = 'index.php?route=product/search';
  var search = $('#content input[name="search"]').val();
  if (search) url += '&search=' + encodeURIComponent(search);
  var category_id = $('#content select[name="category_id"]').val();
  if (category_id > 0) url += '&category_id=' + encodeURIComponent(category_id);
  if ($('#content input[name="sub_category"]:checked').val()) url += '&sub_category=true';
  if ($('#content input[name="description"]:checked').val()) url += '&description=true';
  location = url;
});

$('#content input[name="search"]').on('keydown', function (e) {
  if (e.keyCode == 13) $('#button-search').trigger('click');
});

$('select[name="category_id"]').on('change', function () {
  $('input[name="sub_category"]').prop('disabled', this.value == '0');
}).trigger('change');
</script>
<?php echo $footer; ?>
