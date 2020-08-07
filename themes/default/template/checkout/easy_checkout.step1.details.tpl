<?php  // TODO:  this is bad, if there is no customer_groups set in admin!  ?>


<?php if($customer_groups) { ?>
<div class="form-group row">
  <div class="col-12 col-sm-5">
    <div class="row">

      <div class="col-12 col-md-5">
        <div class="row">
          <div class="col-12">
            <label>
              <input type="radio" name="customer_group_id" value="<?php echo $customer_groups[0]['customer_group_id']; ?>"
                     <?= ($customer_groups[0]['customer_group_id'] == $customer_group_id ? 'checked="checked"' : '') ?>/>
                <?php echo $customer_groups[0]['name']; ?>
            </label>
          </div>
        </div>
      </div>


        <?php if(!empty($customer_groups[1])) { ?>
      <div class="col-12 col-sm-7">
        <div class="row">
          <div class="col-12">
            <label>
              <input type="radio" name="customer_group_id" value="<?php echo $customer_groups[1]['customer_group_id']; ?>"
                     <?= ($customer_groups[1]['customer_group_id'] == $customer_group_id ? 'checked="checked"' : '') ?>/>
                <?php echo $customer_groups[1]['name']; ?>
            </label>
          </div>
        </div>
      </div>
      <?php } ?>
    </div>

  </div>

  <div class="col-12 col-sm-7" style="display: block;">
      <?php foreach ($custom_fields as $field) { ?><?php } ?>
  </div>
</div>

<?php } ?>

<div class="row">
  <div class="col-12">
    <h4><?php echo __c('text_delivery_address'); ?></h4>
  </div>
</div>

<div class="row">
  <div class="col-12 col-sm-12 col-md-4">


    <div class="row company_details" style="display: none">
      <div class="col-12">
          <?php
          // eff('company', 'Uzņēmuma nosaukums', 0);
          eff('custom_field1', 'Uzņēmuma nosaukums', 0);
          eff('custom_field3', 'Reģ.Nr.', 0);
          eff('custom_field2', 'PVN.nr', 0);
          ?>
      </div>
    </div>


      <?php
      $fields = [
          'firstname' => ['required' => 1],
          'lastname'=>['required' => 1],
          'email'     => ['required' => 1],
          'telephone' => ['required' => 1],
      ];


      foreach ($fields as $key => $field) {
          eff($key, ${"entry_" . $key}, $field['required'], 'text', 4, ['default_value' => $$key, 'invalid' => (!empty($errors[$key]) ? 1 : 0 ) ]);

          if (!empty($errors[$key])) {
              echo "<div class='text-danger'>" . $errors[$key] . "</div>";
          }


      }
      ?>
  </div>
  <div class="col-12 col-sm-12 col-md-6">
      <?php

      $key = 'address_1';
      eff($key, ${"entry_" . $key}, 1, 'text', 2, ['default_value' => $$key]);


      $key = 'city';
      eff($key, ${"entry_" . $key}, 1, 'text', 2, ['default_value' => $$key]);

      $key = 'postcode';
      eff($key, ${"entry_" . $key}, 1, 'text', 2, ['default_value' => $$key]);
      ?>

    <div class="form-group row required">
      <label class="col-2 control-label" for="input-payment-country"><?php echo $entry_country; ?>:</label>
      <div class="col-10">


          <strong>Latvija</strong> <br />

          <input name="country_id" type="hidden" id="input-payment-country" class="form-control" value="117">


          <?php /*
          <select name="country_id" id="input-payment-country" class="form-control">
          <option value=""><?php echo $text_select; ?></option>
            <?php foreach ($countries as $country) { ?><?php if ($country['country_id'] == $country_id) { ?>
              <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
            <?php } else { ?>
              <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
            <?php } ?><?php } ?>
        </select>
          */?>
      </div>
    </div>

      <?php if($text_my_billing_address_is_different) { ?>
    <div class="form-group row">
      <div class="col-12">
        <label>
          <input type="checkbox" name="billing_address_details" value="1">
          <span style="font-style: italic"><?php echo $text_my_billing_address_is_different ;  ?></span>


        </label>

      </div>
    </div>
      <?php } ?>


  </div>
</div>


<div class="billing_address_block" style="display: none">

  <div class="row">
    <div class="col-12">
      <h4>Billing Address</h4>
      <hr>
    </div>
  </div>

  <div class="row">


    <div class="col-12 col-sm-12 col-md-4">


      <div class="row company_details" style="">
        <div class="col-12">
            <?php eff('company_copy', 'Company name', 0, '', '', ['disabled' => 1, 'disable_placeholder' => 1]);
            eff('custom_field1_copy', 'VAT.nr', 0, '', '', ['disabled' => 1, 'disable_placeholder' => 1]); ?>
        </div>
      </div>


        <?php
        $fields = [
            'firstname' => ['required' => 1, 'named_array' => 'address2'],
            //'lastname'=>['required' => 1],
            'email'     => ['required' => 1, 'named_array' => 'address2'],
            'telephone' => ['required' => 1, 'named_array' => 'address2'],
        ];


        foreach ($fields as $key => $field) {
            eff($key, ${"entry_" . $key}, $field['required'], '', '', ['named_array' => $field['named_array']]);
        }
        ?>
    </div>
    <div class="col-6">
        <?php

        $key = 'address_1';
        eff($key, ${"entry_" . $key}, 1, 'text', 4, ['named_array' => 'address2']);

        $key = 'city';
        eff($key, ${"entry_" . $key}, 1, 'text', 4, ['named_array' => 'address2']);

        $key = 'postcode';
        eff($key, ${"entry_" . $key}, 1, 'text', 4, ['named_array' => 'address2']);
        ?>


      <?PHP /* <div class="form-group row required">
        <label class="col-2 control-label" for="input-payment-country"><?php echo $entry_country; ?></label>
        <div class="col-10">
          <select name="address2[country_id]" id="input-payment-country" class="form-control">
            <option value=""><?php echo $text_select; ?></option>
              <?php foreach ($countries as $country) { ?><?php if ($country['country_id'] == $country_id) { ?>
                <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
              <?php } else { ?>
                <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
              <?php } ?><?php } ?>
          </select>
        </div>
      </div> */ ?>


    </div>
  </div>

</div>


<script>
    $('input[type=radio][name=customer_group_id]').on('change', function () {
        if (this.checked && this.value == 2) {
            $('.company_details').show();
        } else {
            $('.company_details').hide();
        }
    });


    $('input[type=checkbox][name=billing_address_details]').on('change', function () {
        if (this.checked && this.value == 1) {
            $('.billing_address_block').show();
            $('span.billing_address_title').hide();

        } else {
            $('.billing_address_block').hide();
            $('span.billing_address_title').show();
        }
    });

    $('input[name=company],input[name=custom_field1]').on('change', function () {
        $('input[name=' + this.name + '_copy]').val(this.value);
    });
</script>





