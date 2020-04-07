<?php if ($error_no_payment) { ?>
  <div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_no_payment; ?></div>
<?php } ?>


<?php if ($payment_methods) {

  // prd($payment_methods);

  // pr($payment_method_code);

  if(empty($payment_method_code) ){
      $payment_method_code = 'stripe'; // Hardcoded // TODO: - Stripe pēc noklusējuma.
  }


//  pr($payment_method_code);


  ?>



    <?php foreach ($payment_methods as $payment_method) { ?>
    <div class="radio">
      <label>
          <?php if ($payment_method['code'] == $payment_method_code || !$payment_method_code) { ?><?php $payment_method_code = $payment_method['code']; ?>
            <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" title="<?php echo html_to_plaintext($payment_method['title']); ?>" checked="checked"/>
          <?php } else { ?>
            <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" ddd title="<?php echo html_to_plaintext($payment_method['title']); ?>"/>
          <?php } ?>
          <?php echo $payment_method['title']; ?></label>
    </div>

    <?php } ?><?php } ?>

<?php debug_template(__FILE__); ?>


