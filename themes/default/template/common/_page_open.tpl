<?php
// Opens a standard page: breadcrumbs, flash messages, side columns and #content.
// Close it with _page_close.tpl. Set $page_class before including to add a modifier.
?>
<div class="container cp-page<?php echo !empty($page_class) ? ' ' . $page_class : ''; ?>">
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <li class="breadcrumb-item"><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ol></nav>
  <?php if (!empty($success)) { ?>
    <div class="alert alert-success alert-dismissible"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php } ?>
  <?php foreach (['error_warning', 'error_csrf_token'] as $page_error) { if (empty($$page_error)) continue; ?>
    <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $$page_error; ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  <?php } ?>
  <div class="cp-layout<?php echo $column_left ? ' has-left' : ''; ?><?php echo $column_right ? ' has-right' : ''; ?>">
    <?php echo $column_left; ?>
    <div id="content"><?php echo $content_top; ?>
