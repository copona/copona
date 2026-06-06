<?php echo $header; ?>
<div class="container">
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
      <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li class="breadcrumb-item"><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ol></nav>
  <?php if ($success) { ?>
      <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?></div>
  <?php } ?>
  <div class="row"><?php echo $column_left; ?>
      <?php if ($column_left && $column_right) { ?>
          <?php $class = 'col-sm-6'; ?>
      <?php } elseif ($column_left || $column_right) { ?>
          <?php $class = 'col-sm-9'; ?>
      <?php } else { ?>
          <?php $class = 'col-sm-12'; ?>
      <?php } ?>
    <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
      <h2><?php echo $text_my_account; ?></h2>
      <ul class="list-unstyled">
        <li><a href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a></li>
        <li><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>
        <li><a href="<?php echo $payment; ?>"><?php echo $text_payment; ?></a></li>
      </ol></nav>
      <h2><?php echo $text_my_tracking; ?></h2>
      <ul class="list-unstyled">
        <li><a href="<?php echo $tracking; ?>"><?php echo $text_tracking; ?></a></li>
      </ol></nav>
      <h2><?php echo $text_my_transactions; ?></h2>
      <ul class="list-unstyled">
        <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
      </ol></nav>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>