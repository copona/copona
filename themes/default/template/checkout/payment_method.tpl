<?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($payment_methods) { ?>

    <?php foreach ($payment_methods as $payment_method) { ?>
        <div class="radio">
          <label>
              <?php if ($payment_method['code'] == $code || !$code) { ?>
                  <?php $code = $payment_method['code']; ?>
                <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" checked="checked" />
            <?php } else { ?>
                <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" />

            <?php } ?>
            <?php echo $payment_method['title']; ?>
            <?php if ($payment_method['terms']) { ?>
                (<?php echo $payment_method['terms']; ?>)
            <?php } ?>
          </label>
        </div>
    <?php } ?>
<?php } ?>


