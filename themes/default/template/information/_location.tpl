<?php
// One store location card. Expects $loc = [name, image, address, geocode, telephone, fax, open, comment].
?>
<div class="cp-location">
  <?php if ($loc['image']) { ?>
    <img src="<?php echo $loc['image']; ?>" alt="<?php echo $loc['name']; ?>" class="cp-location__img" />
  <?php } ?>
  <dl class="cp-location__facts">
    <div>
      <dt><?php echo $loc['name']; ?></dt>
      <dd><address><?php echo $loc['address']; ?></address>
        <?php if ($loc['geocode']) { ?>
          <a href="https://maps.google.com/maps?q=<?php echo urlencode($loc['geocode']); ?>&hl=<?php echo $geocode_hl; ?>&t=m&z=15" target="_blank" rel="noopener" class="cp-link-accent"><i class="fa fa-map-marker" aria-hidden="true"></i> <?php echo $button_map; ?></a>
        <?php } ?>
      </dd>
    </div>
    <?php if ($loc['telephone']) { ?>
      <div><dt><?php echo $text_telephone; ?></dt><dd><a href="tel:<?php echo preg_replace('/[^0-9+]/', '', strip_tags($loc['telephone'])); ?>"><?php echo $loc['telephone']; ?></a></dd></div>
    <?php } ?>
    <?php if ($loc['fax']) { ?>
      <div><dt><?php echo $text_fax; ?></dt><dd><?php echo $loc['fax']; ?></dd></div>
    <?php } ?>
    <?php if ($loc['open']) { ?>
      <div><dt><?php echo $text_open; ?></dt><dd><?php echo $loc['open']; ?></dd></div>
    <?php } ?>
    <?php if ($loc['comment']) { ?>
      <div><dt><?php echo $text_comment; ?></dt><dd><?php echo $loc['comment']; ?></dd></div>
    <?php } ?>
  </dl>
</div>
