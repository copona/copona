<?php if ($reviews) { ?>
  <ul class="cp-reviews">
    <?php foreach ($reviews as $review) { ?>
      <li class="cp-review">
        <div class="cp-review__head">
          <strong><?php echo $review['author']; ?></strong>
          <span class="cp-rating" aria-label="<?php echo $review['rating']; ?>/5"><?php for ($i = 1; $i <= 5; $i++) { ?><i class="fa <?php echo $review['rating'] < $i ? 'fa-star-o' : 'fa-star'; ?>"></i><?php } ?></span>
          <time class="cp-review__date"><?php echo $review['date_added']; ?></time>
        </div>
        <p class="cp-review__text"><?php echo $review['text']; ?></p>
      </li>
    <?php } ?>
  </ul>
  <div class="cp-pagination"><?php echo $pagination; ?></div>
<?php } else { ?>
  <p class="cp-empty"><?php echo $text_no_reviews; ?></p>
<?php } ?>
