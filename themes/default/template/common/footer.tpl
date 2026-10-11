<footer class="cp-footer">
  <div class="container cp-footer__grid">
    <div class="cp-footer__brand">
      <a href="<?php echo $home; ?>" class="cp-wordmark"><?php echo $name; ?></a>
      <?php if (!empty($config_social_media)) { ?>
        <div class="cp-footer__social">
          <?php foreach ($config_social_media as $social_media) { ?>
            <a target="_blank" rel="noopener" href="<?= $social_media['link'] ?>"><img src="image/<?= $social_media['icon'] ?>" alt="<?= $social_media['name'] ?>" title="<?= $social_media['name'] ?>"/></a>
          <?php } ?>
        </div>
      <?php } ?>
    </div>
    <?php if ($informations) { ?>
      <nav aria-label="<?php echo $text_information; ?>">
        <h5><?php echo $text_information; ?></h5>
        <?php foreach ($informations as $information) { ?>
          <a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a>
        <?php } ?>
      </nav>
    <?php } ?>
    <nav aria-label="<?php echo $text_service; ?>">
      <h5><?php echo $text_service; ?></h5>
      <a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a>
      <a href="<?php echo $return; ?>"><?php echo $text_return; ?></a>
      <a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a>
    </nav>
    <nav aria-label="<?php echo $text_extra; ?>">
      <h5><?php echo $text_extra; ?></h5>
      <a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a>
      <a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a>
      <a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a>
      <a href="<?php echo $special; ?>"><?php echo $text_special; ?></a>
    </nav>
    <nav aria-label="<?php echo $text_account; ?>">
      <h5><?php echo $text_account; ?></h5>
      <a href="<?php echo $account; ?>"><?php echo $text_account; ?></a>
      <a href="<?php echo $order; ?>"><?php echo $text_order; ?></a>
      <a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a>
      <a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a>
    </nav>
  </div>
  <div class="cp-footer__bottom">
    <div class="container"><?= $powered ?></div>
  </div>
</footer>
<?php foreach ($scripts as $script) { ?>
  <script src="<?php echo $script; ?>"></script>
<?php } ?>
</body>
</html>
