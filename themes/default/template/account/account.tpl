<?php echo $header; ?>
<?php include(__DIR__ . '/../common/_page_open.tpl'); ?>
      <h1><?php echo $heading_title; ?></h1>
      <div class="cp-hub">
        <section class="cp-hub__card">
          <h2 class="cp-hub__title"><i class="fa fa-user-o" aria-hidden="true"></i> <?php echo $text_my_account; ?></h2>
          <ul class="cp-hub__links">
            <li><a href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a></li>
            <li><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>
            <li><a href="<?php echo $address; ?>"><?php echo $text_address; ?></a></li>
            <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
          </ul>
        </section>
        <section class="cp-hub__card">
          <h2 class="cp-hub__title"><i class="fa fa-shopping-bag" aria-hidden="true"></i> <?php echo $text_my_orders; ?></h2>
          <ul class="cp-hub__links">
            <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
            <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
            <?php if ($reward) { ?>
              <li><a href="<?php echo $reward; ?>"><?php echo $text_reward; ?></a></li>
            <?php } ?>
            <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
            <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
            <li><a href="<?php echo $recurring; ?>"><?php echo $text_recurring; ?></a></li>
          </ul>
        </section>
        <?php if ($credit_cards) { ?>
          <section class="cp-hub__card">
            <h2 class="cp-hub__title"><i class="fa fa-credit-card" aria-hidden="true"></i> <?php echo $text_credit_card; ?></h2>
            <ul class="cp-hub__links">
              <?php foreach ($credit_cards as $credit_card) { ?>
                <li><a href="<?php echo $credit_card['href']; ?>"><?php echo $credit_card['name']; ?></a></li>
              <?php } ?>
            </ul>
          </section>
        <?php } ?>
        <section class="cp-hub__card">
          <h2 class="cp-hub__title"><i class="fa fa-envelope-o" aria-hidden="true"></i> <?php echo $text_my_newsletter; ?></h2>
          <ul class="cp-hub__links">
            <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
          </ul>
        </section>
      </div>
<?php include(__DIR__ . '/../common/_page_close.tpl'); ?>
<?php echo $footer; ?>
