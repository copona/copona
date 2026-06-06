<div id="manufacturers<?php echo $module; ?>" class="swiper carousel-brands" style="padding: 10px 0;">
  <div class="swiper-wrapper align-items-center">
    <?php foreach ($manufacturers as $banner) {
      if (!$banner['image']) continue; ?>
      <div class="swiper-slide text-center">
        <?php if ($banner['href']) { ?>
          <a href="<?php echo $banner['href']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['name']; ?>" class="img-fluid"/></a>
        <?php } else { ?>
          <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['name']; ?>" class="img-fluid"/>
        <?php } ?>
      </div>
    <?php } ?>
  </div>
  <div class="swiper-button-prev"></div>
  <div class="swiper-button-next"></div>
</div>
<script>
{
  const el = '#manufacturers<?=$module?>';
  new Swiper(el, {
    loop: true,
    autoplay: { delay: 3000, disableOnInteraction: false },
    navigation: {
      nextEl: el + ' .swiper-button-next',
      prevEl: el + ' .swiper-button-prev',
    },
    breakpoints: {
      0:   { slidesPerView: 2, spaceBetween: 10 },
      576: { slidesPerView: 3, spaceBetween: 20 },
      768: { slidesPerView: 4, spaceBetween: 20 },
      992: { slidesPerView: 6, spaceBetween: 30 },
    }
  });
}
</script>
