<div class="row gx-4">
  <div class="col-md-8">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="cart-contents">
      <div class="table-responsive">
        <table class="table">
          <thead>
            <tr>
              <th class="image"><?php echo $column_image; ?></th>
              <th class="name text-start"><?php echo $column_name; ?></th>
              <th class="quantity text-start"><?php echo $column_quantity; ?></th>
              <th class="price text-end"><?php echo $column_price; ?></th>
              <th class="total text-end"><?php echo $column_total; ?></th>
            </tr>
          </thead>
          <tbody>
              <?php foreach ($products as $product) { ?>
                <tr>
                  <td class="image">
                      <?php if ($product['thumb']) { ?>
                        <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail" /></a>
                    <?php } ?>
                  </td>

                  <td class="name">
                    <a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                    <div class="model"><?php echo $column_model; ?>: <?php echo $product['model']; ?></div>

                    <?php if (!$product['stock']) { ?>
                        <span class="text-danger">***</span>
                    <?php } ?>

                    <?php if ($product['option']) { ?>
                        <?php foreach ($product['option'] as $option) { ?>
                            <br />
                            <small>- <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                        <?php } ?>
                    <?php } ?>

                    <?php if ($product['reward']) { ?>
                        <br />
                        <small><?php echo $product['reward']; ?></small>
                    <?php } ?>

                    <?php if ($product['recurring']) { ?>
                        <br />
                        <span class="badge bg-info"><?php echo $text_recurring_item; ?></span> <small><?php echo $product['recurring']; ?></small>
                    <?php } ?>
                  </td>

                  <td class="text-start quantity">
                    <div class="input-group input-group-sm flex-nowrap" style="width:120px;">
                      <input type="text" name="quantity[<?php echo $product['cart_id']; ?>]" value="<?php echo $product['quantity']; ?>" class="checkout-quantity form-control" style="min-width:40px;" />
                      <button type="button" onclick="cart.update(<?php echo $product['cart_id']; ?>, $(this).closest('.input-group').find('.checkout-quantity').val())" data-bs-toggle="tooltip" title="<?php echo $button_update; ?>" class="btn btn-secondary"><i class="fa fa-refresh"></i></button>
                      <button type="button" data-bs-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-secondary" onclick="cart.remove('<?php echo $product['cart_id']; ?>');"><i class="fa fa-times-circle"></i></button>
                    </div>
                  </td>

                  <td class="price text-end"><?php echo $product['price']; ?></td>

                  <td class="total text-end"><?php echo $product['total']; ?></td>

                </tr>

            <?php } ?>

            <?php foreach ($vouchers as $voucher) { ?>
                <tr>
                  <td class="image"></td>
                  <td class="name"><?php echo $voucher['description']; ?></td>
                  <td class="quantity">
                    <div class="input-group input-group-sm flex-nowrap" style="width:120px;">
                      <input type="text" name="" value="1" disabled="disabled" class="form-control" style="min-width:40px;" />
                      <button type="button" data-bs-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-secondary" onclick="voucher.remove('<?php echo $voucher['key']; ?>');"><i class="fa fa-times-circle"></i></button>
                    </div>
                  </td>
                  <td class="price text-end"><?php echo $voucher['amount']; ?></td>
                  <td class="total text-end"><?php echo $voucher['amount']; ?></td>
                </tr>
            <?php } ?>
          </tbody>
        </table>
      </div>
    </form>
  </div>

  <div class="col-md-4 cart-info-sidebar">
    <?php if ($modules) { ?>
      <h5><?php echo $text_next; ?></h5>
      <p class="text-muted small"><?php echo $text_next_choice; ?></p>
      <div class="panel-group mb-3" id="accordion">
          <?php foreach ($modules as $module) { ?>
              <?php echo $module; ?>
          <?php } ?>
      </div>
    <?php } ?>
    <table class="table table-bordered table-sm">
        <?php foreach ($totals as $total) { ?>
          <tr>
            <td class="text-end"><strong><?php echo $total['title']; ?>:</strong></td>
            <td class="text-end"><?php echo $total['text']; ?></td>
          </tr>
        <?php } ?>
    </table>
    <div class="cart-info-actions d-flex justify-content-end mt-2">
      <a href="<?php echo $checkout; ?>" class="btn btn-primary"><?php echo $button_checkout; ?></a>
    </div>
  </div>
</div>
