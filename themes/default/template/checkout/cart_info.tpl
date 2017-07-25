<div id="cart-info" class="col-md-8">
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="cart-contents">
    <div class="table-responsive">
      <table class="table">
        <thead>
          <tr>
            <th class="image"><?php echo $column_image; ?></th>
            <th class="name text-left"><?php echo $column_name; ?></th>
            <th class="quantity text-left"><?php echo $column_quantity; ?></th>
            <th class="price text-right"><?php echo $column_price; ?></th>
            <th class="total text-right"><?php echo $column_total; ?></th>
          </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product) { ?>
              <tr>
                <td class="image">
                    <?php if ($product['thumb']) { ?>
                      <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>"  class="img-thumbnail" /></a>
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
                      <span class="label label-info"><?php echo $text_recurring_item; ?></span> <small><?php echo $product['recurring']; ?></small>
                  <?php } ?>
                </td>

                <td class="text-left quantity">
                  <div class="input-group btn-block" style="min-width:120px; max-width: 200px;">
                    <input type="text" name="quantity[<?php echo $product['cart_id']; ?>]" value="<?php echo $product['quantity']; ?>" size="1" class="checkout-quantity form-control" />
                    <span class="input-group-btn">
                      <span onclick="cart.update(<?php echo $product['cart_id']; ?>, $(this).parent().parent().find('.checkout-quantity').val())" data-toggle="tooltip" title="<?php echo $button_update; ?>" class="btn btn-default"><i class="fa fa-refresh"></i></span>
                      <span type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-default" onclick="cart.remove('<?php echo $product['cart_id']; ?>');"><i class="fa fa-times-circle"></i></span>
                    </span>
                  </div>
                </td>

                <td class="price"><?php echo $product['price']; ?></td>

                <td class="total"><?php echo $product['total']; ?></td>

              </tr>

          <?php } ?>

          <?php foreach ($vouchers as $voucher) { ?>
              <tr>
                <td class="image"></td>
                <td class="name"><?php echo $voucher['description']; ?></td>
                <td class="quantity">
                  <div class="input-group btn-block" style="min-width:120px; max-width: 200px;">
                    <input type="text" name="" value="1" size="1" disabled="disabled" class="form-control" />
                    <span class="input-group-btn"><button type="button" data-toggle="tooltip" title="<?php echo $button_remove; ?>" class="btn btn-default" onclick="voucher.remove('<?php echo $voucher['key']; ?>');"><i class="fa fa-times-circle"></i></button></span>
                  </div>
                </td>
                <td class="price"><?php echo $voucher['amount']; ?></td>
                <td class="total"><?php echo $voucher['amount']; ?></td>
              </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </form>
</div>
<div id="cart-module" class="col-md-4">
  <div class="inner">
    <ul class="cart-totals">
        <?php foreach ($totals as $total) { ?>
          <li><span class="left"><?php echo $total['title']; ?>:</span><span class="right"><?php echo $total['text']; ?></span></li>
      <?php } ?>
    </ul>
    <div id="total-cart">
      <div class="buttons">
        <div class="cart"><a href="<?php echo $checkout; ?>" id="button-checkout" class="btn btn-default btn-block btn-cart btn-lg"><?php echo $button_checkout; ?></a></div>
        <a href="<?php echo $continue; ?>" id="continue-shopping" class="btn btn-default btn-block"><?php echo $button_shopping; ?></a>
      </div>
    </div>
    <?php if ($modules) { ?>
        <div class="contentset"><h4><?php echo $text_next; ?></h4></div>
        <p><?php echo $text_next_choice; ?></p>
        <div class="panel-group" id="accordion">
          <?php foreach ($modules as $module) { ?>
              <?php echo $module; ?>
          <?php } ?>
        </div>
    <?php } ?>
  </div>
</div>