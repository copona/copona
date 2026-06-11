<?php echo $header; ?>

<div class="category-pd-checkout">
  <div class="container">
    <?php if ($attention) { ?>
      <div class="alert alert-info"><i class="fa fa-info-circle"></i>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <?php echo $attention; ?>
      </div>
    <?php } ?>
    <?php if ($success) { ?>
      <div class="alert alert-success"><i class="fa fa-check-circle"></i>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <?php echo $success; ?>
      </div>
    <?php } ?>
    <?php if ($error_warning) { ?>
      <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        <?php foreach ($error_warning as $val) { echo $val . '<br>'; } ?>
      </div>
    <?php } ?>

    <div class="row">
      <div id="content" class="content-cart col-12">
        <div class="onepage-checkout">

          <!-- Cart contents -->
          <div class="row mb-4">
            <div class="col-12">
              <div id="cart-info"></div>
            </div>
          </div>

          <form action="<?php echo $action; ?>" class="form" method="post">
            <div id="step1">
              <div class="row g-4">

                <!-- LEFT: Shipping + Customer info -->
                <div class="col-md-7">

                  <!-- Shipping method -->
                  <div class="card mb-3<?= isset($error_warning['shipping_method']) ? ' border-danger' : ''; ?>">
                    <div class="card-header fw-semibold small"><?= $text_shipping_method; ?></div>
                    <div class="card-body">
                      <div class="mb-3">
                        <label class="form-label small" for="country_id"><?= $entry_country; ?></label>
                        <select name="country_id" id="country_id" class="form-select form-select-sm">
                          <option value=""><?php echo $text_select; ?></option>
                          <?php foreach ($countries as $country) { ?>
                            <option value="<?= $country['country_id']; ?>"<?= ($country['country_id'] == $country_id ? ' selected' : ''); ?>><?= $country['name']; ?></option>
                          <?php } ?>
                        </select>
                      </div>
                      <div class="mb-3 zone">
                        <label class="form-label small" for="shipping_method_zone"><?= $entry_city; ?></label>
                        <select id="shipping_method_zone" name="zone_id" class="form-select form-select-sm"></select>
                      </div>
                      <div id="shipping-method">
                        <div style="display:none;text-align:center">
                          <img src="themes/<?php echo Config::get('theme_default_directory') ?>/assets/img/AjaxLoader.gif" alt=""/>
                        </div>
                      </div>
                      <div id="shipping_address">
                        <p class="fw-semibold small mb-2"><?= $text_shipping_address; ?></p>
                        <?php $field = 'address_1'; ?>
                        <div class="mb-0<?= isset($error_warning[$field]) ? ' has-validation' : ''; ?>">
                          <label class="form-label small" for="<?= $field; ?>"><?= ${"entry_" . $field}; ?></label>
                          <input class="form-control form-control-sm<?= isset($error_warning[$field]) ? ' is-invalid' : ''; ?>" type="text" name="<?= $field; ?>" id="<?= $field; ?>" placeholder="<?= ${"entry_" . $field}; ?>" value="<?= ${$field}; ?>">
                          <?php if (isset($error_warning[$field])) { ?><div class="invalid-feedback"><?= $error_warning[$field]; ?></div><?php } ?>
                        </div>
                        <input id="validate_address" type="hidden" name="validate_address">
                      </div>
                    </div>
                  </div>

                  <!-- Customer information -->
                  <div class="card mb-3">
                    <div class="card-header fw-semibold small"><?= $text_contact_buyer; ?></div>
                    <div class="card-body">
                      <div class="row g-3 mb-3">
                        <div class="col-sm-6">
                          <?php $field = 'firstname'; ?>
                          <label class="form-label small" for="<?= $field; ?>"><?= ${"entry_" . $field}; ?></label>
                          <input class="form-control form-control-sm<?= isset($error_warning[$field]) ? ' is-invalid' : ''; ?>" type="text" name="<?= $field; ?>" id="<?= $field; ?>" placeholder="<?= ${"entry_" . $field}; ?>" value="<?= ${$field}; ?>">
                          <?php if (isset($error_warning[$field])) { ?><div class="invalid-feedback"><?= $error_warning[$field]; ?></div><?php } ?>
                        </div>
                        <div class="col-sm-6">
                          <?php $field = 'lastname'; ?>
                          <label class="form-label small" for="<?= $field; ?>"><?= ${"entry_" . $field}; ?></label>
                          <input class="form-control form-control-sm<?= isset($error_warning[$field]) ? ' is-invalid' : ''; ?>" type="text" name="<?= $field; ?>" id="<?= $field; ?>" placeholder="<?= ${"entry_" . $field}; ?>" value="<?= ${$field}; ?>">
                          <?php if (isset($error_warning[$field])) { ?><div class="invalid-feedback"><?= $error_warning[$field]; ?></div><?php } ?>
                        </div>
                      </div>
                      <div class="row g-3">
                        <div class="col-sm-6">
                          <?php $field = 'email'; ?>
                          <label class="form-label small" for="<?= $field; ?>"><?= ${"entry_" . $field}; ?></label>
                          <input class="form-control form-control-sm<?= isset($error_warning[$field]) ? ' is-invalid' : ''; ?>" type="email" name="<?= $field; ?>" id="<?= $field; ?>" placeholder="<?= ${"entry_" . $field}; ?>" value="<?= ${$field}; ?>">
                          <?php if (isset($error_warning[$field])) { ?><div class="invalid-feedback"><?= $error_warning[$field]; ?></div><?php } ?>
                        </div>
                        <div class="col-sm-6">
                          <?php $field = 'telephone'; ?>
                          <label class="form-label small" for="<?= $field; ?>"><?= ${"entry_" . $field}; ?></label>
                          <input class="form-control form-control-sm<?= isset($error_warning[$field]) ? ' is-invalid' : ''; ?>" type="tel" name="<?= $field; ?>" id="<?= $field; ?>" placeholder="<?= ${"entry_" . $field}; ?>" value="<?= ${$field}; ?>">
                          <?php if (isset($error_warning[$field])) { ?><div class="invalid-feedback"><?= $error_warning[$field]; ?></div><?php } ?>
                        </div>
                      </div>
                    </div>
                  </div>

                </div>

                <!-- RIGHT: Order summary + Payment -->
                <div class="col-md-5">

                  <!-- Order totals -->
                  <div class="cart-summary mb-3">
                    <div class="cart-summary-heading"><?= $text_total_title ?></div>
                    <div class="cart-summary-line">
                      <span><?= $text_price; ?></span>
                      <span id="cart_total_value"><?= number_format($cart_total_value, 2); ?> €</span>
                    </div>
                    <div class="cart-summary-line">
                      <span><?= $text_shipping; ?></span>
                      <span id="order_shipping"><?= number_format($order_shipping, 2); ?> €</span>
                    </div>
                    <div class="cart-summary-line cart-summary-total">
                      <span><?= $text_total_payment; ?></span>
                      <span id="order_total"><?= number_format($cart_total_value + $order_shipping, 2); ?></span>
                    </div>
                  </div>

                  <!-- Payment method -->
                  <div class="card mb-3">
                    <div class="card-body">
                      <div id="payment-method"><?= $payment_method; ?></div>
                    </div>
                  </div>

                  <!-- Payer data (person / legal entity) -->
                  <div class="card mb-3">
                    <div class="card-header fw-semibold small"><?= $payer_data_title ?></div>
                    <div class="card-body">
                      <div id="payer_data" style="display:none">
                        <?php if ($customer_group_id == '') { $customer_group_id = '1'; } ?>
                        <div class="form-check mb-2">
                          <input class="form-check-input" type="radio" name="customer_group_id" value="1" id="customer_group_id1" <?= $customer_group_id == '1' ? 'checked' : ''; ?>>
                          <label class="form-check-label small" for="customer_group_id1"><?= $text_customer_group_pers ?></label>
                        </div>
                        <div style="display:none">
                          <input class="form-check-input" type="radio" name="customer_group_id" value="2" id="customer_group_id2" <?= $customer_group_id == '2' ? 'checked' : ''; ?>>
                          <label class="form-check-label small" for="customer_group_id2"><?= $text_customer_group_legal; ?></label>
                        </div>
                        <div id="legal_person_div" style="display:none">
                          <?php foreach (['company_name', 'reg_num', 'vat_num', 'bank_name', 'bank_code', 'bank_account', 'address_2'] as $field) { ?>
                            <div class="mb-2">
                              <label class="form-label small" for="<?= $field; ?>"><?= ${"entry_" . $field}; ?></label>
                              <input class="form-control form-control-sm<?= isset($error_warning[$field]) ? ' is-invalid' : ''; ?>" type="text" id="<?= $field; ?>" name="serial[<?= $field; ?>]" placeholder="<?= ${"entry_" . $field}; ?>" value="<?= ${$field}; ?>">
                              <?php if (isset($error_warning[$field])) { ?><div class="invalid-feedback"><?= $error_warning[$field]; ?></div><?php } ?>
                            </div>
                          <?php } ?>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- Agree + submit -->
                  <div class="d-flex align-items-center justify-content-between gap-3<?= isset($error_warning['warning']) ? ' has-validation' : ''; ?>">
                    <div class="form-check">
                      <input class="form-check-input" id="agree" type="checkbox" name="agree" <?= ($agree ? 'checked' : ''); ?>>
                      <label class="form-check-label small" for="agree"><?= $text_agree; ?></label>
                    </div>
                    <button type="submit" id="button-payment-method" class="btn btn-primary">
                      <span class="wait" style="display:none"><i class="fa fa-spinner fa-pulse fa-fw me-1"></i></span>
                      <?= $text_make_order; ?>
                    </button>
                  </div>

                </div>
              </div>
            </div>
          </form>
        </div>
        <script>
          <?php require_once('checkout.js.tpl'); ?>
        </script>
        <?php echo $content_bottom; ?>
      </div>
    </div>
  </div>
</div>
<script>
  $(function() { cart.get('#cart-info'); });
</script>
<?php echo $footer; ?>
