<?php echo $header; ?>
<div class="container cp-page">
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <li class="breadcrumb-item"><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ol></nav>
  <div class="cp-page-head">
    <?php if ($thumb) { ?>
      <img src="<?php echo $thumb; ?>" alt="<?php echo $heading_title; ?>" class="cp-page-head__thumb"/>
    <?php } ?>
    <div>
      <h1><?php echo $heading_title; ?></h1>
      <?php if ($description) { ?>
        <div class="cp-page-head__desc"><?php echo $description; ?></div>
      <?php } ?>
    </div>
  </div>
  <?php if ($categories) { ?>
    <nav class="cp-chips" aria-label="<?php echo $heading_title; ?>">
      <?php foreach ($categories as $category) { ?>
        <a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
      <?php } ?>
    </nav>
  <?php } ?>
  <?php if ($manufacturers) { ?>
    <nav class="cp-chips cp-chips--brands" aria-label="<?php echo $text_brands; ?>">
      <?php foreach ($manufacturers as $manufacturer) { ?>
        <a href="<?= $manufacturer['href'] ?>"><?php if ($manufacturer['image']) { ?><img src="<?= $manufacturer['image'] ?>" alt="<?= $manufacturer['name'] ?>"><?php } else { ?><?= $manufacturer['name'] ?><?php } ?></a>
      <?php } ?>
    </nav>
  <?php } ?>
  <div class="cp-layout<?php echo $column_left ? ' has-left' : ''; ?><?php echo $column_right ? ' has-right' : ''; ?>">
    <?php echo $column_left; ?>
    <div id="content"><?php echo $content_top; ?>
      <?php if ($products) { ?>
        <?php include(__DIR__ . '/../common/_listing_toolbar.tpl'); ?>
        <?php include(__DIR__ . '/../common/_listing_products.tpl'); ?>
      <?php } elseif (!$categories) { ?>
        <p class="cp-empty"><?php echo $text_empty; ?></p>
      <?php } ?>
      <?php echo $content_bottom; ?>
    </div>
    <?php echo $column_right; ?>
  </div>
</div>
<?php echo $footer; ?>
