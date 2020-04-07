<style>
  .pickup-description {
    margin-left: 40px;
    display: block;
    font-size: 12px;
  }

</style>
<script>



    $(document).on('click', '#shipping-method .radio label', function () {
        $(this).find('input[type="radio"]').prop("checked", true);
    });

    function setSelectedValue(curr) {

        $(curr).parent().find('.selected-shipping-method').val($(curr).val());
        $(curr).parent().find('.selected-shipping-cost').attr('name', $(curr).val());
        $(curr).parent().find('input[name="shipping_method"]').val($(curr).val());

        //trigger click to recalculate total cart price
        $(curr).parent().find('input[name="shipping_method"]').trigger("click");
        $(curr).parent().find('.selected-shipping-cost').val($(curr).find('option:selected').data('cost'));


    }

    function focusSelectedValue(curr) {

        $(curr).parent().find('input[name="shipping_method"]').trigger("click");
        $(curr).parent().find('.selected-shipping-method').val($(curr).val());
        $(curr).parent().find('.selected-shipping-cost').attr('name', $(curr).val());
        $(curr).parent().find('.selected-shipping-cost').val($(curr).find('option:selected').data('cost'));
    }

    $(".selected-shipping-method").change(function () {
        $(this).find('.selected_sub_quote').trigger('focus');
    });
</script>

<?php

if (!empty($error_warning)) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php //pr (  $code )  ?>
<?php if ($shipping_methods) { ?>
  <p><?php echo $text_shipping_method; ?></p>

    <?php foreach ($shipping_methods as $type => $shipping_method) {  //prd($shipping_method)?>
        <?php $show_address = ($type !== 'pickup' ? true : false); // pārbauda vai rādīt adreses lauku   ?>
        <?php if (!$shipping_method['error']) { ?>
            <?php if (isset($shipping_method['group_title'])) { ?>
        <p><strong><?php echo $shipping_method['group_title'] ?></strong></p>
            <?php } ?>
            <?php if ($shipping_method['sub_quote']) { ?>
        <div class="radio">
          <label>
              <?php


              if ($code && isset($shipping_method['quote'][explode('.', $code)[1]])) {
                  $sub_quote_data['code'] = $shipping_method['quote'][explode('.', $code)[1]]['code'];
                  $sub_quote_data['cost_with_tax'] = str_replace(",", ".", $shipping_method['quote'][explode('.', $code)[1]]['cost_with_tax']);
                  $sub_quote_data['cost'] = $shipping_method['quote'][explode('.', $code)[1]]['cost'];
              } else {
                  $sub_quote_data['code'] = '';
                  $sub_quote_data['cost_with_tax'] = '';
                  $sub_quote_data['cost'] = '';
              }
              ?>
              <?php if ($sub_quote_data['code'] == $code || !$code) { // prd($sub_quote_data); ?>
                  <?php $code = $sub_quote_data['code']; ?>
                <input data-show-address="0" class="selected-shipping-method" type="radio" name="shipping_method"
                       value="<?php echo $sub_quote_data['code']; ?>" checked="checked"
                       data-cost="<?= (!empty($sub_quote_data['cost_with_tax'])
                           ? $sub_quote_data['cost_with_tax']
                           : ''); ?>"/>
                <input class="selected-shipping-cost" type="hidden" name="<?php echo $sub_quote_data['code']; ?>"
                       data-cost="<?php echo $sub_quote_data['cost_with_tax']; ?>"
                       value="<?php echo $sub_quote_data['cost_with_tax']; ?>"/>
              <?php } else { ?>
                <input data-show-address="0" class="shipping-method" type="radio" name="shipping_method"
                       data-cost="<?= (empty($sub_quote_data['code'])
                           ? str_replace(",", ".", reset($shipping_method['quote'])['cost_with_tax'])
                           : $sub_quote_data['cost_with_tax']); ?>"
                       value="<?= (empty($sub_quote_data['code'])
                           ? str_replace(",", ".", reset($shipping_method['quote'])['code'])
                           : $sub_quote_data['code']); ?>"/>
                <input class="selected-shipping-cost" type="hidden" name="<?php echo $sub_quote_data['code']; ?>"
                       value="<?= (empty($sub_quote_data['code'])
                           ? str_replace(",", ".", reset($shipping_method['quote'])['cost_with_tax'])
                           : $sub_quote_data['cost_with_tax']); ?>"
                />
              <?php } ?>
              <?php echo $shipping_method['title']; ?>

              <?php if ($shipping_method['sub_quote']) {

                  ?>
                <select style='max-width: 100%;' data-name="" class="form-control selected_sub_quote" onchange="setSelectedValue(this);" onfocus="focusSelectedValue(this);">
                    <?php foreach ($shipping_method['quote'] as $key => $sub_quote) { ?>
                      <option data-cost="<?= $sub_quote['cost_with_tax'] ?>"

                          <?= ($sub_quote_data['code'] == $sub_quote['code'] ? 'selected="selected"' : '') ?> value="<?= $sub_quote['code'] ?>"><?php echo $sub_quote['title'] ?></option>
                    <?php } ?>
                </select>
              <?php } ?>
          </label>
        </div>
            <?php } else { ?>
                <?php foreach ($shipping_method['quote'] as $key => $quote) {
                    (isset($quote['show_address']) ? $show_address = $quote['show_address'] : ''); ?>
          <div class="radio">
            <label>
                <?php if ($quote['code'] == $code || !$code) { ?>

                    <?php $quote['cost_with_tax'] = str_replace(",", ".", $quote['cost_with_tax']) ?>

                    <?php $code = $quote['code']; ?>
                  <input data-show-address="<?= $show_address ?>" class="selected-shipping-method" type="radio" name="shipping_method"
                         data-cost="<?php echo $quote['cost_with_tax']; ?>"
                         value="<?php echo $quote['code']; ?>" checked="checked"/>
                  <input type="hidden" name="<?php echo $quote['code']; ?>" value="<?php echo $quote['cost_with_tax']; ?>"
                  />
                <?php } else { ?>
                  <input data-show-address="<?= $show_address ?>"
                         data-cost="<?php echo $quote['cost_with_tax']; ?>"
                         type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>"/>
                  <input type="hidden" name="<?php echo $quote['code']; ?>" value="<?php echo $quote['cost_with_tax']; ?>"
                  />
                <?php } ?>
                <?php echo $quote['title']; ?> - (<?php echo $quote['text']; ?>)
                <?= (isset($quote['worktime']) ? '<span class="pickup-description">' . $quote['worktime'] . '<br / >Tel. ' . $quote['phone'] . '</span>' : '') ?>
            </label>
          </div>
                <?php }
            } ?>
        <?php } else { ?>
      <div class="alert alert-danger"><?php echo $shipping_method['error']; ?></div>
        <?php } ?>
    <?php } ?>
<?php } ?>

<b><?php echo $text_comments; ?></b>

<textarea class="form-control" name="comment" rows="4" style="width: 100%;"><?php echo $comment; ?></textarea>

<?php debug_template( __FILE__ ) ; ?>

