<?php echo $header; ?>
<?php
$gallery_items = [];
if ($thumb) {
    $gallery_items[] = ['popup' => $popup, 'mid' => $image_mid, 'thumb' => $thumb, 'title' => $heading_title, 'type' => 'image'];
}
foreach ($images as $gimg) {
    $gallery_items[] = ['popup' => $gimg['popup'], 'mid' => $gimg['image_mid'], 'thumb' => $gimg['thumb'], 'title' => $gimg['description'], 'type' => 'image'];
}
foreach ($product_videos ?? [] as $video) {
    $gallery_items[] = ['popup' => $video['video'], 'mid' => $video['video_src'], 'thumb' => $video['video_src'], 'title' => $heading_title, 'type' => 'video'];
}
$share_url = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
?>
<div class="container cp-page">
  <nav aria-label="breadcrumb"><ol class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
      <li class="breadcrumb-item"><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ol></nav>
  <div class="cp-layout<?php echo $column_left ? ' has-left' : ''; ?><?php echo $column_right ? ' has-right' : ''; ?>">
    <?php echo $column_left; ?>
    <div id="content"><?php echo $content_top; ?>
      <div class="cp-product">
        <div class="cp-product__gallery">
          <?php if ($gallery_items) { ?>
            <div class="gallery-main">
              <a href="<?php echo htmlspecialchars($gallery_items[0]['popup']); ?>" class="<?php echo $gallery_items[0]['type'] === 'video' ? 'gallery-video' : 'gallery-image'; ?>">
                <img id="gallery-main-img" fetchpriority="high" src="<?php echo $gallery_items[0]['mid']; ?>" alt="<?php echo htmlspecialchars($gallery_items[0]['title']); ?>">
              </a>
            </div>
            <?php if (count($gallery_items) > 1) { ?>
              <div class="gallery-thumbs">
                <?php foreach ($gallery_items as $gi => $gitem) { ?>
                  <button type="button" class="gallery-thumb<?php echo $gi === 0 ? ' active' : ''; ?>"
                          data-popup="<?php echo htmlspecialchars($gitem['popup']); ?>"
                          data-mid="<?php echo htmlspecialchars($gitem['mid']); ?>"
                          data-title="<?php echo htmlspecialchars($gitem['title']); ?>"
                          data-type="<?php echo $gitem['type']; ?>">
                    <img loading="lazy" decoding="async" src="<?php echo $gitem['thumb']; ?>" alt="<?php echo htmlspecialchars($gitem['title']); ?>">
                    <?php if ($gitem['type'] === 'video') { ?><span class="gallery-play">&#9654;</span><?php } ?>
                  </button>
                <?php } ?>
              </div>
            <?php } ?>
          <?php } ?>
        </div>

        <div class="cp-product__info">
          <?php if ($manufacturer) { ?>
            <a href="<?php echo $manufacturers; ?>" class="cp-eyebrow"><?php echo $manufacturer; ?></a>
          <?php } ?>
          <h1><?php echo $heading_title; ?></h1>
          <p class="cp-product__meta">
            <?php echo $text_model; ?> <?php echo $model; ?>
            <?php if ($review_status) { ?>
              ·
              <?php if ($rating) { ?>
                <span class="cp-rating"><?php for ($i = 1; $i <= 5; $i++) { ?><i class="fa <?php echo $rating < $i ? 'fa-star-o' : 'fa-star'; ?>"></i><?php } ?></span>
              <?php } ?>
              <a href="#tab-review" class="cp-review-link"><?php echo $reviews; ?></a>
            <?php } ?>
          </p>

          <?php if ($price) { ?>
            <div class="cp-price cp-price--lg">
              <?php if (!$special) { ?>
                <span class="cp-price__now"><?php echo $price; ?></span>
              <?php } else { ?>
                <span class="cp-price__now is-special"><?php echo $special; ?></span>
                <s class="cp-price__old"><?php echo $price; ?></s>
              <?php } ?>
              <?php if ($tax) { ?>
                <span class="cp-price__tax"><?php echo $text_tax; ?> <?php echo $tax; ?></span>
              <?php } ?>
            </div>
            <?php if ($discounts) { ?>
              <ul class="cp-discounts">
                <?php foreach ($discounts as $discount) { ?>
                  <li><?php echo $discount['quantity']; ?><?php echo $text_discount; ?><?php echo $discount['price']; ?></li>
                <?php } ?>
              </ul>
            <?php } ?>
          <?php } ?>

          <?php if ($short_description) { ?>
            <p class="cp-product__lead"><?php echo $short_description; ?></p>
          <?php } ?>

          <?php if ($group_products) { ?>
            <ul class="cp-product__group">
              <?php foreach ($group_products as $group_product) { ?>
                <li<?= ($group_product['product_id'] == $product_id ? ' class="active"' : '') ?>>
                  <a href="<?php echo $group_product['href'] ?>"><img src="<?php echo $group_product['image'] ?>" alt="<?php echo $group_product['name'] ?>" title="<?php echo $group_product['name'] ?>"></a>
                </li>
              <?php } ?>
            </ul>
          <?php } ?>

          <div id="product">
            <?php foreach ($options as $option) { ?>
              <?php $oid = $option['product_option_id']; ?>
              <div class="cp-field<?php echo ($option['required'] ? ' required' : ''); ?>">
                <?php if ($option['type'] == 'select') { ?>
                  <label class="form-label" for="input-option<?php echo $oid; ?>"><?php echo $option['name']; ?></label>
                  <select name="option[<?php echo $oid; ?>]" id="input-option<?php echo $oid; ?>" class="form-select">
                    <option value=""><?php echo $text_select; ?></option>
                    <?php foreach ($option['product_option_value'] as $option_value) { ?>
                      <option value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?><?php if ($option_value['price']) { ?> (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)<?php } ?></option>
                    <?php } ?>
                  </select>
                <?php } elseif ($option['type'] == 'radio' || $option['type'] == 'checkbox') { ?>
                  <span class="form-label"><?php echo $option['name']; ?></span>
                  <div id="input-option<?php echo $oid; ?>" class="cp-choices">
                    <?php foreach ($option['product_option_value'] as $option_value) { ?>
                      <label class="cp-choice">
                        <input type="<?php echo $option['type']; ?>" name="option[<?php echo $oid; ?>]<?php echo $option['type'] == 'checkbox' ? '[]' : ''; ?>" value="<?php echo $option_value['product_option_value_id']; ?>"/>
                        <?php if ($option_value['image']) { ?>
                          <img src="<?php echo $option_value['image']; ?>" alt=""/>
                        <?php } ?>
                        <span><?php echo $option_value['name']; ?><?php if ($option_value['price']) { ?> <small><?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?></small><?php } ?></span>
                      </label>
                    <?php } ?>
                  </div>
                <?php } elseif ($option['type'] == 'text') { ?>
                  <label class="form-label" for="input-option<?php echo $oid; ?>"><?php echo $option['name']; ?></label>
                  <input type="text" name="option[<?php echo $oid; ?>]" value="<?php echo $option['value']; ?>" id="input-option<?php echo $oid; ?>" class="form-control"/>
                <?php } elseif ($option['type'] == 'textarea') { ?>
                  <label class="form-label" for="input-option<?php echo $oid; ?>"><?php echo $option['name']; ?></label>
                  <textarea name="option[<?php echo $oid; ?>]" rows="4" id="input-option<?php echo $oid; ?>" class="form-control"><?php echo $option['value']; ?></textarea>
                <?php } elseif ($option['type'] == 'file') { ?>
                  <span class="form-label"><?php echo $option['name']; ?></span>
                  <button type="button" id="button-upload<?php echo $oid; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-outline-secondary w-100"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
                  <input type="hidden" name="option[<?php echo $oid; ?>]" value="" id="input-option<?php echo $oid; ?>"/>
                <?php } elseif (in_array($option['type'], ['date', 'datetime', 'time'])) { ?>
                  <?php $formats = ['date' => 'YYYY-MM-DD', 'datetime' => 'YYYY-MM-DD HH:mm', 'time' => 'HH:mm']; ?>
                  <label class="form-label" for="input-option<?php echo $oid; ?>"><?php echo $option['name']; ?></label>
                  <div class="input-group <?php echo $option['type']; ?>">
                    <input type="text" name="option[<?php echo $oid; ?>]" value="<?php echo $option['value']; ?>" data-date-format="<?php echo $formats[$option['type']]; ?>" id="input-option<?php echo $oid; ?>" class="form-control"/>
                    <button type="button" class="btn btn-outline-secondary" aria-label="<?php echo $option['name']; ?>"><i class="fa fa-calendar"></i></button>
                  </div>
                <?php } ?>
              </div>
            <?php } ?>

            <?php if ($recurrings) { ?>
              <div class="cp-field required">
                <label class="form-label" for="input-recurring"><?php echo $text_payment_recurring; ?></label>
                <select name="recurring_id" id="input-recurring" class="form-select">
                  <option value=""><?php echo $text_select; ?></option>
                  <?php foreach ($recurrings as $recurring) { ?>
                    <option value="<?php echo $recurring['recurring_id']; ?>"><?php echo $recurring['name']; ?></option>
                  <?php } ?>
                </select>
                <div class="form-text" id="recurring-description"></div>
              </div>
            <?php } ?>

            <div class="cp-buy">
              <div class="cp-qty">
                <button type="button" data-qty="-1" aria-label="&minus;">&minus;</button>
                <input type="text" inputmode="numeric" name="quantity" value="<?php echo $minimum; ?>" id="input-quantity" aria-label="<?php echo $entry_qty; ?>"/>
                <button type="button" data-qty="1" aria-label="+">+</button>
              </div>
              <input type="hidden" name="product_id" value="<?php echo $product_id; ?>"/>
              <button type="button" id="button-cart" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-primary btn-lg cp-buy__add"><?php echo $button_cart; ?></button>
              <button type="button" class="cp-icon-btn cp-icon-btn--lg" title="<?php echo $button_wishlist; ?>" aria-label="<?php echo $button_wishlist; ?>" onclick="wishlist.add('<?php echo $product_id; ?>');"><i class="fa fa-heart-o"></i></button>
              <button type="button" class="cp-icon-btn cp-icon-btn--lg" title="<?php echo $button_compare; ?>" aria-label="<?php echo $button_compare; ?>" onclick="compare.add('<?php echo $product_id; ?>');"><i class="fa fa-exchange"></i></button>
            </div>
            <?php if ($minimum > 1) { ?>
              <div class="alert alert-info"><i class="fa fa-info-circle"></i> <?php echo $text_minimum; ?></div>
            <?php } ?>
          </div>

          <ul class="cp-facts">
            <li><strong><?php echo $text_stock; ?></strong> <?php echo $stock; ?></li>
            <?php if ($reward) { ?>
              <li><strong><?php echo $text_reward; ?></strong> <?php echo $reward; ?></li>
            <?php } ?>
            <?php if ($points) { ?>
              <li><strong><?php echo $text_points; ?></strong> <?php echo $points; ?></li>
            <?php } ?>
          </ul>

          <div class="cp-share">
            <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode($share_url) ?>" target="_blank" rel="noopener" title="Share on Facebook">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878V14.89h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"/></svg>
            </a>
            <a href="https://x.com/intent/post?url=<?= urlencode($share_url) ?>&text=<?= urlencode($heading_title) ?>" target="_blank" rel="noopener" title="Share on X">
              <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-4.714-6.231-5.401 6.231H2.744l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
            </a>
          </div>
        </div>
      </div>

      <div class="cp-product__details">
        <ul class="nav nav-underline" role="tablist">
          <li class="nav-item" role="presentation"><a class="nav-link active" href="#tab-description" data-bs-toggle="tab" role="tab"><?php echo $tab_description; ?></a></li>
          <?php if ($attribute_groups) { ?>
            <li class="nav-item" role="presentation"><a class="nav-link" href="#tab-specification" data-bs-toggle="tab" role="tab"><?php echo $tab_attribute; ?></a></li>
          <?php } ?>
          <?php if ($review_status) { ?>
            <li class="nav-item" role="presentation"><a class="nav-link" href="#tab-review" data-bs-toggle="tab" role="tab"><?php echo $tab_review; ?></a></li>
          <?php } ?>
        </ul>
        <div class="tab-content">
          <div class="tab-pane show active cp-prose" id="tab-description"><?php echo $description; ?></div>
          <?php if ($attribute_groups) { ?>
            <div class="tab-pane" id="tab-specification">
              <?php foreach ($attribute_groups as $attribute_group) { ?>
                <h3 class="cp-spec__group"><?php echo $attribute_group['name']; ?></h3>
                <dl class="cp-spec">
                  <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
                    <div><dt><?php echo $attribute['name']; ?></dt><dd><?php echo $attribute['text']; ?></dd></div>
                  <?php } ?>
                </dl>
              <?php } ?>
            </div>
          <?php } ?>
          <?php if ($review_status) { ?>
            <div class="tab-pane" id="tab-review">
              <div id="review"></div>
              <form id="form-review" class="cp-review-form">
                <h3><?php echo $text_write; ?></h3>
                <?php if ($review_guest) { ?>
                  <div class="cp-field required">
                    <label class="form-label" for="input-name"><?php echo $entry_name; ?></label>
                    <input type="text" name="name" value="<?php echo $customer_name; ?>" id="input-name" class="form-control"/>
                  </div>
                  <div class="cp-field required">
                    <label class="form-label" for="input-review"><?php echo $entry_review; ?></label>
                    <textarea name="text" rows="5" id="input-review" class="form-control"></textarea>
                    <div class="form-text"><?php echo $text_note; ?></div>
                  </div>
                  <fieldset class="cp-field required">
                    <legend class="form-label"><?php echo $entry_rating; ?></legend>
                    <div class="cp-stars-input">
                      <span><?php echo $entry_bad; ?></span>
                      <?php for ($r = 1; $r <= 5; $r++) { ?>
                        <input type="radio" name="rating" value="<?php echo $r; ?>" aria-label="<?php echo $r; ?>"/>
                      <?php } ?>
                      <span><?php echo $entry_good; ?></span>
                    </div>
                  </fieldset>
                  <?php echo $captcha; ?>
                  <button type="button" id="button-review" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-dark"><?php echo $button_continue; ?></button>
                <?php } else { ?>
                  <p><?php echo $text_login; ?></p>
                <?php } ?>
              </form>
            </div>
          <?php } ?>
        </div>
      </div>

      <?php if ($products) { ?>
        <section class="cp-section">
          <h2 class="cp-section__title"><?php echo $text_related; ?></h2>
          <div class="cp-product-grid">
            <?php foreach ($products as $product) { ?>
              <?php include(__DIR__ . '/../common/_product_card.tpl'); ?>
            <?php } ?>
          </div>
        </section>
      <?php } ?>
      <?php if ($tags) { ?>
        <nav class="cp-chips cp-tags" aria-label="<?php echo $text_tags; ?>">
          <span><?php echo $text_tags; ?></span>
          <?php foreach ($tags as $tag) { ?>
            <a href="<?php echo $tag['href']; ?>"><?php echo $tag['tag']; ?></a>
          <?php } ?>
        </nav>
      <?php } ?>
      <?php echo $content_bottom; ?>
    </div>
    <?php echo $column_right; ?>
  </div>
