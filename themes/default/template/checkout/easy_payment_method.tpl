<?php if ($error_warning) { ?>
  <div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?></div>
<?php } ?>
<h3><i class="fa fa-credit-card" aria-hidden="true"></i> <?php echo $text_checkout_payment_method; ?></h3>

<?php if ($payment_methods) { ?>
  <p><?php echo $text_payment_method; ?></p>
    <?php foreach ($payment_methods as $payment_method) { ?>
    <div class="radio">
      <label>

        <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>"
               title="<?=(function_exists('html_to_plaintext') ? html_to_plaintext($payment_method['title']) : 'Payment method' )?>"
            <?=( ($payment_method['code'] == $code || !$code) ? 'checked="checked"' : '' )?> />

          <?php echo $payment_method['title']; ?></label>
    </div>

    <?php } ?><?php } ?>
