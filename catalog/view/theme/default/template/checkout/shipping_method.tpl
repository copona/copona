<style>
  .pickup-description{
      margin-left:40px;
      display: block;
      font-size: 12px;
  }

</style>


<?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>

<?php if ($shipping_methods) { ?>
    <p><?php echo $text_shipping_method; ?></p>
    <?php foreach ($shipping_methods as $type => $shipping_method) { ?>
        <?php $show_address = ($type == 'country_zone' ? true : false); // pārbauda vai rādīt adreses lauku   ?>
        <?php if (!$shipping_method['error']) { ?>
            <?php if (isset($shipping_method['group_title'])) { ?>
                <p><strong><?php echo $shipping_method['group_title'] ?><p></strong>
                <?php } ?>
                <?php foreach ($shipping_method['quote'] as $key => $quote) { ?>
                <div class="radio">
                  <label>
                      <?php if ($quote['code'] == $code || !$code) { ?>
                          <?php $code = $quote['code']; ?>
                        <input data-show-address="<?= $show_address ?>" class="selected-shipping-method" type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" checked="checked" />
                        <input type="hidden" name="<?php echo $quote['code']; ?>" value="<?php echo $quote['cost']; ?>" />
                    <?php } else { ?>
                        <input data-show-address="<?= $show_address ?>" type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" />
                        <input type="hidden" name="<?php echo $quote['code']; ?>" value="<?php echo $quote['cost']; ?>"  />
                    <?php } ?>
                    <?php echo $quote['title']; ?> - (<?php echo $quote['text']; ?>)
                    <?= (isset($quote['worktime']) ? '<span class="pickup-description">' . $quote['worktime'] . '<br / >Tel. ' . $quote['phone'] . '</span>' : '') ?>
                  </label>
                </div>
            <?php } ?>
        <?php } else { ?>
            <div class="alert alert-danger"><?php echo $shipping_method['error']; ?></div>
        <?php } ?>
    <?php } ?>
<?php } ?>

<b><?php echo $text_comments; ?></b>
<textarea class="form-control" name="comment" rows="4" style="width: 100%;"><?php echo $comment; ?></textarea>

