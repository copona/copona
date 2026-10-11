<div id="slideshow<?php echo $module; ?>" class="swiper cp-hero">
  <div class="swiper-wrapper">
    <?php foreach ($banners as $banner) { ?>
      <<?php echo $banner['link'] ? 'a href="' . $banner['link'] . '"' : 'div'; ?> class="swiper-slide cp-hero__slide">
        <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['alt']; ?>"/>
        <?php if ($banner['title']) { ?>
          <span class="cp-hero__caption">
            <span class="cp-hero__title"><?php echo $banner['title']; ?></span>
            <?php if ($banner['description']) { ?><span class="cp-hero__text"><?php echo $banner['description']; ?></span><?php } ?>
          </span>
        <?php } ?>
      </<?php echo $banner['link'] ? 'a' : 'div'; ?>>
    <?php } ?>
  </div>
  <div class="swiper-button-prev"></div>
  <div class="swiper-button-next"></div>
  <div class="swiper-pagination"></div>
</div>
<script>
  {
    const slideshowEl = '#slideshow<?=$module?>';
    let mySwiper = new Swiper(slideshowEl, {
      loop: true,
      autoplay: { delay: 5000, disableOnInteraction: false },
      navigation: {
        nextEl: slideshowEl + ' .swiper-button-next',
        prevEl: slideshowEl + ' .swiper-button-prev',
      },
      pagination: {
        el: slideshowEl + ' .swiper-pagination',
        clickable: true
      }
    });

    mySwiper.el.addEventListener("mouseenter", function () {
      mySwiper.autoplay.stop();
    }, false);
    mySwiper.el.addEventListener("mouseleave", function () {
      mySwiper.autoplay.start();
    }, false);
  }
</script>