</div>
<script>
$('select[name=\'recurring_id\'], input[name="quantity"]').change(function () {
        $.ajax({
            url: 'index.php?route=product/product/getRecurringDescription',
            type: 'post',
            data: $('input[name=\'product_id\'], input[name=\'quantity\'], select[name=\'recurring_id\']'),
            dataType: 'json',
            beforeSend: function () {
                $('#recurring-description').html('');
            },
            success: function (json) {
                $('.alert, .text-danger').remove();

                if (json['success']) {
                    $('#recurring-description').html(json['success']);
                }
            }
        });
    });
</script>
<script>
    $('#button-cart').on('click', function () {
        $.ajax({
            url: 'index.php?route=checkout/cart/add',
            type: 'post',
            data: $('#product input[type=\'text\'], #product input[type=\'hidden\'], #product input[type=\'radio\']:checked, #product input[type=\'checkbox\']:checked, #product select, #product textarea'),
            dataType: 'json',
            beforeSend: function () {
                $('#button-cart').button('loading');
            },
            complete: function () {
                $('#button-cart').button('reset');
            },
            success: function (json) {
                $('.alert, .text-danger').remove();
                $('.cp-field').removeClass('is-invalid');

                if (json['error']) {
                    if (json['error']['option']) {
                        for (i in json['error']['option']) {
                            var element = $('#input-option' + i.replace('_', '-'));

                            if (element.parent().hasClass('input-group')) {
                                element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
                            } else {
                                element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
                            }
                        }
                    }

                    if (json['error']['recurring']) {
                        $('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
                    }

                    // Highlight any found errors
                    $('.text-danger').parent().addClass('is-invalid');
                }

                if (json['success']) {
                    var toastEl = document.getElementById('cart-toast');
                    if (toastEl) {
                        $('#cart-toast-product').text(json['product_name'] || '');
                        if (json['cart_url']) {
                            $('#cart-toast-link').attr('href', json['cart_url']);
                        }
                        bootstrap.Toast.getOrCreateInstance(toastEl).show();
                    }
                    $('#cart').load('index.php?route=common/cart/info');
                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

</script>
<script>
    $('button[id^=\'button-upload\']').on('click', function () {
        var node = this;

        $('#form-upload').remove();

        $('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

        $('#form-upload input[name=\'file\']').trigger('click');

        if (typeof timer != 'undefined') {
            clearInterval(timer);
        }

        timer = setInterval(function () {
            if ($('#form-upload input[name=\'file\']').val() != '') {
                clearInterval(timer);

                $.ajax({
                    url: 'index.php?route=tool/upload',
                    type: 'post',
                    dataType: 'json',
                    data: new FormData($('#form-upload')[0]),
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function () {
                        $(node).button('loading');
                    },
                    complete: function () {
                        $(node).button('reset');
                    },
                    success: function (json) {
                        $('.text-danger').remove();

                        if (json['error']) {
                            $(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
                        }

                        if (json['success']) {
                            alert(json['success']);

                            $(node).parent().find('input').val(json['code']);
                        }
                    },
                    error: function (xhr, ajaxOptions, thrownError) {
                        alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                    }
                });
            }
        }, 500);
    });
</script>
<script>
    $('#review').delegate('.pagination a', 'click', function (e) {
        e.preventDefault();

        $('#review').fadeOut('slow');

        $('#review').load(this.href);

        $('#review').fadeIn('slow');
    });

    $('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');

    $('#button-review').on('click', function () {
        $.ajax({
            url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
            type: 'post',
            dataType: 'json',
            data: $("#form-review").serialize(),
            beforeSend: function () {
                $('#button-review').button('loading');
            },
            complete: function () {
                $('#button-review').button('reset');
            },
            success: function (json) {
                $('.alert-success, .alert-danger').remove();

                if (json['error']) {
                    $('#review').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
                }

                if (json['success']) {
                    $('#review').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

                    $('input[name=\'name\']').val('');
                    $('textarea[name=\'text\']').val('');
                    $('input[name=\'rating\']:checked').prop('checked', false);
                }
            }
        });
    });

    $('.cp-qty button').on('click', function () {
        var input = $('#input-quantity');
        input.val(Math.max(1, (parseInt(input.val(), 10) || 1) + parseInt($(this).data('qty'), 10)));
    });

    $('.cp-review-link').on('click', function (e) {
        e.preventDefault();
        bootstrap.Tab.getOrCreateInstance(document.querySelector('a[href="#tab-review"]')).show();
        document.querySelector('.cp-product__details').scrollIntoView({behavior: 'smooth'});
    });

    $(document).ready(function () {
        // Gallery thumbnail click
        $('.gallery-thumb').on('click', function () {
            var popup = $(this).data('popup');
            var mid   = $(this).data('mid');
            var title = $(this).data('title');
            var type  = $(this).data('type');
            $('.gallery-thumb').removeClass('active');
            $(this).addClass('active');
            $('#gallery-main-img').attr('src', mid).attr('alt', title);
            var $a = $('.gallery-main a');
            $a.attr('href', popup);
            $a.removeClass('gallery-image gallery-video').addClass(type === 'video' ? 'gallery-video' : 'gallery-image');
        });

        // Build items array from thumbnails for Magnific gallery
        var mfpItems = [];
        $('.gallery-thumb').each(function () {
            mfpItems.push({
                src:  $(this).data('popup'),
                type: $(this).data('type') === 'video' ? 'iframe' : 'image'
            });
        });

        $('.gallery-main a').on('click', function (e) {
            e.preventDefault();
            if (mfpItems.length > 0) {
                $.magnificPopup.open({
                    items:   mfpItems,
                    gallery: { enabled: true },
                    type:    'image'
                }, $('.gallery-thumb.active').index());
            } else {
                $.magnificPopup.open({
                    items: [{ src: $(this).attr('href'), type: 'image' }],
                    type: 'image'
                });
            }
        });
    });
</script>
<?php echo $footer; ?>
