<div class="table-responsive">
  <table class="table table-bordered table-hover">
    <thead>
      <tr>
        <td class="text-start"><?php echo $column_ip; ?></td>
        <td class="text-end"><?php echo $column_total; ?></td>
        <td class="text-start"><?php echo $column_date_added; ?></td>
      </tr>
    </thead>
    <tbody>
        <?php if ($ips) { ?>
            <?php foreach ($ips as $ip) { ?>
              <tr>
                <td class="text-start"><a href="http://www.geoiptool.com/en/?IP=<?php echo $ip['ip']; ?>" target="_blank"><?php echo $ip['ip']; ?></a></td>
                <td class="text-end"><a href="<?php echo $ip['filter_ip']; ?>" target="_blank"><?php echo $ip['total']; ?></a></td>
                <td class="text-start"><?php echo $ip['date_added']; ?></td>      </tr>
          <?php } ?>
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
