<table class="table table-bordered">
  <thead>
    <tr>
      <td class="text-start"><?php echo $column_google_category; ?></td>
      <td class="text-start"><?php echo $column_category; ?></td>
      <td class="text-end"><?php echo $column_action; ?></td>
    </tr>
  </thead>
  <tbody>
      <?php if ($google_base_categories) { ?>
          <?php foreach ($google_base_categories as $google_base_category) { ?>
            <tr>
              <td class="text-start"><?php echo $google_base_category['google_base_category']; ?></td>
              <td class="text-start"><?php echo $google_base_category['category']; ?></td>
              <td class="text-end"><button type="button" value="<?php echo $google_base_category['category_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" data-bs-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-danger"><i class="fa fa-minus-circle"></i></button></td>
            </tr>
        <?php } ?>
    <?php } else { ?>
        <tr>
          <td class="text-center" colspan="3"><?php echo $text_no_results; ?></td>
        </tr>
    <?php } ?>
  </tbody>
</table>
<div class="row">
  <div class="col-sm-6 text-start"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-end"><?php echo $results; ?></div>
</div>
