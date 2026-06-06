<div id="carousel<?php echo $module; ?>" class="swiper">
  <div class="swiper-wrapper">
    <?php foreach ($banners as $banner) { ?>
      <div class="swiper-slide text-center">
        <?php if ($banner['link']) { ?>
          <a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-fluid" /></a>
        <?php } else { ?>
          <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-fluid" />
        <?php } ?>
      </div>
    <?php } ?>
  </div>
  <div class="swiper-button-prev"></div>
  <div class="swiper-button-next"></div>
</div>
<script>
{
  const el = '#carousel<?=$module?>';
  new Swiper(el, {
    loop: true,
    autoplay: { delay: 3000, disableOnInteraction: false },
    navigation: {
      nextEl: el + ' .swiper-button-next',
      prevEl: el + ' .swiper-button-prev',
    },
    breakpoints: {
      0:   { slidesPerView: 2, spaceBetween: 10 },
      576: { slidesPerView: 3, spaceBetween: 15 },
      768: { slidesPerView: 4, spaceBetween: 20 },
      992: { slidesPerView: 6, spaceBetween: 20 },
    }
  });
}
</script>
