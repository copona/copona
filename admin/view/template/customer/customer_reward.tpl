<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td class="text-start"><?php echo $column_date_added; ?></td>
        <td class="text-start"><?php echo $column_description; ?></td>
        <td class="text-end"><?php echo $column_points; ?></td>
      </tr>
    </thead>
    <tbody>
        <?php if ($rewards) { ?>
            <?php foreach ($rewards as $reward) { ?>
              <tr>
                <td class="text-start"><?php echo $reward['date_added']; ?></td>
                <td class="text-start"><?php echo $reward['description']; ?></td>
                <td class="text-end"><?php echo $reward['points']; ?></td>
              </tr>
          <?php } ?>
          <tr>
            <td></td>
            <td class="text-end"><b><?php echo $text_balance; ?></b></td>
            <td class="text-end"><?php echo $balance; ?></td>
          </tr>
      <?php } else { ?>
          <tr>
            <td class="text-center" colspan="3"><?php echo $text_no_results; ?></td>
          </tr>
      <?php } ?>
    </tbody>
  </table>
</div>
<div class="row">
  <div class="col-sm-6 text-start"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-end"><?php echo $results; ?></div>
</div>
