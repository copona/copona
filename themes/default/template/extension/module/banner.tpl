<div id="banner<?php echo $module; ?>" class="swiper">
  <div class="swiper-wrapper">
    <?php foreach ($banners as $banner) { ?>
      <div class="swiper-slide">
        <?php if ($banner['link']) { ?>
          <a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-fluid" /></a>
        <?php } else { ?>
          <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-fluid" />
        <?php } ?>
      </div>
    <?php } ?>
  </div>
  <div class="swiper-pagination"></div>
</div>
<script>
{
  const el = '#banner<?=$module?>';
  new Swiper(el, {
    loop: true,
    effect: 'fade',
    autoplay: { delay: 3000, disableOnInteraction: false },
    pagination: {
      el: el + ' .swiper-pagination',
      clickable: true,
    },
  });
}
</script>
