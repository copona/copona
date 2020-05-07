<?php if ($error_no_payment) { ?>
    <div class="alert alert-warning"><i class="fa fa-exclamation-circle"></i> <?php echo $error_no_payment; ?></div>
<?php } ?>


<?php if ($payment_methods) {
    if (empty($payment_method_code)) {
        $payment_method_code = 'swedbank_portal.cc'; // Hardcoded // TODO: - Stripe should be by-default .
    }
    // echo $payment_method_code ;

    ?>
    <?php foreach ($payment_methods as $payment_method) { ?>
        <?php
        if (!empty($payment_method['template'])) {
            echo $payment_method['template'];
            continue;
        }
        ?>

        <div class="radio<?= htmlspecialchars($payment_method['code']) ?>">
            <label>
                <?php if ($payment_method['code'] == $payment_method_code || !$payment_method_code) {
                    $payment_method_code = $payment_method['code'];
                    ?>
                    <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" title="<?php echo html_to_plaintext($payment_method['title']); ?>"
                           checked="checked"/>
                <?php } else { ?>
                    <input type="radio" name="payment_method" value="<?php echo $payment_method['code']; ?>" title="<?php echo html_to_plaintext($payment_method['title']); ?>"/>
                <?php } ?>
                <?php echo $payment_method['title_html'] ?? $payment_method['title']; ?>
            </label>
        </div>

    <?php } ?><?php } ?>

<?php debug_template(__FILE__); ?>



