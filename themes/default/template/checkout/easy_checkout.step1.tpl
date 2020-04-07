<div class="form-group row">
  <div class="col-5">
    <div class="row">

      <div class="col-4">
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
      <div class="col-6">
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

  <div class="col-7" style="display: block;">
      <?php foreach ($custom_fields as $field) { ?><?php } ?>
  </div>


</div>


<div class="row">
  <div class="col-4">

    <div class="row company_details" style="display: none">
      <div class="col-12">
          <?php eff('company', 'Company name', 0);
          eff('custom_field1', 'VAT.nr', 0); ?>
      </div>
    </div>


      <?php
      $fields = [
          'firstname' => ['required' => 1],
          //'lastname'=>['required' => 1],
          'email'     => ['required' => 1],
          'telephone' => ['required' => 1],
      ];


      foreach ($fields as $key => $field) {
          eff($key, ${"entry_" . $key}, $field['required']);
      }
      ?>
  </div>
  <div class="col-6">
      <?php

      $key = 'address_1';
      eff($key, ${"entry_" . $key}, 1, 'text', 4);

      $key = 'city';
      eff($key, ${"entry_" . $key}, 1, 'text', 4);

      $key = 'postcode';
      eff($key, ${"entry_" . $key}, 1, 'text', 4);
      ?>

    <div class="form-group row required">
      <label class="col-2 control-label" for="input-payment-country"><?php echo $entry_country; ?></label>
      <div class="col-10">
        <select name="country_id" id="input-payment-country" class="form-control">
          <option value=""><?php echo $text_select; ?></option>
            <?php foreach ($countries as $country) { ?><?php if ($country['country_id'] == $country_id) { ?>
              <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
            <?php } else { ?>
              <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
            <?php } ?><?php } ?>
        </select>
      </div>
    </div>


  </div>
</div>


<script>
    $('input[type=radio][name=customer_group_id]').on('change', function () {

        if(this.checked && this.value==3) {
            $('.company_details').show();
        } else {
            $('.company_details').hide();
        }
    });
</script>





