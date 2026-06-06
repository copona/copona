<?php echo $header; ?>
<div class="container">
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li class="breadcrumb-item"><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
      <?php } ?>
  </ol></nav>
  <div class="row relative"><?php echo $column_left; ?>
      <?php if ($column_left && $column_right) { ?>
          <?php $class = 'col-sm-6'; ?>
      <?php } elseif ($column_left || $column_right) { ?>
          <?php $class = 'col-sm-9'; ?>
      <?php } else { ?>
          <?php $class = 'col-sm-12'; ?>
      <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h2><?php echo $heading_title; ?></h2>
        <?php if ($description) { ?>
          <div class="row">
              <?php if ($description) { ?>
                <div class="col-sm-10"><?php echo $description; ?></div>
              <?php } ?>
          </div>
          <hr>
        <?php } ?>
        <?php if ($categories) { ?>
          <div class="row">
              <?php if ($thumb) { ?>
                <div class="col-sm-2">
                  <img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" class="img-thumbnail"/>
                </div>
              <?php } ?>
              <?php $columns = $thumb ? 5 : 4 ?>
              <?php foreach (array_chunk($categories, ceil(count($categories) / 3)) as $categories) { ?>
                <div class="col-sm-<?= $columns ?>">
                  <ul>
                      <?php foreach ($categories as $category) { ?>
                        <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
                      <?php } ?>
                  </ol></nav>
                </div>
              <?php } ?>
          </div>
        <?php } ?>
        <?php if ($manufacturers) { ?>
          <ul class="row manufacturer-list">
              <?php foreach ($manufacturers as $manufacturer) { ?>
                <li><a href="<?= $manufacturer['href'] ?>"><img src="<?= $manufacturer['image'] ?>"></a></li>
              <?php } ?>
          </ol></nav>
        <?php } ?>
        <?php if ($products) { ?>
          <div class="row">
            <div class="col-md-2 col-sm-6 d-none d-sm-block">
              <div class="btn-group btn-group-sm">
                <button type="button" id="list-view" class="btn btn-secondary" data-bs-toggle="tooltip" title="<?php echo $button_list; ?>">
                  <i class="fa fa-th-list"></i></button>
                <button type="button" id="grid-view" class="btn btn-secondary" data-bs-toggle="tooltip" title="<?php echo $button_grid; ?>">
                  <i class="fa fa-th"></i></button>
              </div>
            </div>
            <div class="col-md-3 col-sm-6">
              <div class="form-group">
                <a href="<?php echo $compare; ?>" id="compare-total" class="btn btn-link"><?php echo $text_compare; ?></a>
              </div>
            </div>
            <div class="col-md-4 col-6">
              <div class="form-group input-group input-group-sm">
                <label class="input-group-text" for="input-sort"><?php echo $text_sort; ?></label>
                <select id="input-sort" class="form-control" onchange="location = this.value;">
                    <?php foreach ($sorts as $sorts) { ?>
                        <?php if ($sorts['value'] == $sort . '-' . $order) { ?>
                        <option value="<?php echo $sorts['href']; ?>" selected="selected"><?php echo $sorts['text']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $sorts['href']; ?>"><?php echo $sorts['text']; ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
              </div>
            </div>
            <div class="col-md-3 col-6">
              <div class="form-group input-group input-group-sm">
                <label class="input-group-text" for="input-limit"><?php echo $text_limit; ?></label>
                <select id="input-limit" class="form-control" onchange="location = this.value;">
                    <?php foreach ($limits as $limits) { ?>
                        <?php if ($limits['value'] == $limit) { ?>
                        <option value="<?php echo $limits['href']; ?>" selected="selected"><?php echo $limits['text']; ?></option>
                        <?php } else { ?>
                        <option value="<?php echo $limits['href']; ?>"><?php echo $limits['text']; ?></option>
                        <?php } ?>
                    <?php } ?>
                </select>
              </div>
            </div>
          </div>
          <div class="row gy-4">
            <?php foreach ($products as $product) { ?>
              <?php include(__DIR__ . '/../common/_product_list_card.tpl'); ?>
            <?php } ?>
          </div>
          <div class="row paginations">
            <div class="col-sm-7 text-start"><?php echo $pagination; ?></div>
            <div class="col-sm-5 text-end"><?php echo $results; ?></div>
          </div>
        <?php } ?>
        <?php if (!$categories && !$products) { ?>
          <p><?php echo $text_empty; ?></p>
        <?php } ?>
        <?php echo $content_bottom; ?></div>
      <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>
