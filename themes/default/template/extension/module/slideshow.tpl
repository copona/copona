<div id="slideshow<?php echo $module; ?>" class="banner-slideshow owl-carousel" style="opacity: 1;">
     <div class = "swiper-wrapper">
       <?php foreach ($banners as $banner) { ?>
   
      <div class="swiper-slide">
          <?php if ($banner['link']) { ?>
              <?php if ($banner['title']) { ?>
              <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive"/>
              <a class="banner-text" href="<?=$banner['link'] ?>">
                <h2><?php echo $banner['title']; ?></h2>
                  <?=($banner['description'] ? "<span>" . $banner['description'] . "</span>" : '' ); ?>
              </a>
              <?php } else { ?>
              <a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive"/></a>
              <?php } ?>
          <?php } else { ?>
            <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive"/>
          <?php } ?>
      </div>

  <?php } ?>
    </div>
     <!-- If we need navigation buttons -->
     <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>

</div>
<script>
  var mySwiper = new Swiper('#slideshow<?=$module?>', {
    loop : true,
    navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
    autoHeight: true
  },

    
  });
</script>