<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="float-end"> <a href="<?php echo $cancel; ?>" data-bs-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-secondary"><i class="fa fa-reply"></i></a></div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
          <?php foreach ($breadcrumbs as $breadcrumb) { ?>
            <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <div class="card">
      <div class="card-header">
        <h3 class="card-title"><i class="fa fa-pencil"></i> <?php echo $heading_title; ?></h3>
      </div>
      <div class="card-body">
        <p><?php echo $text_installed; ?></p>
      </div>
    </div>
  </div>
  <?php echo $footer; ?>