<?php echo $header; ?>

    <div class="container">
        <ul class="breadcrumb">
            <?php foreach ($breadcrumbs as $breadcrumb) { ?>
                <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
            <?php } ?>
        </ul>
        <?php if ($error_warning) { ?>
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php } ?>
        <div class="row"><?php echo $column_left; ?>
            <?php if ($column_left && $column_right) { ?><?php $class = 'col-sm-6'; ?><?php } elseif ($column_left || $column_right) { ?><?php $class = 'col-sm-9'; ?><?php } else { ?><?php $class = 'col-sm-12'; ?><?php } ?>
            <div id="content" class="<?php echo $class; ?>">
                <h2><?php echo $heading_title; ?></h2>

                <?php if (!isset($address)) { ?>
                    <div class="row">
                        <div class="col-md-12">

                            <p class="well login-checkout"><?php echo $text_returning_customer; ?> &nbsp;<a href="#" onclick="jQuery('.login-form').toggle();return false;"
                                                                                                            class="btn btn-danger"><?php echo $text_i_am_returning_customer; ?></a></p>

                            <div class="login-form registerbox clearfix" style="display:none">
                                <div class="row">
                                    <div class="col-md-12 message"></div>
                                    <form class="form-inline" role="form">
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label class="control-label" for="input-email"><?php echo $entry_email; ?></label>
                                                <input type="text" name="email" value="" placeholder="<?php echo str_replace(':', '', $entry_email); ?>" id="input-email" class="form-control"/>
                                            </div>&nbsp;&nbsp;
                                            <div class="form-group">
                                                <label class="control-label" for="input-password"><?php echo $entry_password; ?></label>
                                                <input type="password" name="password" value="" placeholder="<?php echo str_replace(':', '', $entry_password); ?>" id="input-password" class="form-control"/>
                                            </div>
                                        </div>
                                        <div class="form-group col-md-4">
                                            <input type="button" value="<?php echo $button_login; ?>" id="button-login" data-loading-text="<?php if (isset($text_loading)) {
                                                echo $text_loading;
                                            } else echo 'loading ...' ?>" class="btn btn-login"/>
                                            <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>

                <div class="row box checkout_form box-container">
                    <div class="col-md-4 register_block">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-columns card-columns-1">
                                    <div class="card">
                                        <div class="card-body">

                                            <div class="row">

                                                <h3><i class="fa fa-user"></i> <?php echo $text_checkout_account; ?></h3>
                                                <div class="form-group">
                                                    <label class="control-label"><?php echo $entry_customer_group; ?></label>
                                                    <?php foreach ($customer_groups as $customer_group) { ?><?php if ($customer_group['customer_group_id'] == $customer_group_id) { ?>
                                                        <div class="radio">
                                                            <label>
                                                                <input type="radio" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>" checked="checked"/>
                                                                <?php echo $customer_group['name']; ?></label>
                                                        </div>
                                                    <?php } else { ?>
                                                        <div class="radio">
                                                            <label>
                                                                <input type="radio" checked="checked" name="customer_group_id" value="<?php echo $customer_group['customer_group_id']; ?>"/>
                                                                <?php echo $customer_group['name']; ?></label>
                                                        </div>
                                                    <?php } ?><?php } ?>
                                                </div>

                                                <div class="form-group required col-md-6">
                                                    <label class="control-label" for="input-payment-firstname"><?php echo $entry_firstname; ?></label>
                                                    <input type="text" name="firstname" value="<?php if (isset($address['firstname'])) {
                                                        echo $address['firstname'];
                                                    } elseif (isset($firstname)) {
                                                        echo $firstname;
                                                    } ?>" placeholder="<?php echo str_replace(':', '', $entry_firstname); ?>" id="input-payment-firstname" class="form-control" <?php if (isset($customer_id)) { ?> readonly<?php } ?>/>
                                                </div>
                                                <div class="form-group required col-md-6">
                                                    <label class="control-label" for="input-payment-lastname"><?php echo $entry_lastname; ?></label>
                                                    <input type="text" name="lastname" value="<?php if (isset($lastname)) {
                                                        echo $lastname;
                                                    } ?>" placeholder="<?php echo str_replace(':', '', $entry_lastname); ?>" id="input-payment-lastname" class="form-control" <?php if (isset($customer_id)) { ?> readonly<?php } ?>/>
                                                </div>
                                                <div class="form-group required col-md-12">
                                                    <label class="control-label" for="input-payment-email"><?php echo $entry_email; ?></label>
                                                    <input type="text" name="email" value="<?php if (isset($email)) {
                                                        echo $email;
                                                    } ?>" placeholder="<?php echo str_replace(':', '', $entry_email); ?>" id="input-payment-email" class="form-control" <?php if (isset($customer_id)) { ?> readonly<?php } ?>/>
                                                </div>
                                                <div class="form-group required  col-md-12">
                                                    <label class="control-label" for="input-payment-telephone"><?php echo $entry_telephone; ?></label>
                                                    <input type="text" name="telephone" value="<?php if (isset($telephone)) {
                                                        echo $telephone;
                                                    } ?>" placeholder="<?php echo str_replace(':', '', $entry_telephone); ?>" id="input-payment-telephone" class="form-control" <?php if (isset($customer_id)) { ?> readonly<?php } ?>/>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="card">
                                        <div class="card-body">
                                            <h3 class="clearfix"><i class="fa fa-map-marker" aria-hidden="true"></i> <?php echo $text_checkout_payment_address; ?></h3>

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
                                            <?php } ?><?php if (isset($customer_id)) { ?>
                                                <div class="radio">
                                                    <label>
                                                        <input type="radio" name="payment_address" value="new" onclick="jQuery('#payment-address-new').show();"/>
                                                        <?php echo $text_address_new; ?></label>
                                                </div>
                                            <?php } ?><?php } ?>
                                        </div>

                                        <div id="payment-address-new" <?php if (isset($customer_id) && $addresses) { ?>    style="display:none"<?php } ?>>
                                            <?php // if (!$checkout_hide_company){?>
                                            <div class="form-group col-md-6">
                                                <label class="control-label" for="input-payment-company"><?php echo $entry_company; ?></label>
                                                <input type="text" name="company" value="<?php if (isset($company)) {
                                                    echo $company;
                                                } ?>" placeholder="<?php echo str_replace(':', '', $entry_company); ?>" id="input-payment-company" class="form-control"/>
                                            </div>
                                            <?php //} else {?>
                                            <input type="hidden" name="company" value=""/>
                                            <?php //}?>

                                            <?php if (isset($entry_company_id)) { ?><?php if (!$checkout_hide_company_id) { ?>
                                                <div class="form-group col-md-6">
                                                    <label class="control-label" for="input-payment-company"><?php echo $entry_company_id; ?></label>
                                                    <input type="text" name="company_id" value="<?php if (isset($company_id)) {
                                                        echo $company_id;
                                                    } ?>" placeholder="<?php echo str_replace(':', '', $entry_company_id); ?>" id="input-payment-company-id" class="form-control"/>
                                                </div>
                                            <?php } else { ?>
                                                <input type="hidden" name="company_id" value=""/>
                                            <?php } ?>

                                                <?php if (!$checkout_hide_tax_id) { ?>
                                                    <div class="form-group col-md-6">
                                                        <label class="control-label" for="input-tax-id-"><?php echo $entry_tax_id; ?></label>
                                                        <input type="text" name="tax_id" value="<?php if (isset($tax_id)) {
                                                            echo $tax_id;
                                                        } ?>" placeholder="<?php echo str_replace(':', '', $entry_tax_id); ?>" id="input-tax-id-" class="form-control"/>
                                                    </div>
                                                <?php } else { ?>
                                                    <input type="hidden" name="tax_id" value=""/>
                                                <?php }
                                            } ?>

                                            <div class="form-group required col-md-12">
                                                <label class="control-label" for="input-payment-address-1"><?php echo $entry_address_1; ?></label>
                                                <input type="text" name="address_1" value="<?php if (isset($address_1)) {
                                                    echo $address_1;
                                                } ?>" placeholder="<?php echo str_replace(':', '', $entry_address_1); ?>" id="input-payment-address-1" class="form-control"/>
                                            </div>
                                            <div class="form-group col-md-12">
                                                <label class="control-label" for="input-payment-address-2"><?php echo $entry_address_2; ?></label>
                                                <input type="text" name="address_2" value="<?php if (isset($address_2)) {
                                                    echo $address_2;
                                                } ?>" placeholder="<?php echo str_replace(':', '', $entry_address_2); ?>" id="input-payment-address-2" class="form-control"/>
                                            </div>
                                            <div class="form-group required col-md-6">
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
                                            <div class="form-group required col-md-6">
                                                <label class="control-label" for="input-payment-zone"><?php echo $entry_zone;; ?></label>
                                                <select name="zone_id" id="input-payment-zone" class="form-control">
                                                </select>
                                            </div>
                                            <div class="form-group required col-md-6">
                                                <label class="control-label" for="input-payment-city"><?php echo $entry_city; ?></label>
                                                <input type="text" name="city" value="<?php if (isset($city)) {
                                                    echo $city;
                                                } ?>" placeholder="<?php echo str_replace(':', '', $entry_city); ?>" id="input-payment-city" class="form-control"/>
                                            </div>
                                            <div class="form-group required col-md-6">
                                                <label class="control-label" for="input-payment-postcode"><?php echo $entry_postcode; ?></label>
                                                <input type="text" name="postcode" value="<?php if (isset($postcode)) {
                                                    echo $postcode;
                                                } ?>" placeholder="<?php echo str_replace(':', '', $entry_postcode); ?>" id="input-payment-postcode" class="form-control"/>
                                            </div>
                                        </div>

                                        <?php if (!isset($customer_id)) { ?>
                                            <div class="form-group col-md-12">
                                                <label>
                                                    <input type="checkbox" name="register" onclick="jQuery('.register-form').toggle()">&nbsp;<?php echo $text_register; ?></label>
                                            </div>
                                        <?php } ?>


                                        <div class="register-form" style="display:none">
                                            <div class="form-group required col-md-6">
                                                <label class="control-label" for="input-payment-password"><?php echo $entry_password; ?></label>
                                                <input type="password" name="password" value="" placeholder="<?php echo str_replace(':', '', $entry_password); ?>" id="input-payment-password" class="form-control"/>
                                            </div>
                                            <div class="form-group required col-md-6">
                                                <label class="control-label" for="input-payment-confirm"><?php echo $entry_confirm; ?></label>
                                                <input type="password" name="confirm" value="" placeholder="<?php echo str_replace(':', '', $entry_confirm); ?>" id="input-payment-confirm" class="form-control"/>
                                            </div>
                                        </div>
                                        <?php /*} else {?>
				  <div class="form-group required col-md-6">
				    <label class="control-label" for="input-payment-firstname"><?php echo $entry_firstname; ?></label>
				    <p><?php echo $firstname; ?></p>
				  </div>
				  <div class="form-group required col-md-6">
				    <label class="control-label" for="input-payment-lastname"><?php echo $entry_lastname; ?></label>
				    <p><?php echo $lastname; ?></p>
				  </div>
				  <div class="form-group required  col-md-6">
				    <label class="control-label" for="input-payment-email"><?php echo $entry_email; ?></label>
				    <p><?php echo $email; ?></p>
				  </div>
				  <div class="form-group required  col-md-6">
				    <label class="control-label" for="input-payment-telephone"><?php echo $entry_telephone; ?></label>
				    <p><?php echo $telephone; ?></p>
				  </div>
				  <?php if (!empty($fax)) {?>
				  <div class="form-group col-md-12" >
				    <label class="control-label" for="input-payment-fax"><?php echo $entry_fax; ?></label>
				    <p><?php echo $fax; ?></p>
				  </div>
				  <?php }?>

				  <div class="col-md-12">
				    <h3 class="clearfix"><?php echo $text_your_address; ?></h3>
				  </div>

				  <?php if (!empty($address['company'])) {?>
				  <div class="form-group col-md-6" >
				    <label class="control-label" for="input-payment-company"><?php echo $entry_company; ?></label>
				    <p><?php echo $address['company']; ?></p>
				  </div>
				  <?php }?>

				  <?php if (!empty($address['address_1'])) {?>
				  <div class="form-group required col-md-12">
				    <label class="control-label" for="input-payment-address-1"><?php echo $entry_address_1; ?></label>
				    <p><?php echo $address['address_1'];?></p>
				  </div>
				   <?php }?>

				  <?php if (!empty($address['address_2'])) {?>
				  <div class="form-group col-md-12">
				    <label class="control-label" for="input-payment-address-2"><?php echo $entry_address_2; ?></label>
				    <p><?php echo $address['address_2'];?></p>
				  </div>
				   <?php }?>

				  <div class="form-group required col-md-6">
				    <label class="control-label" for="input-payment-country"><?php echo $entry_country; ?></label>
				    <p><?php echo $address['country'];?></p>
				  </div>
				  <div class="form-group required col-md-6">
				    <label class="control-label" for="input-payment-zone"><?php echo $entry_zone;; ?></label>
				    <p><?php echo $address['zone'];?></p>
				  </div>
				  <div class="form-group required col-md-6">
				    <label class="control-label" for="input-payment-city"><?php echo $entry_city; ?></label>
				    <p><?php echo $address['city'];?></p>
				  </div>
				  <div class="form-group required col-md-6" >
				    <label class="control-label" for="input-payment-postcode"><?php echo $entry_postcode; ?></label>
				    <p><?php echo $address['postcode'];?></p>
				  </div>

			<?php } */ ?>

                                    </div>

                                </div>
                            </div><!-- -->
                        </div>
                    </div>

                    <div class="col-md-4">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-columns card-columns-1">
                                    <div class="card">
                                        <div class="card-body">

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
                                                <?php } ?><?php if (isset($customer_id)) { ?>
                                                    <div class="radio">
                                                        <label>
                                                            <input type="radio" name="shipping_address" value="new" onclick="jQuery('#shipping-new').show();"/>
                                                            <?php echo $text_address_new; ?></label>
                                                    </div>
                                                <?php } ?><?php } ?>
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
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
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

                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-columns card-columns-1">
                                    <div class="card">
                                        <div class="card-body">

                                            <div class="payment-method">

                                                <?php if ($error_warning) { ?>
                                                    <div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
                                                <?php } ?>
                                                <h3><i class="fa fa-credit-card" aria-hidden="true"></i> <?php echo $text_checkout_payment_method; ?></h3>

                                                <?php if ($payment_methods) { ?>
                                                    <p><?php echo $text_payment_method; ?></p>
                                                    <?php foreach ($payment_methods as $payment_method) { ?>
                                                        <div class="radio">
                                                            <label>
                                                                <?php if ($payment_method['code'] == $code || !$code) { ?><?php $code = $payment_method['code']; ?>
                                                                    <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" title="<?php echo $payment_method['title']; ?>" checked="checked"/>
                                                                <?php } else { ?>
                                                                    <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" title="<?php echo $payment_method['title']; ?>"/>
                                                                <?php } ?>
                                                                <?php echo $payment_method['title']; ?>
                                                            </label>
                                                        </div>
                                                    <?php } ?><?php } ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <p><strong><?php echo $text_comments; ?></strong></p>
                        <p>
                            <textarea name="comment" rows="3" class="form-control"><?php echo $comment; ?></textarea>
                        </p>


                    </div>
                    <div class="col-md-4">
                        <div class="your_order">
                            <h3><i class="fa fa-shopping-basket" aria-hidden="true"></i> <?php echo $text_cart; ?></h3>


                            <?php echo $cart_table ?>

                            <?php if ($text_agree) { ?>
                                <div class="buttons clearfix" id="terms-id">
                                    <div class="pull-right"><?php echo $text_agree; ?>
                                        <?php if ($agree) { ?>
                                            <input type="checkbox" name="agree" value="1" checked="checked"/>
                                        <?php } else { ?>
                                            <input type="checkbox" name="agree" value="1"/>
                                        <?php } ?>
                                        &nbsp;
                                    </div>
                                </div>
                            <?php } else { ?>
                                <div class="buttons">
                                    <div class="pull-right">
                                        <input type="button" value="<?php echo $button_continue; ?>" id="button-payment-method" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary"/>
                                    </div>
                                </div>
                            <?php } ?>

                            <div class="payment clearfix">
                                <?php if ($payment) { ?>
                                    <p><?php $payment; ?></p>
                                <?php } ?>
                            </div>

                            <div class="confirm_button">
                                <input type="button" class="btn btn-primary" data-loading-text="<?php if (isset($text_loading)) {
                                    echo $text_loading;
                                } else echo 'loading ...' ?>" id="button-register" value="<?php echo $heading_title; ?>">
                            </div>

                        </div>
                    </div>
                </div>
                <?php echo $content_bottom; ?></div>
            <?php echo $column_right; ?></div>
    </div>


    <script type="text/javascript">
        <?php include "easy_checkout_script.js" ?>
    </script>

<?php echo $footer; ?>