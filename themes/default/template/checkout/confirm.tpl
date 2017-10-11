<?php echo $header; ?>

<div class="category-pd-checkout">
  <div class="container">
      <?php if ($attention) { ?>
        <div class="alert alert-info"><i class="fa fa-info-circle"></i>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $attention; ?>
        </div>
    <?php } ?>
    <?php if ($success) { ?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php echo $success; ?>
        </div>
    <?php } ?>
    <?php if ($error_warning) { ?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
            <?php foreach ($error_warning as $val) { ?>
              <?= $val ?> <br />
          <?php } ?>
        </div>
    <?php } ?>
    <div class="row"><?php echo $column_left; ?>
        <?php if ($column_left && $column_right) { ?>
            <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
            <?php $class = 'col-sm-9'; ?>
        <?php } else { ?>
            <?php $class = 'col-sm-12'; ?>
        <?php } ?>
      <div id="content" class="<?php echo $class; ?> content-cart"><?php echo $content_top; ?>


        <?php if (!isset($redirect)) { ?>
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td colspan="2" class="text-left"><?php echo $column_name; ?></td>
                  <td class="text-right"><?php echo $column_quantity; ?></td>
                  <td class="text-right"><?php echo $column_price; ?></td>
                  <td class="text-right"><?php echo $column_total; ?></td>
                </tr>
              </thead>
              <tbody>

                <?php foreach ($products as $product) { ?>

                    <tr>
                      <td colspan="2" class="text-left"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                          <?php foreach ($product['option'] as $option) { ?>
                            <br />
                            &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                        <?php } ?>

                        <?php if ($product['recurring']) { ?>
                            <br />
                            <span class="label label-info"><?php echo $text_recurring; ?></span>
                            <small><?php echo $product['recurring']; ?></small>
                        <?php } ?>
                      </td>
                      <td class="text-right"><?php echo $product['quantity']; ?></td>
                      <td class="text-right"><?php echo $product['price_no_vat']; ?></td> <!-- price -->
                      <td class="text-right"><?php echo $product['price_no_vat_total']; ?></td> <!-- total -->
                    </tr>
                <?php } ?>
                <?php foreach ($vouchers as $voucher) { ?>
                    <tr>
                      <td class="text-left"><?php echo $voucher['description']; ?></td>
                      <td class="text-left"></td>
                      <td class="text-right">1</td>
                      <td class="text-right"><?php echo $voucher['amount']; ?></td>
                      <td class="text-right"><?php echo $voucher['amount']; ?></td>
                    </tr>
                <?php } ?>
              </tbody>
              <tfoot>

                <?php foreach ($totals as $total) { ?>
                    <tr>
                      <td colspan="4" class="text-right"><strong><?php echo $total['title']; ?>:</strong></td>
                      <td class="text-right"><?php echo $total['text']; ?></td>
                    </tr>
                <?php } ?>
              </tfoot>
            </table>
            <?php echo $payment; ?>
        <?php } else { ?>
            <script type="text/javascript"><!--
    					location = '<?php echo $redirect; ?>';
                  //--></script>
        <?php } ?>


        <?php echo $content_bottom; ?></div>
      <?php echo $column_right; ?></div>
  </div>
</div>
<?php echo $footer; ?>
