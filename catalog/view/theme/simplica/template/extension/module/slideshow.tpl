<div id="slideshow<?php echo $module; ?>" class="banner-slideshow owl-carousel" style="opacity: 1;">
    <?php foreach ($banners as $banner) { ?>
      <div class="item">
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
<script type="text/javascript"><!--
    $('#slideshow<?php echo $module; ?>').owlCarousel({
        items: 6,
        autoPlay: 3000,
        singleItem: true,
        navigation: true,
        navigationText: ['<i class="fa fa-chevron-left fa-5x"></i>', '<i class="fa fa-chevron-right fa-5x"></i>'],
        pagination: true
    });
    --></script>