<?php echo $header; ?>

<div class="category-pd-checkout">
  <div class="container">
      <?php if ($attention) { ?>
        <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $attention; ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php } ?>
    <?php if ($success) { ?>
        <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php } ?>
    <?php if ($error_warning) { ?>

        <?php // echo $error_warning;?>
        <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>
            <?php
            $i = 0;
            foreach ($error_warning as $val) {
                ?>
                <?= $val ?>
                <?php
                $i++;
                if ((count($error_warning) - $i) > 0) {
                    echo "<br>";
                }
                ?>
            <?php } ?>
          <button type="button" class="close" data-dismiss="alert">&times;</button>
        </div>
    <?php } ?>
    <div class="row">
        <?php //echo $content_top;?>
      <div id="content" class="content-cart">
          <?= $cart; ?>
        <div class="onepage-checkout">
          <form action="<?php echo $action; ?>" class="form" method="post">
            <div id="step1">
              <div class="row">
                <div class="col-md-6">
                  <fieldset>
                    <legend <?= (isset($error_warning['shipping_method']) ? 'class="error"' : ''); ?>><?= $text_shipping_method; ?></legend>
                    <label for="country_id"><?= $entry_country; ?>:</label>
                    <select name="country_id" class="large-field form-control">
                      <option value=""><?php echo $text_select; ?></option>
                      <?php foreach ($countries as $country) { ?>
                          <?php if ($country['country_id'] == $country_id) { ?>
                              <option value="<?php echo $country['country_id']; ?>"
                                      selected="selected"><?php echo $country['name']; ?></option>
                                  <?php } else { ?>
                              <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                          <?php } ?>
                      <?php } ?>
                    </select>
                    <div class="zone">
                      <label for="zone_id"><?= $entry_city; ?>:</label>
                      <select id="shipping_method_zone" name="zone_id" class="large-field form-control"></select>
                    </div>
                    <br><br>
                    <div id="shipping-method">
                      <div style="display:none; margin: 0 auto; width: 50%; text-align: center;">
                        <img src="catalog/view/theme/<?php echo $this->config->get('theme_default_directory') ?>/image/AjaxLoader.gif" alt=""/>
                      </div>
                    </div>
                    <div id="shipping_address">
                      <legend><?= $text_shipping_address; ?></legend>
                      <?php $field = 'city'; ?>
                      <?php $field = 'address_1'; ?>
                      <div class="<?= $field; ?><?= (isset($error_warning[$field]) ? " error" : ""); ?>">
                        <span class="field<?= ($$field ? " show-label" : ''); ?><?= (isset($error_warning[$field]) ? " error" : ""); ?>">
                          <label for="<?= $field; ?>"><?= ${"entry_" . $field}; ?>:</label>
                          <input class="form-control" type="text" name="<?= $field; ?>" placeholder="<?= ${"entry_" . $field}; ?>" value="<?= ${$field}; ?>"><br />
                        </span>
                        <input id="validate_address" type="hidden" name="validate_address">
                      </div>
                    </div>
                  </fieldset>

                  <fieldset>
                    <legend><?= $text_contact_buyer; ?></legend>
                    <div class="row">
                      <div class="col-md-6">

                        <?php $field = 'firstname'; ?>
                        <div class="<?= $field; ?><?= (isset($error_warning[$field]) ? " error" : ""); ?>">
                          <span class="field<?= ($$field ? " show-label" : ''); ?><?= (isset($error_warning[$field]) ? " error" : ""); ?>">
                            <label for="<?= $field; ?>"><?= ${"entry_" . $field}; ?>:</label>
                            <input class="form-control" type="text"   name="<?= $field; ?>" placeholder="<?= ${"entry_" . $field}; ?>" value="<?= ${$field}; ?>"><br />
                          </span>
                        </div>
                      </div>
                      <div class="col-md-6">
                          <?php $field = 'lastname'; ?>
                        <div class="<?= $field; ?><?= (isset($error_warning[$field]) ? " error" : ""); ?>">
                          <span class="field<?= ($$field ? " show-label" : ''); ?><?= (isset($error_warning[$field]) ? " error" : ""); ?>">
                            <label for="<?= $field; ?>"><?= ${"entry_" . $field}; ?>:</label>
                            <input class="form-control" type="text"  name="<?= $field; ?>" placeholder="<?= ${"entry_" . $field}; ?>" value="<?= ${$field}; ?>"><br />
                          </span>
                        </div>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-md-6">
                          <?php $field = 'email'; ?>
                        <div class="<?= $field; ?><?= (isset($error_warning[$field]) ? " error" : ""); ?>">
                          <span class="field<?= ($$field ? " show-label" : ''); ?><?= (isset($error_warning[$field]) ? " error" : ""); ?>">
                            <label for="<?= $field; ?>"><?= ${"entry_" . $field}; ?>:</label>
                            <input class="form-control" type="text"  name="<?= $field; ?>" placeholder="<?= ${"entry_" . $field}; ?>" value="<?= ${$field}; ?>"><br />
                          </span>
                        </div>
                      </div>
                      <div class="col-md-6">
                          <?php $field = 'telephone'; ?>
                        <div class="<?= $field; ?><?= (isset($error_warning[$field]) ? " error" : ""); ?>">
                          <span class="field<?= ($$field ? " show-label" : ''); ?><?= (isset($error_warning[$field]) ? " error" : ""); ?>">
                            <label for="<?= $field; ?>"><?= ${"entry_" . $field}; ?>:</label>
                            <input class="form-control" type="text"  name="<?= $field; ?>" placeholder="<?= ${"entry_" . $field}; ?>" value="<?= ${$field}; ?>"><br />
                          </span>
                        </div>
                      </div>
                    </div>
                  </fieldset>
                </div>
                <div class="col-md-6">
                  <fieldset>
                    <legend><?= $text_total_title ?>:</legend>
                    <h4><?= $text_price; ?>: <span id="cart_total_value"><?= number_format($cart_total_value, 2); ?> €</span></h4>
                    <h4><?= $text_shipping; ?>: <span id="order_shipping"><?= number_format($order_shipping, 2); ?> €</span></h4>
                    <h3><?= $text_total_payment; ?>: <span id="order_total"><?= number_format($cart_total_value + $order_shipping, 2); ?> €</span></h3>
                  </fieldset>
                  <fieldset>
                    <div id="payment-method"><?= $payment_method; ?></div>
                  </fieldset>
                  <fieldset>
                    <legend><?= $payer_data_title ?></legend>
                    <div class="radio">
                      <div class="col-md-12" id="payer_data" style="display: none;">
                          <?php
                          if ($customer_group_id == "") {
                              $customer_group_id = "1";
                          }
                          ?>
                        <div class="col-md-12" >
                          <input type="radio" name="customer_group_id" value="1" id="customer_group_id1" <?= $customer_group_id == "1" ? 'checked="checked"' : '' ?> >
                          <label for="customer_group_id1"><?= $text_customer_group_pers ?></label>
                          <br />

                          <div style="display:none">
                            <input type="radio" name="customer_group_id" value="2" id="customer_group_id2" <?= $customer_group_id == "2" ? 'checked="checked"' : '' ?> >
                            <label for="customer_group_id2"><?= $text_customer_group_legal; ?></label>
                          </div>
                        </div>

                        <div id="legal_person_div" style="display: none;">
                          <div class="col-md-12">
                              <?php $field = 'company_name'; ?>
                            <div class="<?= $field; ?><?= (isset($error_warning[$field]) ? " error" : ""); ?>">
                              <span class="field<?= ($$field ? " show-label" : ''); ?><?= (isset($error_warning[$field]) ? " error" : ""); ?>">
                                <label for="<?= $field; ?>"><?= ${"entry_" . $field}; ?>:</label>
                                <input class="form-control" type="text"id="<?= $field; ?>"  name="<?= $field; ?>" placeholder="<?= ${"entry_" . $field}; ?>" value="<?= ${$field}; ?>"><br />
                              </span>
                            </div>
                          </div>

                          <div class="col-md-12">
                              <?php $field = 'reg_num'; ?>
                            <div class="<?= $field; ?><?= (isset($error_warning[$field]) ? " error" : ""); ?>">
                              <span class="field<?= ($$field ? " show-label" : ''); ?><?= (isset($error_warning[$field]) ? " error" : ""); ?>">
                                <label for="<?= $field; ?>"><?= ${"entry_" . $field}; ?>:</label>
                                <input class="form-control" type="text" id="<?= $field; ?>" name="<?= $field; ?>" placeholder="<?= ${"entry_" . $field}; ?>" value="<?= ${$field}; ?>"><br />
                              </span>
                            </div>
                          </div>

                          <div class="col-md-12">
                              <?php $field = 'vat_num'; ?>
                            <div class="<?= $field; ?><?= (isset($error_warning[$field]) ? " error" : ""); ?>">
                              <span class="field<?= ($$field ? " show-label" : ''); ?><?= (isset($error_warning[$field]) ? " error" : ""); ?>">
                                <label for="<?= $field; ?>"><?= ${"entry_" . $field}; ?>:</label>
                                <input class="form-control" type="text" id="<?= $field; ?>" name="<?= $field; ?>" placeholder="<?= ${"entry_" . $field}; ?>" value="<?= ${$field}; ?>"><br />
                              </span>
                            </div>
                          </div>

                          <div class="col-md-12">
                              <?php $field = 'bank_name'; ?>
                            <div class="<?= $field; ?><?= (isset($error_warning[$field]) ? " error" : ""); ?>">
                              <span class="field<?= ($$field ? " show-label" : ''); ?><?= (isset($error_warning[$field]) ? " error" : ""); ?>">
                                <label for="<?= $field; ?>"><?= ${"entry_" . $field}; ?>:</label>
                                <input class="form-control" type="text" id="<?= $field; ?>" name="<?= $field; ?>" placeholder="<?= ${"entry_" . $field}; ?>" value="<?= ${$field}; ?>"><br />
                              </span>
                            </div>
                          </div>

                          <div class="col-md-12">
                              <?php $field = 'bank_code'; ?>
                            <div class="<?= $field; ?><?= (isset($error_warning[$field]) ? " error" : ""); ?>">
                              <span class="field<?= ($$field ? " show-label" : ''); ?><?= (isset($error_warning[$field]) ? " error" : ""); ?>">
                                <label for="<?= $field; ?>"><?= ${"entry_" . $field}; ?>:</label>
                                <input class="form-control" type="text" id="<?= $field; ?>" name="<?= $field; ?>" placeholder="<?= ${"entry_" . $field}; ?>" value="<?= ${$field}; ?>"><br />
                              </span>
                            </div>
                          </div>

                          <div class="col-md-12">
                              <?php $field = 'bank_account'; ?>
                            <div class="<?= $field; ?><?= (isset($error_warning[$field]) ? " error" : ""); ?>">
                              <span class="field<?= ($$field ? " show-label" : ''); ?><?= (isset($error_warning[$field]) ? " error" : ""); ?>">
                                <label for="<?= $field; ?>"><?= ${"entry_" . $field}; ?>:</label>
                                <input class="form-control" type="text" id="<?= $field; ?>" name="<?= $field; ?>" placeholder="<?= ${"entry_" . $field}; ?>" value="<?= ${$field}; ?>"><br />
                              </span>
                            </div>
                          </div>

                          <div class="col-md-12">
                              <?php $field = 'address_2'; ?>
                            <div class="<?= $field; ?><?= (isset($error_warning[$field]) ? " error" : ""); ?>">
                              <span class="field<?= ($$field ? " show-label" : ''); ?><?= (isset($error_warning[$field]) ? " error" : ""); ?>">
                                <label for="<?= $field; ?>"><?= ${"entry_" . $field}; ?>:</label>
                                <input class="form-control" type="text" id="<?= $field; ?>" name="<?= $field; ?>" placeholder="<?= ${"entry_" . $field}; ?>" value="<?= ${$field}; ?>"><br />
                              </span>
                            </div>
                          </div>

                        </div>
                      </div>
                    </div>
                  </fieldset>
                  <div class="row">
                    <div class="col-md-12">
                      <div class="<?= (isset($error_warning['warning']) ? " error" : ""); ?>">
                        <label for="agree" id="agree_label">
                          <input id="agree" type="checkbox" name="agree" <?= ($agree ? 'checked' : ''); ?>>
                          <?= $text_agree; ?></label>
                        <div align="right">
                          <span class="wait" style="display: none; margin-right: 10px;">&nbsp;<img src="catalog/view/theme/<?php echo $this->config->get('theme_default_directory') ?>/image/AjaxLoader.gif" width="20px" alt="Loading" /></span>
                          <input type="submit" value="<?= $text_make_order; ?>" id="button-payment-method" class="btn btn-primary" />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div> <!-- Close div row-->
            </div>
          </form>
        </div>
        <!-- Close div row -->
        <?php
        // Checkout Javascript
        require_once('checkout.js.tpl');
        ?>

        <?php echo $content_bottom; ?></div>
      <?php echo $column_right; ?></div>
  </div>
</div>
<?php echo $footer; ?>