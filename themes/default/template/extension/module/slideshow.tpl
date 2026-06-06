<div id="slideshow<?php echo $module; ?>" class="swiper banner-slideshow">
  <div class="swiper-wrapper">
      <?php foreach ($banners as $banner) { ?>

        <div class="swiper-slide">
            <?php if ($banner['link']) { ?><?php if ($banner['title']) { ?>
              <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['alt']; ?>" class="img-fluid"/><a class="banner-text" href="<?= $banner['link'] ?>">
                <h2><?php echo $banner['title']; ?></h2>
                    <?= ($banner['description'] ? "<span>" . $banner['description'] . "</span>" : ''); ?>
              </a>
            <?php } else { ?>
              <a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['alt']; ?>" class="img-fluid"/></a>
            <?php } ?><?php } else { ?>
              <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['alt']; ?>" class="img-fluid"/>
            <?php } ?>
        </div>

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