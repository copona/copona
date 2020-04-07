<?php if ($shipping_required) { ?>
<div class="shiptobilling clearfix">
  <h3><i class="fa fa-paper-plane" aria-hidden="true"></i> <?php echo $text_checkout_shipping_address; ?></h3>
    <?php if (!isset($customer_id)) { ?>
      <label class="checkbox">
        <input type="checkbox" name="shipping_address" value="new" <?php if (isset($address_id)) {
            echo 'checked="checked"';
        } ?> onclick="jQuery('.shipping-address').toggle()"><?php echo $text_address_new; ?>
      </label>
    <?php } ?>
</div>

<div class="shipping-address" <?php if (isset($shipping_address_id) || isset($customer_id)) {
    echo 'style="display:block"';
} else {
    echo 'style="display:none"';
} ?>>
    <?php if ($addresses) { ?><?php if (isset($customer_id)) { ?>
      <div class="radio">
        <label>
          <input type="radio" name="shipping_address" id="jkl" value="existing" checked="checked" onclick="jQuery('#shipping-new').hide()"/>
            <?php echo $text_address_existing; ?></label>
      </div>


      <div id="shipping-existing">

        <select name="shipping_address_id" class="form-control">
            <?php foreach ($addresses as $address) { ?><?php if (isset($shipping_address_id) && $address['address_id'] == $shipping_address_id) { ?>
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
              <input type="radio" name="shipping_address" value="new" onclick="jQuery('#shipping-new').show();"/>
                <?php echo $text_address_new; ?></label>
          </div>
        <?php }
    } ?>
  <br/>
  <div id="shipping-new" style="display: <?php echo(($addresses && isset($customer_id)) ? 'none' : 'block'); ?>;">
    <div class="form-group required">
      <label class="control-label" for="input-shipping-firstname"><?php echo $entry_firstname; ?></label>
      <div class="">
        <input type="text" name="shipping_firstname" value="" placeholder="<?php echo str_replace(':', '', $entry_firstname); ?>" id="input-shipping-firstname" class="form-control"/>
      </div>
    </div>
    <div class="form-group required">
      <label class="control-label" for="input-shipping-lastname"><?php echo $entry_lastname; ?></label>
      <div class="">
        <input type="text" name="shipping_lastname" value="" placeholder="<?php echo str_replace(':', '', $entry_lastname); ?>" id="input-shipping-lastname" class="form-control"/>
      </div>
    </div>
      <?php //if (!$checkout_hide_company_id){?>
    <div class="form-group">
      <label class="control-label" for="input-shipping-company"><?php echo $entry_company; ?></label>
      <div class="">
        <input type="text" name="shipping_company" value="" placeholder="<?php echo str_replace(':', '', $entry_company); ?>" id="input-shipping-company" class="form-control"/>
      </div>
    </div>
      <?php //} else {?>
    <input type="hidden" name="shipping_company" value=""/>
      <?php //}?>
    <div class="form-group required">
      <label class="control-label" for="input-shipping-address-1"><?php echo $entry_address_1; ?></label>
      <div class="">
        <input type="text" name="shipping_address_1" value="" placeholder="<?php echo str_replace(':', '', $entry_address_1); ?>" id="input-shipping-address-1" class="form-control"/>
      </div>
    </div>
    <div class="form-group">
      <label class="control-label" for="input-shipping-address-2"><?php echo $entry_address_2; ?></label>
      <div class="">
        <input type="text" name="shipping_address_2" value="" placeholder="<?php echo str_replace(':', '', $entry_address_2); ?>" id="input-shipping-address-2" class="form-control"/>
      </div>
    </div>
    <div class="form-group required">
      <label class="control-label" for="input-shipping-city"><?php echo $entry_city; ?></label>
      <div class="">
        <input type="text" name="shipping_city" value="" placeholder="<?php echo str_replace(':', '', $entry_city); ?>" id="input-shipping-city" class="form-control"/>
      </div>
    </div>
    <div class="form-group required">
      <label class="control-label" for="input-shipping-postcode"><?php echo $entry_postcode; ?></label>
      <div class="">
        <input type="text" name="shipping_postcode" value="<?php echo $postcode; ?>" placeholder="<?php echo str_replace(':', '', $entry_postcode); ?>" id="input-shipping-postcode"
               class="form-control"/>
      </div>
    </div>
    <div class="form-group required">
      <label class="control-label" for="input-shipping-country"><?php echo $entry_country; ?></label>
      <div class="">
        <select name="shipping_country_id" id="input-shipping-country" class="form-control">
          <option value=""><?php echo $text_select; ?></option>
            <?php foreach ($countries as $country) { ?><?php if ($country['country_id'] == $country_id) { ?>
              <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
            <?php } else { ?>
              <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
            <?php } ?><?php } ?>
        </select>
      </div>
    </div>
    <div class="form-group required">
      <label class="control-label" for="input-shipping-zone"><?php echo $entry_zone; ?></label>
      <div class="">
        <select name="shipping_zone_id" id="input-shipping-zone" class="form-control">
        </select>
      </div>
    </div>
      <?php if (isset($custom_fields)) {
          foreach ($custom_fields as $custom_field) { ?><?php if ($custom_field['type'] == 'select') { ?>
            <div class="form-group<?php echo($custom_field['required'] ? ' required' : ''); ?> custom-field">
              <label class="control-label" for="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
              <div class="">
                <select name="shipping_custom_field[<?php echo $custom_field['custom_field_id']; ?>]" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"
                        class="form-control">
                  <option value=""><?php echo $text_select; ?></option>
                    <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                      <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>
                    <?php } ?>
                </select>
              </div>
            </div>
          <?php } ?><?php if ($custom_field['type'] == 'radio') { ?>
            <div class="form-group<?php echo($custom_field['required'] ? ' required' : ''); ?> custom-field">
              <label class="control-label"><?php echo $custom_field['name']; ?></label>
              <div class="">
                <div id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>">
                    <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                      <div class="radio">
                        <label>
                          <input type="radio" name="shipping_custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>"/>
                            <?php echo $custom_field_value['name']; ?></label>
                      </div>
                    <?php } ?>
                </div>
              </div>
            </div>
          <?php } ?><?php if ($custom_field['type'] == 'checkbox') { ?>
            <div class="form-group<?php echo($custom_field['required'] ? ' required' : ''); ?> custom-field">
              <label class="control-label"><?php echo $custom_field['name']; ?></label>
              <div class="">
                <div id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>">
                    <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
                      <div class="checkbox">
                        <label>
                          <input type="checkbox" name="shipping_custom_field[<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>"/>
                            <?php echo $custom_field_value['name']; ?></label>
                      </div>
                    <?php } ?>
                </div>
              </div>
            </div>
          <?php } ?><?php if ($custom_field['type'] == 'text') { ?>
            <div class="form-group<?php echo($custom_field['required'] ? ' required' : ''); ?> custom-field">
              <label class="control-label" for="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
              <div class="">
                <input type="text" name="shipping_custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>"
                       placeholder="<?php echo str_replace(':', '', $custom_field['name']); ?>" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control"/>
              </div>
            </div>
          <?php } ?><?php if ($custom_field['type'] == 'textarea') { ?>
            <div class="form-group<?php echo($custom_field['required'] ? ' required' : ''); ?> custom-field">
              <label class="control-label" for="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
              <div class="">
                              <textarea name="shipping_custom_field[<?php echo $custom_field['custom_field_id']; ?>]" rows="5" placeholder="<?php echo str_replace(':', '', $custom_field['name']); ?>"
                                        id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control"><?php echo $custom_field['value']; ?></textarea>
              </div>
            </div>
          <?php } ?><?php if ($custom_field['type'] == 'file') { ?>
            <div class="form-group<?php echo($custom_field['required'] ? ' required' : ''); ?> custom-field">
              <label class="control-label"><?php echo $custom_field['name']; ?></label>
              <div class="">
                <button type="button" id="button-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="btn btn-default"><i
                    class="fa fa-upload"></i> <?php echo $button_upload; ?>
                </button>
                <input type="hidden" name="shipping_custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>"
                       id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"/>
              </div>
            </div>
          <?php } ?><?php if ($custom_field['type'] == 'date') { ?>
            <div class="form-group<?php echo($custom_field['required'] ? ' required' : ''); ?> custom-field">
              <label class="control-label" for="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
              <div class="">
                <input type="date" name="shipping_custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>"
                       placeholder="<?php echo str_replace(':', '', $custom_field['name']); ?>" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control"/>
              </div>
            </div>
          <?php } ?><?php if ($custom_field['type'] == 'datetime') { ?>
            <div class="form-group<?php echo($custom_field['required'] ? ' required' : ''); ?> custom-field">
              <label class="control-label" for="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
              <div class="">
                <input type="datetime-local" name="shipping_custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>"
                       placeholder="<?php echo str_replace(':', '', $custom_field['name']); ?>" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control"/>
              </div>
            </div>
          <?php } ?><?php if ($custom_field['type'] == 'time') { ?>
            <div class="form-group<?php echo($custom_field['required'] ? ' required' : ''); ?> custom-field">
              <label class="control-label" for="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?></label>
              <div class="">
                <input type="time" name="shipping_custom_field[<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>"
                       placeholder="<?php echo str_replace(':', '', $custom_field['name']); ?>" id="input-shipping-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control"/>
              </div>
            </div>
          <?php } ?><?php }
      } ?>
  </div>


  <div class="row">
    <div class="col-md-12">
      <div class="card-columns card-columns-1">
        <div class="card">
          <div class="card-body">

            <div class="shipping-method">
              <h3><i class="fa fa-truck" aria-hidden="true"></i> <?php echo $text_checkout_shipping_method; ?></h3>

                <?php if ($error_warning) { ?>
                  <div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
                <?php } ?>
                <?php if ($shipping_methods) { ?>
                  <p><?php echo $text_shipping_method; ?></p>
                    <?php foreach ($shipping_methods as $shipping_method) { ?>
                    <p><strong><?php echo $shipping_method['title']; ?></strong></p>
                        <?php if (!$shipping_method['error']) { ?><?php foreach ($shipping_method['quote'] as $quote) { ?>
                      <div class="radio">
                        <label>
                            <?php if ($quote['code'] == $code || !$code) { ?><?php $code = $quote['code']; ?>
                              <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" title="<?php echo $quote['title']; ?>" checked="checked"/>
                            <?php } else { ?>
                              <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" title="<?php echo $quote['title']; ?>"/>
                            <?php } ?>
                            <?php echo $quote['title']; ?> - <?php echo $quote['text']; ?></label>
                      </div>
                        <?php }
                        } else { ?>
                      <div class="alert alert-danger"><?php echo $shipping_method['error']; ?></div>
                        <?php }
                    }
                } ?>
            </div>
              <?php } ?>

          </div>
        </div>
      </div>
    </div>
  </div>
</div>
