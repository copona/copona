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

    <div class="form-group required col-md-12">
        <label class="control-label" for="input-payment-firstname"><?php echo $entry_firstname; ?></label>
        <input type="text" name="firstname" value="<?php if (isset($address['firstname'])) {
            echo $address['firstname'];
        } elseif (isset($firstname)) {
            echo $firstname;
        } ?>" placeholder="<?php echo str_replace(':', '', $entry_firstname); ?>" id="input-payment-firstname" class="form-control" <?php if (isset($customer_id)) { ?> readonly<?php } ?>/>
    </div>
    <div class="form-group required col-md-12">
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