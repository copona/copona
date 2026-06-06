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
      <h1><?php echo $heading_title; ?></h1>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" class="row">
        <fieldset>
          <div class="form-group">
            <label class="col-sm-2 form-label"><?php echo $entry_newsletter; ?></label>
            <div class="col-sm-10">
                <?php if ($newsletter) { ?>
                  <label class="radio-inline">
                    <input type="radio" name="newsletter" value="1" checked="checked" />
                    <?php echo $text_yes; ?> </label>
                  <label class="radio-inline">
                    <input type="radio" name="newsletter" value="0" />
                    <?php echo $text_no; ?></label>
              <?php } else { ?>
                  <label class="radio-inline">
                    <input type="radio" name="newsletter" value="1" />
                    <?php echo $text_yes; ?> </label>
                  <label class="radio-inline">
                    <input type="radio" name="newsletter" value="0" checked="checked" />
                    <?php echo $text_no; ?></label>
              <?php } ?>
            </div>
          </div>
        </fieldset>
        <div class="buttons clearfix">
          <div class="float-start"><a href="<?php echo $back; ?>" class="btn btn-secondary"><?php echo $button_back; ?></a></div>
          <div class="float-end">
            <input type="submit" value="<?php echo $button_continue; ?>" class="btn btn-primary" />
          </div>
        </div>
      </form>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<?php echo $footer; ?>