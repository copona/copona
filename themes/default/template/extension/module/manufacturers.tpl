<style>
  .owl-carousel.carousel-brands .owl-wrapper {
    display: flex !important;
    align-items: center;
  }

  .carousel-brands {
    padding-top: 10px;
    padding-bottom: 10px;
  }

</style>
<div id="carousel<?php echo $module; ?>" class="owl-carousel carousel-brands">

    <?php foreach ($manufacturers as $banner) {
        if (!$banner['image']) {
            continue;
        } ?>
      <div class="item text-center" style="display: flex;">
          <?php if ($banner['href']) { ?>
            <a href="<?php echo $banner['href']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['name']; ?>" class="img-responsive"/></a>
          <?php } else { ?>
            <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['name']; ?>" class="img-responsive"/>
          <?php } ?>
      </div>
    <?php } ?>
</div>
<script type="text/javascript"><!--
  $('#carousel<?php echo $module; ?>').owlCarousel({
    items: 5,
    autoPlay: 3000,
    navigation: true,
    navigationText: false,
    pagination: true
  });
  --></script>