<?php if ($addresses) { ?><?php if (isset($customer_id)) { ?>
  <div class="radio">
    <label>
      <input type="radio" name="payment_address" value="existing" checked="checked" onclick="jQuery('#payment-address-new').hide()"/>
        <?php echo $text_address_existing; ?></label>
  </div>
  <div id="payment-existing">
    <select name="payment_address_id" class="form-control">
        <?php foreach ($addresses as $address) { ?><?php if (isset($payment_address_id) && $address['address_id'] == $payment_address_id) { ?>
          <option value="<?php echo $address['address_id']; ?>"
                  selected="selected"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>, <?php echo $address['address_1']; ?>, <?php echo $address['city']; ?>
            , <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>
        <?php } else { ?>
          <option
            value="<?php echo $address['address_id']; ?>"><?php echo $address['firstname']; ?> <?php echo $address['lastname']; ?>, <?php echo $address['address_1']; ?>
            , <?php echo $address['city']; ?>, <?php echo $address['zone']; ?>, <?php echo $address['country']; ?></option>
        <?php } ?><?php } ?>
    </select>
  </div>
<?php }
    if (isset($customer_id)) { ?>
      <div class="radio">
        <label>
          <input type="radio" name="payment_address" value="new" onclick="jQuery('#payment-address-new').show();"/>
            <?php echo $text_address_new; ?></label>
      </div>
    <?php }
} ?>


<div class="col-md-12" id="payment-address-new" <?php if (isset($customer_id) && $addresses) { ?>style="display:none"<?php } ?>>


    <?php if (isset($entry_company_id)) { ?><?php if (!$checkout_hide_company_id) { ?>
        <?php
        $key = 'company_id';
        eff($key, ${"entry_" . $key}, 0);

        ?>
    <?php } ?>


        <?php if (!$checkout_hide_tax_id) { ?>
            <?php
            $key = 'tax_id';
            eff($key, ${"entry_" . $key}, 0);

            ?>
        <?php } ?>

        <?php
    } ?>




  <div class="form-group required">
    <label class="control-label" for="input-payment-country"><?php echo $entry_country; ?></label>
    <select name="country_id" id="input-payment-country" class="form-control">
      <option value=""><?php echo $text_select; ?></option>
        <?php foreach ($countries as $country) { ?><?php if ($country['country_id'] == $country_id) { ?>
          <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
        <?php } else { ?>
          <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
        <?php } ?><?php } ?>
    </select>
  </div>


  <div class="form-group required">
    <label class="control-label" for="input-payment-zone"><?php echo $entry_zone;; ?></label>
    <select name="zone_id" id="input-payment-zone" class="form-control">
    </select>
  </div>


  <?php
  $key = 'city';
  eff($key, ${"entry_" . $key}, 1);

  $key = 'postcode';
  eff($key, ${"entry_" . $key}, 1);

  ?>

</div>


<div class="col-md-12">

    <?php if (!isset($customer_id)) { ?>
      <div class="form-group">
        <label>
          <input type="checkbox" name="register" onclick="jQuery('.register-form').toggle()">&nbsp;<?php echo $text_register; ?>
        </label>
      </div>
    <?php } ?>


  <div class="register-form" style="display:none">

    <?php
    $key = 'password';
    eff($key, ${"entry_" . $key}, 1);

    $key = 'confirm';
    eff($key, ${"entry_" . $key}, 1);


    ?>


  </div>
</div>


